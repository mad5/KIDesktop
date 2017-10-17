<?php

class fastfw {
	// {{{
	protected $processorList = array();
	public $QS = array();
	public $fw;
	public $startTime;
	public $DC;
	public $VAR;
	protected $develop = false;
	protected $verifyHTML = '';

	public $clientProxy = '';

	protected $scriptLines = '';

	public $redirectClassName = array();
	protected $preventStepModuls = array();
	protected $preventStepSave = array();

	protected $bindList = array();

	protected $errorOutFile = "";

	protected $extPath = "";

	public $redirectAfterRun = "";
	public $response = null;

	public function __construct() {
		// {{{
		$this->initFW();

		$this->startTime = microtime(true);

		if(defined("DB_TYPE") && DB_TYPE=="sqlite") {
			if (!file_exists(dirname(DB_FILE))) {
				mkdir(dirname(DB_FILE), 0775);
				chmod(dirname(DB_FILE), 0775);
			}
			$this->DC = new \classes\DBpdosqlite(DB_FILE);
		} else {

			if (defined("DB_NAME") && DB_NAME != "") {

				if (defined("DB_TYPE") && DB_TYPE == "mssql") {
					if (defined('DB_NAME')) {
						$this->DC = new \classes\DBpdo(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_CHARACTERSET);
					} else {
						$this->DC = new \classes\DBnotAvailable();
					}
				} else {
					if (defined("DB_TYPE") && DB_TYPE == "mysqli") {
						if (defined('DB_NAME')) {
							$this->DC = new \classes\DBps(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_CHARACTERSET);
						} else {
							$this->DC = new \classes\DBnotAvailable();
						}
					} else {
						if (defined('DB_NAME')) {
							$this->DC = new \classes\DB(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_CHARACTERSET);
						} else {
							$this->DC = new \classes\DBnotAvailable();
						}
					}
				}


				if ($this->DC->connected != TRUE) {
					$this->errorOutput();
				}
			}
		}
		
		
		$this->fw = $this;
		
		$this->VAR = array();
		
		$this->REQUESTVARS['GET'] = $_GET;
		$this->REQUESTVARS['POST'] = $_POST;
		$this->REQUESTVARS['COOKIE'] = $_COOKIE;
		$this->REQUESTVARS['REQUEST'] = $_REQUEST;

		$GLOBALS["FastFW"] = $this;
		
		$GLOBALS['helperFastFW'] = $this;
		// }}}
	}

	public function setExtensionPath($extPath) {
		$this->$extPath = $extPath;
	}

	private function initFW() {
		// {{{
            
		
		$this->checkNecessary();
		$secureSession = false;
		if(defined('secureSession') && secureSession==true) $secureSession = true;
		
		if(!defined("noSession")) {
			#var_dump(dirname($_SERVER['PHP_SELF']));exit;
			$path = dirname($_SERVER['PHP_SELF']);
			if($path=="" || $path=="\\") $path = "/";
			session_set_cookie_params(0, $path, null, $secureSession, true);
			session_start();
		}
		define('libPath', dirname(__FILE__));

		if($_GET["resetSession"]) {
			session_destroy();
			exit;
		}

		if(!isset($_SESSION['fastfw_until'])) $_SESSION['fastfw_until'] = array();
		if(!isset($_SESSION['fastfw'])) $_SESSION['fastfw'] = array();

		include_once(libPath.'/includes/inc.debug.php');
		include_once(libPath.'/includes/inc.helper.php');
		include_once(libPath.'/includes/inc.translation.php');

		if(file_exists(projectPath.'/inc.var.php')) include_once(projectPath.'/inc.var.php');

        if(defined("DB_TYPE") && DB_TYPE=="mssql") {
			include_once(libPath . '/classes/class.dbpdo.php');
		} else if(defined("DB_TYPE") && DB_TYPE=="sqlite") {
            include_once(libPath . '/classes/class.dbpdosqlite.php');
        } else if(defined("DB_TYPE") && DB_TYPE=="mysqli") {
			include_once(libPath.'/classes/class.dbps.php');
		} else {
			include_once(libPath.'/classes/class.db.php');
		}
		include_once(libPath.'/classes/class.fastfw_modul.php');
        include_once(libPath.'/classes/class.fastfwController.php');

		

		if(!defined('FE_USER_SECRET')) define('FE_USER_SECRET', '');
		if(!defined('defaultLanguage')) define('defaultLanguage', 'de');
		if(!defined('FILE_UPLOAD_FOLDER')) define('FILE_UPLOAD_FOLDER', projectPath.'/uploads');

		$this->QS = $this->routing($_SERVER['QUERY_STRING'], $_GET);
		// }}}
	}

	public function setErrorOutFile($file) {
		$this->errorOutFile = $file;
	}
	
	public function errorOutput($exit=true) {
		
		if($this->errorOutFile!='' && file_exists($this->errorOutFile)) {
			readfile($this->errorOutFile);
		} else {
			echo "<div style='border: solid 1px red;padding:10px;'>Es ist ein Fehler aufgetreten.</div>";
			vd(getTrace());
		}
		
		if($exit) exit();
	}
	
	function checkNecessary() {
		// {{{
		if(!defined('projectPath')) die('set projectPath!');
		if(!file_exists(projectPath.'/cache')) {
			@mkdir(projectPath.'/cache', 0775);
			@chmod(projectPath.'/cache', 0775);
			if(!file_exists(projectPath.'/cache')) {
				die("create cache-folder with write-privileges ");
			}
		}
		// }}}
	}

	function REQUEST($name, $default='') {
		if(stristr($name,'/')) {
			$one = str_bis($name,'/');
			$two = str_nach($name,'/');
			return $this->REQUEST($one, $default)->REQUEST($two, $default);
		} else {
			$res = $default;
			if(isset($this->REQUESTVARS['REQUEST'][$name])) $res = $this->REQUESTVARS['REQUEST'][$name];
			return $res;
		}
	}

	function unsetREQUEST($name) {
	    unset($this->REQUESTVARS['REQUEST'][$name]);
	}

	function setDevelop($dev, $tidy='') {
		$this->develop = $dev;
		$this->verifyHTML = $tidy;
		if(isset($this->DC)) $this->DC->develop = $this->develop;
	}
	function getDevelop() {
		return $this->develop;
	}


	public function inPath($path) {
		$P = explode('/', $path);
		for($i=0;$i<count($P);$i++) {
			if(!isset($this->QS[$i]) || $this->QS[$i]!=$P[$i]) return false;
		}
		return true;
	}
	
	function getDuration() {
		// {{{
		$d = number_format(microtime(true) - $this->startTime,10,",",".");
		return($d);
		// }}}
	}
	function run() {
		// {{{
		if($this->redirectAfterRun!="") {
			$response = new \classes\fwResponseRedirect();
			$response->setRedirectRoute($this->redirectAfterRun);
			$this->response = null;
			$this->redirectAfterRun="";
			return $response;
		}
		if($this->response!=null) {
			$response = $this->response;
			$this->response = null;
			$this->redirectAfterRun="";
			return $this->response;
		}
		$this->processMVC();

		if($this->response!=null) {
			if($this->response instanceof \classes\fwResponsePlain) {

				return $this->response;
			}
		}

		if($this->redirectAfterRun!="") {
			$response = new \classes\fwResponseRedirect();
			$response->setRedirectRoute($this->redirectAfterRun);

		} else {

			$output = $this->sendHeaders();

			$output .= $this->processHTML($this->sendMain());

			if ($this->getDevelop() && $this->verifyHTML == 'tidy') {
				file_put_contents(projectPath . '/cache/tidy.html', $output);

				$E = 'tidy -e -utf8 -f ' . projectPath . '/cache/tidy.out ' . projectPath . '/cache/tidy.html';
				$A = exec($E, $B);

				$f = file_get_contents(projectPath . '/cache/tidy.out');
				if (!stristr($f, 'No warnings or errors were found.')) {
					$output = str_replace('</body>', '<div style="border:solid 3px red;margin:5px;padding:10px;"><pre>' . htmlspecialchars(trim(substr($f, 0, strpos($f, 'To learn more')))) . '</pre></div></body>', $output);
				}
			}

			if (1 == 2 && $_SERVER["HTTP_HOST"] == "develop1") {

				$found = FALSE;
				$C = glob(dirname(__FILE__) . '/../projectchat/data/checked_*');
				for ($i = 0; $i < count($C); $i++) {
					if (filemtime($C[$i]) > time() - 30) {
						$userdata = json_decode(file_get_contents($C[$i]), TRUE);
						if ($userdata["ip"] == $_SERVER["REMOTE_ADDR"]) {
							$found = TRUE;
							break;
						}
					}
				}
				if ($found == FALSE) {
					$output = str_replace('dashboard&">CertBay</a>', 'dashboard&">CertBay</a><a href="/projectchat/" target="projectchat" onclick="window.open(\'\', \'projectchat\', \'width=600,height=800,resizable=yes,scrollbars=yes\');jQuery(this).remove();" class="navbar-brand topnavitem">ProjectChat öffnen! <img src="http://www.freesmileys.org/smileys/smiley-dance013.gif"></a>', $output);
				}
			}

			$response = new \classes\fwResponse();
			$response->setHtml($output);
		}

		$this->response = null;
		$this->redirectAfterRun="";

		return $response;
		// }}}
	}
	
	public function processHTML($html) {
		// {{{
		for($i=0;$i<count($this->processorList);$i++) {
			// {{{
			$html = $this->processorList[$i]->processHTML($html);
			// }}}
		}
		return($html);
		// }}}
	}
	
	public function addHTMLProcessor($obj) {
		// {{{
		$this->processorList[] = $obj;
		// }}}
	}

	public function routing($QS, $GET) {
		$MM = array('Index', 'Index');
		if(isset($GET['fw_goto']) && $GET['fw_goto']!='') {
			if(stristr($GET['fw_goto'], '/')) $MM = explode('/', $GET['fw_goto']);
			else $MM = array($GET['fw_goto'],'Index');
			if($MM[0]=='') $MM[0] = 'Index';
			if($MM[1]=='') $MM[1] = 'Index';
		}
		return $MM;
	}

    public function route($pfad, $params=array()) {
        $MM = explode('/', $pfad);
        if($MM[0]=='') $MM[0] = 'Index';
        if($MM[1]=='') $MM[1] = 'Index';
        return $this->runMVC($MM, $params);
    }

	public function routeAjax($pfad, $params=array()) {
		$html = "";
		$id = "fwra_".str_replace(".","",microtime(true));
		$html .= "<span id='".$id."' class='spanFWAjaxContent'>";

		$html .= "</span>";


		$params["fwAjaxOutput"] = "direct";
		$data = json_encode($params);

		$html .= "<script>";
		$html .= 'function callAjaxRoute_'.$id.'() {';
		$html .= '$.ajax({';
			$html .= '"url": "'.getLink($pfad).'",';
			$html .= '"type": "post",';
			//$html .= '"data": {"fwAjaxOutput":"direct"},';
			$html .= '"data": '.$data.',';
			$html .= '"dataType": "html",';
			$html .= '"timeout": 5000,';
			$html .= '"success": function(html) {';
			$html .= '$("#'.$id.'").html(html);';
			$html .= '}';
		$html .= '});';
		$html .= '}';
		$html .= '$(function() {';
		$html .= 'setTimeout(function() { ';
		$html .= 'callAjaxRoute_'.$id.'();';
		$html .= ' }, Math.round(Math.random()*50) );';
		$html .= '});';
		$html .= "</script>";

		$res = new \classes\routeAjaxResult($html);
		$res->setId($id);
		return $res;
	}

	public function setModulMissingPage($page) {
		$this->modulMissingPage = $page;
	}

	public function processMVC() {
		// {{{

        $res = $this->runMVC($this->QS);

        if($res===false) {
                if($this->develop) {
                    echo "<form method=post action='".getLink('fwdevelop.createmodul')."'>";
                    echo "<b>Dieses Modul ist zur Zeit nicht vorhanden!</b><br/>";
                    echo "Develop-Console &ouml;ffnen: <a href='".getLink("fwdevelop/console")."' target='fwconsole' onclick=\"window.open('','fwconsole','width=300,height=800,resizable=yes,scrollbars=yes');return true;\">FW-Console &ouml;ffnen</a>";
                    /*
                    echo "Wollen Sie es nun erzeugen?<br/><br/>";
                    echo "Modul: <input type='text' name='modulName' value='".$GLOBALS['fw_modul']."'><br/>";
                    echo "Methoden: <textarea name='methods' rows=5 cols=40>Index</textarea><br/>";
                    echo "<br/><input type='submit' value='Modul anlegen'>";
                    */
                    echo "</form>";
                }
				if($this->modulMissingPage!="") {
					jump2page($this->modulMissingPage);
					exit;
				}
                $this->error('Modul &raquo;'.$GLOBALS['fw_modul'].'&laquo; nicht vorhanden');
        }

		// }}}
	}

    private function runMVC($QS, $params=array()) {

        $modul = $this->checkValidFilename(array_shift($QS));
        $modul = explode('.', $modul);
        $modul = $modul[count($modul)-1];
        $controller = $modul;
        if(stristr($modul,'-')) {
            $M = explode("-", $modul);
            $modul = $M[0];
            $controller = $M[1];
        }
        if($modul=='') $modul = 'Index';

        if(file_exists(projectPath.'/modul/'.ucfirst($modul))) $modul = ucfirst($modul);
        else if(file_exists(libPath.'/modul/'.ucfirst($modul))) $modul = ucfirst($modul);
        else if(file_exists(libPath.'/modul/'.$modul)) $modul = $modul;
        else return false;

        $GLOBALS['fw_modul'] = $modul;
        $GLOBALS['fw_method'] = $this->QS[1];
        $this->routedLocation = $modul.'/'.$GLOBALS['fw_method'];
        for($i=2;$i<10;$i++) {
            if(!isset($QS[$i])) break;
            $this->routedLocation .= "/".$QS[$i];
        }

        $fnNew = projectPath.'/modul/'.ucfirst($modul).'/'.ucfirst($controller).'Controller.php';
        $fnNewLib = libPath.'/modul/'.ucfirst($modul).'/'.ucfirst($controller).'Controller.php';
        $fn = projectPath.'/modul/'.$modul.'/class.'.$controller.'.php';
#error_reporting(-1);
        $found = false;
        if(file_exists($fnNew)) {
            $modulPath = projectPath . '/modul/' . ucfirst($GLOBALS['fw_modul']);
            include_once($fnNew);
            $found = true;
        } else if(file_exists($fnNewLib)) {
            $modulPath = libPath . '/modul/' . ucfirst($GLOBALS['fw_modul']);
            include_once($fnNewLib);
            $found = true;
        } else if(file_exists($fn)) {
            // Das Modul war im Projektordner vorhanden und wird eingebunden
            $modulPath = projectPath.'/modul/'.$GLOBALS['fw_modul'];
            include_once($fn);
            $found = true;
        } else {
            $fn = libPath.'/modul/'.$GLOBALS['fw_modul'].'/class.'.$controller.'.php';
            if(file_exists($fn)) {
                // Moduldatei ist im LibOrdner vorhanden und wird von dort eingebunden
                $modulPath = libPath.'/modul/'.$GLOBALS['fw_modul'];
                include_once($fn);
                $found = true;
            }
        }

        if($found) {
            // Moduldatei ist eingebunden worden.
            if (class_exists('fastfw_' . $controller)) {
                $name = 'fastfw_' . $controller;
            } else {
                $name = $controller.'Controller';
            }
            $M = new $name();
            $M->fw = $this;
            $M->modulName = $modul;


            $fn2 = projectPath.'/modul/'.$GLOBALS['fw_modul'].'/classes/class.'.$modul.'.php';
            if(file_exists($fn2)) {
                // Eine passende
                $M->$modul = $this->fw_useSingleClass($modul.'/'.$modul);
                $M->$modul->modulName = $modul;
            } else {
                $fn2 = libPath.'/modul/'.$GLOBALS['fw_modul'].'/classes/class.'.$modul.'.php';
                if(file_exists($fn2)) {
                    // Eine passende
                    $M->$modul = $this->fw_useSingleClass($modul.'/'.$modul);
                    $M->$modul->modulName = $modul;
                }
            }

            $M->DC = $this->DC;

            return $M->processMVC($QS, $params);
        } else return false;

    }


	public function sendHeaders() {
		// {{{
		if (!headers_sent()) {
			header('Content-type:text/html;charset=utf-8');
		}
		// }}}
	}

        public function addScript($js) {
            $this->scriptLines .= $js."\n";
        }
        
	public function sendMain() {
		// {{{

		$tpl = new \classes\template();
		$tpl->setVariable($this->VAR);
		$html = $tpl->get(tpl_main);
		return($html);
		// }}}
	}
        
        public function setContentBody($name, $value='') {
            $this->setVariable($name, $value);
        }

	public function setVariable($name, $value='') {
		// {{{
		if(!isset($this->VAR) || !is_array($this->VAR)) $this->VAR = array();
		if(is_array($name)) {
			$this->VAR = array_merge($this->VAR, $name);
		} else {
			$this->VAR[$name] = $value;
		}
		// }}}
	}
	
	
	public function checkValidFilename($fn) {
		// {{{
		if(stristr($fn,'/') || stristr($fn,'..') || stristr($fn,"\n") || stristr($fn,"\r")) $this->error('falsches Modul');
		return($fn);
		// }}}
	}
	public function error($err) {
		// {{{
		echo $err;
		exit;
		
		// }}}
	}
	
	
	public function fw_useClassByKey($class, $key, $params=array()) {
		// {{{
		if(isset($this->redirectClassName[$class])) $class = $this->redirectClassName[$class];
		$classKey = str_replace('/', '__', $class);
		if(isset($this->classByKey[$classKey][$key])) return($this->classByKey[$classKey][$key]);
		else {
			$this->classByKey[$classKey][$key] = $this->fw_useClass($class, $params, false);
			$this->classByKey[$classKey][$key]->id = $key;
			return($this->classByKey[$classKey][$key]);
		}
		// }}}
	}
	public function fw_useSingleClass($class, $params=array()) {
		// {{{
		if(isset($this->redirectClassName[$class])) $class = $this->redirectClassName[$class];
		$classKey = str_replace('/', '__', $class);
		if(isset($this->$classKey)) return($this->$classKey);
		else $this->$classKey = $this->fw_useClass($class, $params);
		return $this->$classKey;
		// }}}
	}
	
	public function fw_useClass($class, $params=array(), $registerFW=true) {
		// {{{
		if(isset($this->redirectClassName[$class])) $class = $this->redirectClassName[$class];
		if(stristr($class,'/')) {
			$i = strpos($class, '/');
			$path = '/'.substr($class,0,$i);
			$class = substr($class,$i+1);
		} else $path = '';

		$classFile = $class;
		$classKey = str_replace('/', '__', $class);

		if(file_exists(projectPath.'/modul'.$path.'/classes/class.'.$classFile.'.php')) {
			include_once(projectPath.'/modul'.$path.'/classes/class.'.$classFile.'.php');
		} else if(file_exists(projectPath.$path.'/classes/class.'.$classFile.'.php')) {
			include_once(projectPath.$path.'/classes/class.'.$classFile.'.php');
		} else if(file_exists(libPath.'/modul/'.$class.'/classes/class.'.$classFile.'.php')) {
			include_once(libPath.'/modul/'.$class.'/classes/class.'.$classFile.'.php');
		} else if(file_exists(libPath.'/classes/class.'.$classFile.'.php')) {
			include_once(libPath.'/classes/class.'.$classFile.'.php');
		} else {
			$this->error('Class &gt;'.$class.'&lt; not found');
		}
#vd(getTrace());
		$class2 = $class."\\".$class;
		$C = new $class2($params);

		if($registerFW) $this->$class = $C;
		$this->$classKey = $C;
		return($C);
		// }}}
	}
        
        public function newClass($class) {
            return $this->fw_useClass($class);
        }
        

        public function setClientProxy($proxy) {
            $this->clientProxy = $proxy;
        }
        
        public function bind($event, $func) {
            if(!isset($this->bindList[$event])) $this->bindList[$event] = array();
            $this->bindList[$event][] = $func;
        }
        
        public function callBinds($event, $A = array()) {
            #vd($this->bindList);exit;
            if(!isset($this->bindList[$event])) return false;
            for($i=0;$i<count($this->bindList[$event]);$i++) {
                $res = call_user_func_array($this->bindList[$event][$i], $A);
                
            }
        }
        
        public function protokollRequestVars($q1, $q2) {
        	if(defined("protokollRequestVars") && protokollRequestVars==true) {
        		if(file_exists(projectPath.'/log')) {
        			$fn = projectPath.'/log/prv_'.$q1."_".$q2.".json";
        			if(file_exists($fn)) {
        				$data = json_decode(file_get_contents($fn), true);
        			}
        			$data = array();
        			if(isset($_REQUEST)) {
        				foreach($_REQUEST as $key => $val) {
        					if($key=="fw_goto") continue;
        					if(!isset($data[$key])) $data[$key] = 1;
        					else $data[$key]++;
        				}
        			}
        			if($data!=array()) file_put_contents($fn, json_encode($data));
        		}
        	}
        }
        
	// }}}
}
?>