<?php
namespace classes;

class template {
	/**
	 * @var $fw \fastfw
	 */
	protected $fw;
	protected $VAR = array();
	protected $VARS;
	public $modulName;
    public $development = false;
	function __construct($params=array()) {
		// {{{
        if(isset($GLOBALS["FastFW"])) $this->fw = $GLOBALS["FastFW"];
		if($this->fw!="") $this->development = $this->fw->getDevelop();
		$this->VARS = new \classes\varArray(array());
		
		// }}}
	}
	
	function clearVariable() {
		// {{{
		$this->VAR = array();
		// }}}
	}


	function setVariable($name, $value="") {
		// {{{
		#if(!is_array($this->VAR)) $this->VAR = array();
		if(is_array($name)) {
			#$this->VAR = array_merge($this->VAR, $name);
			foreach($name as $Akey => $Avalue) {
				$this->VARS->set($Akey, $Avalue);
			}
		} else {
			$this->VARS->set($name, $value);
			#$this->VAR[$name] = $value;
		}
		// }}}
	}

	function get($tpl) {
		// {{{	Alias for tplGet
		return($this->tplGet($tpl));
		// }}}
	}

	function tplParse($tplCode) {
		$md5 = 'tmp.'.md5(microtime(true).rand()).'.php';
		$fn = projectPath.'/cache/'.$md5;
		file_put_contents($fn, $tplCode);
		$html = $this->tplGet($fn);
		unlink($fn);
		return $html;
	}

	function tplGet($tpl) {
		// {{{
		$VAR = $this->VAR;
		$VARS = $this->VARS;
		#vd($this->VARS->getData());
		if(method_Exists($this->VARS, "getData")) {
			$Vx = $this->VARS->getData();
			if(is_array($Vx)) extract($Vx);
		}
#vd($Vx);
		ob_start();

		if(isset($this->modulName) && $this->modulName!='') {
			$path = '/modul/'.$this->modulName;
			$modPath = $this->modulName;
		} else {
                    $path = '';
                    $modPath = '';
                }

		if((substr($tpl,0,1)=='/' || substr($tpl,1,1)==":") && file_exists($tpl)) {
			include($tpl);
		} else {


            if(file_exists(projectPath.$path.'/Templates/'.$tpl)) {
                include(projectPath.$path.'/Templates/'.$tpl);
            } else if(file_exists(projectPath.'/Templates/'.$tpl)) {
                include(projectPath.'/Templates/'.$tpl);
            } else if($modPath!='' && file_exists(libPath.'/modul/'.$modPath.'/Templates/'.$tpl)) {
                include(libPath.'/modul/'.$modPath.'/Templates/'.$tpl);
            } else if(file_exists(libPath.'/Templates/'.$tpl)) {
                include(libPath . '/Templates/' . $tpl);
            }


            else if(file_exists(projectPath.$path.'/templates/'.$tpl)) {
				include(projectPath.$path.'/templates/'.$tpl);
			} else if(file_exists(projectPath.'/templates/'.$tpl)) {
				include(projectPath.'/templates/'.$tpl);
			} else if($modPath!='' && file_exists(libPath.'/modul/'.$modPath.'/templates/'.$tpl)) {
				include(libPath.'/modul/'.$modPath.'/templates/'.$tpl);
			} else if(file_exists(libPath.'/templates/'.$tpl)) {
				include(libPath.'/templates/'.$tpl);
			} else {
				$this->fw->error('Template '.$tpl.' not found<br>');
			}
		}

		$html = ob_get_clean();
		return($html);
		// }}}
	}

	function tplFileExists($tpl) {

		if(isset($this->modulName) && $this->modulName!='') {
			$path = '/modul/'.$this->modulName;
			$modPath = $this->modulName;
		} else {
			$path = '';
			$modPath = '';
		}

		if((substr($tpl,0,1)=='/' || substr($tpl,1,1)==":") && file_exists($tpl)) {
			return $tpl;
		} else {
			if(file_exists(projectPath.$path.'/Templates/'.$tpl)) {
				return projectPath.$path.'/Templates/'.$tpl;
			} else if(file_exists(projectPath.'/Templates/'.$tpl)) {
				return projectPath.'/Templates/'.$tpl;
			} else if($modPath!='' && file_exists(libPath.'/modul/'.$modPath.'/Templates/'.$tpl)) {
				return libPath.'/modul/'.$modPath.'/Templates/'.$tpl;
			} else if(file_exists(libPath.'/Templates/'.$tpl)) {
				return libPath . '/Templates/' . $tpl;
			} else if(file_exists(projectPath.$path.'/templates/'.$tpl)) {
				return projectPath.$path.'/templates/'.$tpl;
			} else if(file_exists(projectPath.'/templates/'.$tpl)) {
				return projectPath.'/templates/'.$tpl;
			} else if($modPath!='' && file_exists(libPath.'/modul/'.$modPath.'/templates/'.$tpl)) {
				return libPath.'/modul/'.$modPath.'/templates/'.$tpl;
			} else if(file_exists(libPath.'/templates/'.$tpl)) {
				return libPath.'/templates/'.$tpl;
			}
		}
		return false;
	}

	function includeTpl($tplName, $data=array(), $modul=NULL) {
		if($modul==NULL) $modul = $this->modulName;
		$tpl = new \classes\template($this->fw);
		$tpl->modulName = $modul;
		foreach($data as $key => $value) {
			$tpl->setVariable($key, $value);
		}
		return $tpl->get($tplName);
	}
}	
?>