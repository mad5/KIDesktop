<?php
namespace classes;

class DBps {
	// {{{
	public $host;
	public $user;
	public $pass;
	public $link;
	public $name;
	public $res;
	public $data;
	public $characterset;
	public $develop=false;
	public $useDirectCall=false;
    public $connected = false;
	public $AESFields = array();
	public $AESkey = "";

	public function __construct($host, $user, $pass, $name, $characterset) {
		// {{{
		
		if(!function_Exists("mysqli_connect")) die("function mysqli_connect does not exists!");
		
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->name = $name;
		$this->characterset = $characterset;
		$this->link = @mysqli_connect($this->host, $this->user, $this->pass, $this->name);
		#var_dump($this->link);
		if($this->link instanceOf \mysqli) {
			$this->connected = true;
		} else {
			die("sorry, no database-connection");
		}

		//$this->connected = true;
		//var_dump($this->connected);

		unset($this->host);
		unset($this->user);
		unset($this->pass);

		if($this->characterset!="") {
			$this->setCharaterSet($this->characterset);
		}
		
		if(defined('cacheDBfolder')) {
			$cacheTablesFN = projectPath.'/cache/cacheTables.ser';
			if(!file_exists($cacheTablesFN)) {
				$T = $this->getAllByQuery("show tables");
				$this->uncachetabs = array();
				for($i=0;$i<count($T);$i++) {
					foreach($T[$i] as $key => $value) {
						$this->uncachetabs[] = $value;
					}
				}
				file_put_contents($cacheTablesFN, serialize($this->uncachetabs));
			} else {
				$this->uncachetabs = unserialize(file_get_contents($cacheTablesFN));
			}
			$this->cacheTabCount = count($this->uncachetabs);
			#vd($this->uncachetabs);
		}
		
		//return $this->res; 
		// }}}
	}
	
	function setCharaterSet($cs) {
		mysqli_query($this->link, 'SET character_set_results = '.$cs);
		mysqli_query($this->link, 'SET character_set_client = '.$cs);
		mysqli_query($this->link, "SET NAMES '".$cs."'");
	}
	
	function close(){
		return $this->res = mysqli_close($this->link);
	}
	function sendQuery($str){
		return $this->query($str);
	}
	
	function getCacheHandle($str) {
		$cacheHandle = array();
		for($i=0;$i<$this->cacheTabCount;$i++) {
			if(stristr($str, $this->uncachetabs[$i])) $cacheHandle[] = $this->uncachetabs[$i];
		}
		
		if(stristr($str,'delete') || stristr($str, 'update') || stristr($str, 'replace') ) {
		    for($i=0;$i<count($cacheHandle);$i++) {
			$ch_rev = projectPath.'/cache/cacheTablesRev'.$cacheHandle[$i].'.rev';
			file_put_contents($ch_rev, "x", FILE_APPEND);
		    }
		}
		
		return $cacheHandle;
	}
	function getCacheRev($cacheHandle) {
	    $rev = "";

	    $ch_rev = projectPath.'/cache/cacheTablesRev_global.rev';
	    $cacheHandleRev = 0;
	    if(file_exists($ch_rev)) {
		    $cacheHandleRev = filesize($ch_rev);
	    } else {
		touch($ch_rev);
	    }
	    $rev .= $cacheHandleRev;

	    for($i=0;$i<count($cacheHandle);$i++) {
		$ch_rev = projectPath.'/cache/cacheTablesRev'.$cacheHandle[$i].'.rev';
		$cacheHandleRev = 0;
		if(file_exists($ch_rev)) {
			$cacheHandleRev = filesize($ch_rev);
		} else {
		    touch($ch_rev);
		}
		#if($i>0) $rev .= '_';
		$rev .= '_';
		$rev .= $cacheHandleRev;
	    }
	    return $cacheHandleRev;
	}
	
	function query($str){
		
		if(!stristr($str, 'LAST_INSERT_ID()')) {
			$GLOBALS["lastQuery"] = $str;
			$this->lastQuery = $str;
		}
		
		/*if($this->develop) {
		    if($this->useDirectCall!=true) {
			try {
				throw new Exception;
			} catch(Exception $e) {
				 $T = $e->getTrace();
				 if(stristr($T[1]['file'],'/modul/') && !stristr($T[1]['file'],'/classes/')) {
					
					 echo "<span style='color:red;font-weight:bold;'>";
					 echo "used direct databasecall in:<br>".$T[1]['file']."<br>at line ".$T[1]['line'];
					 exit;
				 }
			}
		    }
		}*/

		$T = microtime(true);
		
		$res = $this->res = mysqli_query($this->link, $str);
		
		if($this->res==false) {
			$this->logError(mysqli_error($this->link));
		}
		
		$GLOBALS["COUNTQUERIES"]++;
		
		$d = microtime(true)-$T;
		$GLOBALS["SUMTIME"] += $d;
		if(!isset($GLOBALS["SLOWESTQUERY"]) || $d>$GLOBALS["SLOWESTQUERY"]) {
			$GLOBALS["SLOWESTQUERY"] = $d;
		}
		
		#vd($d);exit;
		if( $d > 0.1 /*&& $_SERVER["REMOTE_ADDR"]=="84.44.133.66"*/) {
			file_put_contents(projectPath.'/cache/slowquery_'.date("Ym").'.log', date("d.m.Y H:i:s")."\t".$d."\t".$str."\n\n", FILE_APPEND);
		}
		
#vd($str);
		if ($this->develop && mysqli_errno($this->link)) {
			vd($str);
			try {
				throw new \Exception;
			} catch(Exception $e) {
				 $T = $e->getTrace();
			}
			die("MySQL error ".mysqli_errno($this->link).": ".mysqli_error($this->link)."\n<br>When executing:<br>\n$str\n<br><pre>".print_r($T,1)."</pre>");
		}
		#$T = microtime(true)-$T;
		#if($_SERVER['REMOTE_ADDR']=="192.168.1.81") addLog(number_format($T,4)."\t".$str);
		return $res;
	}
	function fetch(){
		$data = array();
		if($this->res===false || $this->res===true) {$this->logError($this->lastQuery);return $data;}
		return $data = $this->data = mysqli_fetch_array($this->res,  MYSQLI_ASSOC );
	}
	function fetchAll(){
		$data = array();
		if($this->res===false || $this->res===true) {$this->logError($this->lastQuery);return $data;}
		while ($this->data = mysqli_fetch_array($this->res,  MYSQLI_ASSOC )) {
			$data[] = $this->data;
		}
		return $data;
	}
	
	function logError($str) {
		if(function_exists("doLogError")) {
			doLogError( $this->lastQuery , $str);
		}
	}
	
	function enableCaching($query='') {
		if(!defined('cacheDBfolder')) return false;
		if(stristr($query,'AUSSTELLER_CROSS')) return false;
		#if(stristr($query,'SQL_CALC_FOUND_ROWS')) return false;
		return true;
	}
	
	function countByQuery($query) {
		// {{{
		if($this->enableCaching($query)) {
			$cacheHandleRev = $this->getCacheRev($this->getCacheHandle($query));
			
			$queryHash = md5($query);
			$queryHashFile = projectPath.'/cache/cacheTables_count_'.$queryHash.'_'.$cacheHandleRev.".ser";
		}
		if($this->enableCaching($query) && file_Exists($queryHashFile)) {
			$value = file_get_contents($queryHashFile);
			touch($queryHashFile);
			file_put_contents(projectPath.'/cache/cacheTablesCount.hit', "x", FILE_APPEND);
			return $value;
		} else {
			if($this->enableCaching($query)) file_put_contents(projectPath.'/cache/cacheTablesCount.miss', "x", FILE_APPEND);
			$this->query($query);
			$this->data = $this->fetch();
		}
		foreach($this->data as $key => $value) {
			if($this->enableCaching($query)) file_put_contents($queryHashFile, $value);
			return($value);
		}
		
		// }}}
	}
	function getByQuery($query, $feld="", $canCache=true) {
		// {{{
		if($canCache && $this->enableCaching($query))  {
			if(stristr($query, 'SELECT FOUND_ROWS()')) {
				return $this->foundRows;
			}
		}
		if($canCache && $this->enableCaching($query))  {
			$cacheHandleRev = $this->getCacheRev($this->getCacheHandle($query));
			
			$queryHash = md5($query);
			$queryHashFile = projectPath.'/cache/cacheTables_'.$queryHash.'_'.$cacheHandleRev.".ser";
		}
		if($canCache && $this->enableCaching($query) && !stristr($query, 'FOUND_ROWS()') && file_Exists($queryHashFile)) {
			$this->data = unserialize(file_get_contents($queryHashFile));
			touch($queryHashFile);
			file_put_contents(projectPath.'/cache/cacheTablesOne.hit', "x", FILE_APPEND);
		} else {
			if($canCache && $this->enableCaching($query)) file_put_contents(projectPath.'/cache/cacheTablesOne.miss', "x", FILE_APPEND);
			$res = $this->query($query);
			$D = $this->fetch();
			mysqli_free_result($res);
			if(stristr($query, 'SELECT FOUND_ROWS()')) {
				if($feld!="") return($D[$feld]);
				return($D);
			}
			$this->data = $D;
			#if($_SERVER["REMOTE_ADDR"]=="84.44.233.5")
			if($canCache && $this->enableCaching($query)) if($this->data!='' && !stristr($query,'SELECT FOUND_ROWS()')) file_put_contents($queryHashFile, serialize($this->data));
		}
		if($feld!="") return($this->data[$feld]);
		return($this->data);
		// }}}
	}
	function getAllByQuery($query, $justField='') {
		// {{{

		if($this->enableCaching($query)) {
			$cacheHandleRev = $this->getCacheRev($this->getCacheHandle($query));
			$queryHash = md5($query);
			$queryHashFile = projectPath.'/cache/cacheTables_all_'.$queryHash.'_'.$cacheHandleRev.".ser";
			$this->lastQueryHashFile = $queryHashFile;
		}
		if($this->enableCaching($query) && file_Exists($queryHashFile)) {
			$this->data = unserialize(file_get_contents($queryHashFile));
			touch($queryHashFile);
			file_put_contents(projectPath.'/cache/cacheTablesAll.hit', "x", FILE_APPEND);
			
			if(stristr($query,'SQL_CALC_FOUND_ROWS')) {
				touch($queryHashFile.'.foundRows');
				$this->foundRows = (int)file_get_contents($queryHashFile.'.foundRows');
			}
			
		} else {

			$res = $this->query($query);
			$this->data = $this->fetchAll();
			if($res!=false) mysqli_free_result($res);
			if(stristr($query,'SQL_CALC_FOUND_ROWS')) {
				$this->query('SELECT FOUND_ROWS() AS anzahl');
				$D = mysqli_fetch_array($this->res);
				$this->foundRows = $D['anzahl'];
			}

			if($this->enableCaching($query)) file_put_contents(projectPath.'/cache/cacheTablesAll.miss', "x", FILE_APPEND);
			if($this->enableCaching($query)) {
				if(stristr($query,'SQL_CALC_FOUND_ROWS')) {
					$this->query('SELECT FOUND_ROWS() AS anzahl');
					$D = mysqli_fetch_array($this->res);
					$this->foundRows = $D['anzahl'];
				}
				if($this->data!=''  ) { // && count($this->data)<1000
					file_put_contents($queryHashFile, serialize($this->data));
					if(stristr($query,'SQL_CALC_FOUND_ROWS')) file_put_contents($queryHashFile.'.foundRows', (int)$this->foundRows);
				}
			}
		}
		
		
		if($justField!='') {
			$D = array();
			for($i=0;$i<count($this->data);$i++) {
				// {{{
				$D[] = $this->data[$i][$justField];
				// }}}
			}
			return($D);
		}
		return($this->data);
		// }}}
	}
	function setDbTable($table) {
		// {{{
		$this->DBTable = $table;
		// }}}
	}
	
	public function setAESFields($fields=array(), $AESkey='') {
		$this->AESFields = $fields;
		$this->AESkey = $AESkey;
	}
	
	function insert($data, $table="") {
		// {{{
		
		if($table=="") $table = $this->DBTable;

		reset ($data);
		$QN = "";
		$QV = "";
		while (list ($key, $val) = each ($data)) {
			if($QN!="") $QN .= ",";
			$QN .= $key;
			if($QV!="") $QV .= ",";
			if(in_array($key, $this->AESFields)) {
				$QV .= "AES_ENCRYPT('".$this->sql_escape($val)."', '".$this->AESkey."')";
			} else if(substr($val,0,9)=="password(") {
				$QV .= $val;
			} else {
                            if($val==="**NULL**") $QV .= "NULL";
			    else $QV .= "'".$this->sql_escape($val)."'";
			}
		}
		$q = "INSERT INTO $table ($QN) VALUES ($QV)";
		//vd($q);
                $this->lastQuery = $q;
		$this->query($q);
		$pk = $this->lastID();
		return($pk);
		// }}}
	}

	function update($data, $table, $pk, $pk_field="") {
		// {{{
		
		if($pk_field=="") {
			$pk_field = $pk;
			$pk = $table;
			$table = $this->DBTable;
		}

		reset ($data);
		$Q = "";
		while (list ($key, $val) = each ($data)) {
			if($Q!="") $Q .= ",";
			$Q .= $key."=";
			if(in_array($key, $this->AESFields)) {
				$QV .= "AES_ENCRYPT('".$this->sql_escape($val)."', '".$this->AESkey."')";
			} else if(substr($val,0,9)=="password(") {
				$Q .= $val;
			} else {
                            if($val==="**NULL**") $Q .= "NULL";
                            else $Q .= "'".$this->sql_escape($val)."'";
			}
		}
		$q = "UPDATE $table SET $Q  WHERE $pk_field='$pk' ";
		$this->lastQuery = $q;
		$this->query($q);
		// }}}
	}

	function matchArrayWithTable($array, $table) {
		// Hier fehlt noch das matichng
		return $array;
	}

	function lastID() {
		// {{{
		$q = "SELECT LAST_INSERT_ID() as LID";
		$X = $this->getByQuery($q);
		$lastId = $X["LID"];
		return($lastId);
		// }}}
	}
	function DB_error($DB){
		if (!$DB->res){
			return die('Ungueltige Abfrage: ' . mysqli_error($this->link));
		}
	}
	
	function getAllFields($table) {
		// {{{
		$q = 'DESCRIBE '.$table;
		$f = $this->getAllByQuery($q);
		return($f);
		// }}}
	}
	function fieldExists($table, $field) {
		// {{{
		$f = $this->getAllFields($table);
		for($i=0;$i<count($f);$i++) {
			// {{{
			if($f[$i]['Field']==$field) return(true);
			// }}}
		}
		return(false);
		// }}}
	}

	function getAllTables() {
		// {{{
		$q = 'SHOW TABLES';
		$f = $this->getAllByQuery($q);
#var_dump($f);exit;
		if ($f=="" || $f==null || $f==false || $f==array()) return array();
		$k = array_keys($f[0]);
		
		$T = array();
		for($i=0;$i<count($f);$i++) {
			// {{{
			$T[] = $f[$i][$k[0]];
			// }}}
		}
		return($T);
		// }}}
	}
	function tableExists($table) {
		// {{{
		$T = $this->getAllTables();
		#vd($T);
		for($i=0;$i<count($T);$i++) {
			// {{{
			#vd(array($T[$i] == $table, $T[$i] ,$table));
			if($T[$i] == $table) return(true);
			// }}}
		}
		return(false);
		// }}}
	}

	public function sql_escape($data) {
		#if(!is_string($data)) {
		if(is_object($data) || is_array($data)) {
			return "";
			vd(getTrace());
			var_dump($data);
			exit;
		}
		return addslashes($data);
		#return mysqli_real_escape_string($data);
	}

public function concat($a,$b,$c='',$d='',$e='',$f='',$g='') {
		$list = array($a,$b);
		if($c!="") $list[] = $c;
		if($d!="") $list[] = $d;
		if($e!="") $list[] = $e;
		if($f!="") $list[] = $f;
		if($g!="") $list[] = $g;
		$res = "concat(".implode(",", $list).")";
		return $res;
	}

	// }}}
}
// }}}

class DBDebug extends DBps {
	// {{{
	function query($str){
		$T0 = microtime(true);
		$this->res = mysqli_query($this->link, $str);
		$T1 = microtime(true)-$T0;
		if($T1>0.05) {
			$str = str_replace("\n", ' ', $str);
			$str = str_replace("\r", ' ', $str);
			$str = str_replace("\t", ' ', $str);
			
			addFile(projectPath.'/cache/db.log', date('d.m.Y H:i:s')."\t".number_format($T1,8,".","")."\t".$str."\n");
		}
		return $this->res;
	}
	// }}}
}

class DBnotAvailable {
	function __call($fName, $fArgs) {
		// {{{
		die('Sorry, your DB-Class is not intilialized!');
		// }}}
	}
}

?>
