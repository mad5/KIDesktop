<?php
namespace classes;
/*
usage:

<?php
include "class.db.php";
$host = "localhost";
$user = "USER";
$pass = "PASS";
$name = "DATABASE";
$characterset="utf8";

$DC = new DB($host, $user, $pass, $name, $characterset);

?>

http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers

*/
class DBpdo {
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
    public $AESFields = array();
    public $AESkey = "";
    public $connected = false;

	public function __construct($host, $user, $pass, $name, $characterset="utf8") {
		// {{{
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->name = $name;
		$this->characterset = $characterset;
        $this->link = new \PDO("sqlsrv:Server=".$this->host.";Database=".$this->name, $this->user, $this->pass);
		$this->link->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		if($this->link!==false) $this->connected = true;
		
		unset($this->host);
		unset($this->user);
		unset($this->pass);

		if($this->characterset!="") {
			$this->setCharaterSet($this->characterset);
		}
		
		return $this->link;
		// }}}
	}
	
	function setCharaterSet($cs) {
        // @todo
		#mysql_query('SET character_set_results = '.$cs, $this->link);
		#mysql_query('SET character_set_client = '.$cs, $this->link);
		#mysql_query("SET NAMES '".$cs."'", $this->link);
	}
	
	function close(){
        // @todo
		#return $this->res = mysql_close($this->link);
	}
	function sendQuery($str){
        try {
            $this->link->beginTransaction();
            $stmt = $this->link->prepare($str);
            $stmt->execute();
            $this->link->commit();

			if(isset($GLOBALS["protocolAllQueries"]) && $GLOBALS["protocolAllQueries"]==1) {
				file_put_contents(projectPath.'/cache/protocolQuery_'.date("Ym").'.log', date("d.m.Y H:i:s")."\t".$d."\t".$str."\n\n", FILE_APPEND);
			}

        } catch(\PDOException $ex) {
            $this->link->rollBack();
            $this->logError($ex->getMessage());
        }

	}
	

	function query($str) {
		
		$T = microtime(true);

		if(defined('ENABLE_DB_SHORT_CACHE') && ENABLE_DB_SHORT_CACHE==true) {
			if(substr($str,0,6)=="DELETE" || substr($str,0,6)=="UPDATE" || stristr($str, "REPLACE") || stristr($str, "INSERT") || !stristr($str, "SELECT") ) {
				if(isset($GLOBALS["protocolAllQueries"]) && $GLOBALS["protocolAllQueries"]==1) {
					file_put_contents(projectPath.'/cache/protocolQuery_'.date("Ym").'.log', date("d.m.Y H:i:s")."\t".$d."\t-----------------------------\n\n", FILE_APPEND);
				}
				$GLOBALS["fastFWShortCache"] = array();
			}
		}

        try {
			$GLOBALS["lastFWQuery"] = array("query" => $str);
            $stmt = $this->link->query($str);
			if (1==2 && $this->develop) {
				if ($stmt===false) {

					try {
						throw new \Exception;
					} catch(\Exception $e) {
						$T = $e->getTrace();
					}
					die("SQL-Error\n<br>When executing:<br>\n$str\n<br><pre>".print_r($T,1)."</pre>");
				}
			}

        } catch(\PDOException $ex) {
            $this->logError($ex->getMessage());

            if ($this->develop) {
                try {
                    throw new \Exception;
                } catch(\Exception $e) {
                    $T = $e->getTrace();
                }
                die("SQL-Error ".$ex->getMessage()."\n<br>When executing:<br>\n$str\n<br><pre>".print_r($T,1)."</pre>");
            }
            $stmt = false;
            #vd($GLOBALS["lastFWQuery"]);
			throw new \InvalidArgumentException('SQL-Error', 10);
        }
		$d = microtime(true)-$T;

		if(isset($GLOBALS["protocolAllQueries"]) && $GLOBALS["protocolAllQueries"]==1) {
			/*if(stristr($str,"SELECT * FROM questionpool")) {
				vd($str);
				vd(getTrace());exit;
			}*/
			file_put_contents(projectPath.'/cache/protocolQuery_'.date("Ym").'.log', date("d.m.Y H:i:s")."\t".$d."\t".$str."\n\n", FILE_APPEND);
		}

		if( $d > 0.5 /*&& $_SERVER["REMOTE_ADDR"]=="84.44.133.66"*/) {
			file_put_contents(projectPath.'/cache/slowquery_'.date("Ym").'.log', date("d.m.Y H:i:s")."\t".$d."\t".$str."\n\n", FILE_APPEND);
		}

        $this->res = $stmt;
        return $stmt;
	}
	function fetch() {
		$data = array();
		if($this->res===false || $this->res===true) {$this->logError($this->lastQuery);return $data;}
        $this->data = $data = $this->res->fetch(\PDO::FETCH_ASSOC);
		return $data;
	}
	function fetchAll() {
		$data = array();
		if($this->res===false || $this->res===true) {$this->logError($this->lastQuery);return $data;}
		while ($this->data = $this->res->fetch(\PDO::FETCH_ASSOC)) {
			$data[] = $this->data;
		}
		return $data;
	}
	
	function logError($str) {
		if(function_exists("doLogError")) {
			doLogError( $this->lastQuery , $str);
		}
	}

	/*
	 * Exception wenn Query fehlerhaft
	 * Anzahl als Integer
	 */
	function countByQuery($query) {
		// {{{
		if(defined('ENABLE_DB_SHORT_CACHE') && ENABLE_DB_SHORT_CACHE==true) {
			if(isset($GLOBALS["fastFWShortCache"]["countByQuery"][md5($query)])) return $GLOBALS["fastFWShortCache"]["countByQuery"][md5($query)];
		}

		$this->query($query);
		$data = $this->fetch();

		foreach($data as $key => $value) {

			if(defined('ENABLE_DB_SHORT_CACHE') && ENABLE_DB_SHORT_CACHE==true) {
				$GLOBALS["fastFWShortCache"]["countByQuery"][md5($query)] = $value;
			}

			return($value);
		}
		
		// }}}
	}
	/*
	 * Exception wenn Query fehlerhaft
	 * NULL wenn nichts gefunden
	 * 1-dimensionales assoziativ-Array mit Daten
	 */
	function getByQuery($query, $feld="", $canCache=true) {
		// {{{

		if(defined('ENABLE_DB_SHORT_CACHE') && ENABLE_DB_SHORT_CACHE==true) {
			if(isset($GLOBALS["fastFWShortCache"]["getByQuery"][md5($query."/".$feld)])) return $GLOBALS["fastFWShortCache"]["getByQuery"][md5($query."/".$feld)];
		}

		$this->query($query);
		$this->lastQuery = $query;
		$D = $this->fetch();
		if($D===false) return NULL;
		if(stristr($query, 'SELECT FOUND_ROWS()')) {

			if(defined('ENABLE_DB_SHORT_CACHE') && ENABLE_DB_SHORT_CACHE==true) {
				$GLOBALS["fastFWShortCache"]["getByQuery"][md5($query . "/" . $feld)] = ($feld!="" ? $D[$feld] : $D);
			}

			if($feld!="") return($D[$feld]);
			return($D);
		}
		$this->data = $D;

		if(defined('ENABLE_DB_SHORT_CACHE') && ENABLE_DB_SHORT_CACHE==true) {
			$GLOBALS["fastFWShortCache"]["getByQuery"][md5($query . "/" . $feld)] = ($feld!="" ? $this->data[$feld] : $this->data);
		}

		if($feld!="") return($this->data[$feld]);
		return($this->data);
		// }}}
	}
	/*
	 * Exception wenn Query fehlerhaft
	 * leeres Array wenn nichts gefunden
	 * 2-dimensionales assoziativ-Array mit Daten
	 */

	function getAllByQuery($query, $justField='') {
		// {{{

		if(defined('ENABLE_DB_SHORT_CACHE') && ENABLE_DB_SHORT_CACHE==true) {
			if(isset($GLOBALS["fastFWShortCache"]["getAllByQuery"][md5($query."/".$justField)])) return $GLOBALS["fastFWShortCache"]["getAllByQuery"][md5($query."/".$justField)];
		}

		$this->query($query);
		$this->lastQuery = $query;
		$data = $this->fetchAll();

		$Q = $query;
		if(stristr($Q,"OFFSET ")) {
			$Q = substr($Q,0, strpos($Q, "OFFSET "));
		}
		if(stristr($Q,"ORDER BY")) {
			$Q = substr($Q,0, strpos($Q, "ORDER BY"));
		}

		$Z = str_zwischen($Q, "SELECT", "FROM");
		if(!stristr($query,"OFFSET")) {
		#	vd(array(1, $Q));
			$this->foundRows = count($data);
		} else if(stristr($Z,"(")) {
			$this->foundRows = count($data);

		} else {

			if (stristr($query, "GROUP BY")) {

				$Z = str_zwischen($Q, "SELECT", "FROM");
				$Zx = explode(",", $Z);
				$Z2 = $Zx[0];
				$Q = str_replace("SELECT" . $Z . "FROM", "SELECT count(" . $Z2 . ") FROM", $Q);
			} else {

				$Q = str_replace(str_zwischen($Q, "SELECT", "FROM"), " count(*) ", $Q);
			}
			$this->foundRows = $this->countByQuery($Q);
		}

		if(stristr($query,'SQL_CALC_FOUND_ROWS')) {
			$this->query('SELECT FOUND_ROWS() AS anzahl');
			$D = $this->fetch();
			$this->foundRows = $D['anzahl'];
		}

		$this->data = $data;

		if($justField!='') {
			$D = array();
			for($i=0;$i<count($this->data);$i++) {
				$D[] = $this->data[$i][$justField];
			}

			if(defined('ENABLE_DB_SHORT_CACHE') && ENABLE_DB_SHORT_CACHE==true) {
				$GLOBALS["fastFWShortCache"]["getAllByQuery"][md5($query."/".$justField)] = $D;
			}

			return($D);
		}

		if(defined('ENABLE_DB_SHORT_CACHE') && ENABLE_DB_SHORT_CACHE==true) {
			$GLOBALS["fastFWShortCache"]["getAllByQuery"][md5($query."/".$justField)] = $this->data;
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

	/**
	 * MS-SQL Escaping: http://stackoverflow.com/questions/574805/how-to-escape-strings-in-sql-server-using-php
	 * @param $data
	 *
	 * @return mixed
	 */
	public function mssql_escape($data) {
		if ( !isset($data) or empty($data) ) return '';
		if ( is_numeric($data) ) return $data;

		$non_displayables = array(
			'/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
			'/%1[0-9a-f]/',             // url encoded 16-31
			'/[\x00-\x08]/',            // 00-08
			'/\x0b/',                   // 11
			'/\x0c/',                   // 12
			'/[\x0e-\x1f]/'             // 14-31
		);
		foreach ( $non_displayables as $regex )
			$data = preg_replace( $regex, '', $data );
		$data = str_replace("'", "''", $data );
		return $data;
	}

	public function sql_escape($data) {
		return $this->mssql_escape($data);
	}
	/*
	 * Exception wenn Query fehlerhaft
	 * pk zurückliefern
	 */
	function insert($data, $table="") {
		// {{{

		$data = $this->matchArrayWithTable($data, $table);

		$keys = array();
		$vals = array();
		$qms = array();
		foreach($data as $key => $val) {
			$keys[] = $key;
			$vals[] = $val;
			$qms[] = "?";
		}
		$Q = "INSERT INTO ".$table." (";
		$Q .= implode(", ",$keys);
		$Q .= ") VALUES ( ";
		$Q .= implode(", ",$qms);
		$Q .= ") ";
		#vd($Q);exit;

		$GLOBALS["lastFWQuery"] = array("preparedQuery" => $Q, "vals" => $vals);

		try {
			$sth = $this->link->prepare($Q);
			$sth->execute($vals);
		} catch(\PDOException $ex) {
			vd($Q);
			vd($vals);
			vd($ex->getMessage());
			//$this->logError($ex->getMessage());
			exit;
		}

		//$q = $this->insertQuery($data, $table);

        $this->lastQuery = $Q;
		#$this->query($q);
		$pk = $this->lastID(); // @todo

		if(isset($GLOBALS["protocolAllQueries"]) && $GLOBALS["protocolAllQueries"]==1) {
			file_put_contents(projectPath.'/cache/protocolQuery_'.date("Ym").'.log', date("d.m.Y H:i:s")."\t".$d."\t".$str."\n\n", FILE_APPEND);
		}
		if(defined('ENABLE_DB_SHORT_CACHE') && ENABLE_DB_SHORT_CACHE==true) {
			if (isset($GLOBALS["protocolAllQueries"]) && $GLOBALS["protocolAllQueries"] == 1) {
				file_put_contents(projectPath . '/cache/protocolQuery_' . date("Ym") . '.log', date("d.m.Y H:i:s") . "\t" . $d . "\t-----------------------------\n\n", FILE_APPEND);
			}
			$GLOBALS["fastFWShortCache"] = array();
		}

		return($pk);
		// }}}
	}

	function insertQuery($data, $table="") {
		// {{{

		if($table=="") $table = $this->DBTable;

		$data = $this->matchArrayWithTable($data, $table);

		reset ($data);
		$QN = "";
		$QV = "";
		while (list ($key, $val) = each ($data)) {
			if($QN!="") $QN .= ",";
			$QN .= $key;
			if($QV!="") $QV .= ",";
			if(in_array($key, $this->checkForArray($this->AESFields))) {
				$QV .= "AES_ENCRYPT('".addslashes($val)."', '".$this->AESkey."')";
			} else if(substr($val,0,9)=="password(") {
				$QV .= $val;
			} else {
				if($val==="**NULL**") $QV .= "NULL";
				else $QV .= "'".$this->mssql_escape($val)."'";
			}
		}
		$q = "INSERT INTO $table ($QN) VALUES ($QV)";

		return $q;
		// }}}
	}

	/*
	 * Exception wenn Query fehlerhaft
	 * pk zurückliefern
	 */
	function update($data, $table, $pk, $pk_field="") {
		// {{{

		$data = $this->matchArrayWithTable($data, $table);


		$keys = array();
		$vals = array();
		$qms = array();

		$Q = "UPDATE ".$table." SET ";

		foreach($data as $key => $val) {
			$keys[] = $key." = ? ";
			$vals[] = $val;
		}

		$Q .= implode(", ",$keys);
		$Q .= " WHERE ".addslashes($pk_field)." = ? ";

		#$vals[] = $pk_field;
		$vals[] = $pk;

		$GLOBALS["lastFWQuery"] = array("preparedQuery" => $Q, "vals" => $vals);

		#vd($vals);
		#vd($Q);exit;
		$sth = $this->link->prepare($Q);
		$sth->execute($vals);
		$this->lastQuery = $Q;

		if(isset($GLOBALS["protocolAllQueries"]) && $GLOBALS["protocolAllQueries"]==1) {
			file_put_contents(projectPath.'/cache/protocolQuery_'.date("Ym").'.log', date("d.m.Y H:i:s")."\t".$d."\t".$str."\n\n", FILE_APPEND);
		}
		if(defined('ENABLE_DB_SHORT_CACHE') && ENABLE_DB_SHORT_CACHE==true) {
			if (isset($GLOBALS["protocolAllQueries"]) && $GLOBALS["protocolAllQueries"] == 1) {
				file_put_contents(projectPath . '/cache/protocolQuery_' . date("Ym") . '.log', date("d.m.Y H:i:s") . "\t" . $d . "\t-----------------------------\n\n", FILE_APPEND);
			}
			$GLOBALS["fastFWShortCache"] = array();
		}

		return;


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
			if(in_array($key, $this->checkForArray($this->AESFields))) {
				$Q .= "AES_ENCRYPT('".addslashes($val)."', '".$this->AESkey."')";
			} else if(substr($val,0,9)=="password(") {
				$Q .= $val;
			} else {
					if($val==="**NULL**") $Q .= "NULL";
					else $Q .= "'".$this->mssql_escape($val)."'";
			}
		}
		$q = "UPDATE $table SET $Q  WHERE $pk_field='$pk' ";
		$this->lastQuery = $q;
		$this->query($q);
		// }}}
	}
	
	function checkForArray($X) {
		if(!is_Array($X)) return array();
		return $X;
	}

	function matchArrayWithTable($array, $table) {
		$fields = $this->getAllFields($table);
		$F = array();
		for($i=0;$i<count($fields);$i++) {
			$F[] = $fields[$i]["COLUMN_NAME"];
		}
		$fields = $F;
		$result = array();
		foreach($array as $key => $value) {
			if(in_array($key, $fields)) {
				$result[$key] = $value;
			}
		}
		return $result;
	}

	function lastID() {
		// {{{
        $lastId = $this->link->lastInsertId();
		return($lastId);
		// }}}
	}
	function DB_error($DB){
		if (!$DB->res){
			return die('Ungueltige Abfrage: ' );
		}
	}
	
	function getAllFields($table) {
		// {{{
		#$q = 'DESCRIBE '.$table;
		if(isset($this->Columns[$table])) {
			return $this->Columns[$table];
		}
		$q = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$table."' ";
		$f = $this->getAllByQuery($q);
		$this->Columns[$table] = $f;
		return($f);
		// }}}
	}
	function fieldExists($table, $field) {
		// {{{
		$f = $this->getAllFields($table);


		for($i=0;$i<count($f);$i++) {
			// {{{
			if($f[$i]['COLUMN_NAME']==$field) return(true);
			// }}}
		}
		return(false);
		// }}}
	}

	function getAllTables() {
		// {{{
		$q = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES";
		$f = $this->getAllByQuery($q);
		#vd($f);
		#$k = array_keys($f["TABLE_NAME"]);
		
		$T = array();
		for($i=0;$i<count($f);$i++) {
			$T[] = $f[$i]["TABLE_NAME"];
		}
		return($T);
		// }}}
	}
	function tableExists($table) {
		// {{{
		$T = $this->getAllTables();
		#vd($T);exit;
		for($i=0;$i<count($T);$i++) {
			// {{{
			#vd(array($T[$i] == $table, $T[$i] ,$table));
			if($T[$i] == $table) return(true);
			// }}}
		}
		return(false);
		// }}}
	}
	// }}}
}
// }}}

class DBDebug extends DBpdo {
	// {{{
	function query($str){
		$T0 = microtime(true);
		$this->res = mysql_query($str, $this->link);
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
