<?php
namespace classes;

abstract class fastfw_modul {
        
	/**
	*
	* @var fastfw
	*/
    public $fw;
    protected $VAR;

    public function __construct() {
		// {{{
        $this->fw = $GLOBALS["FastFW"];
		// }}}
	}
	function REQUEST($name) {
		return $this->fw->REQUEST($name);
	}
	public function processMVC($QS, $params=array()) {
		// {{{
		$func = array_shift($QS);
		if($func=='') $func = 'Index';
		if(isset($_REQUEST['fw_ajax']) && $_REQUEST['fw_ajax']==1) $func2 = 'ajax_'.$func; else $func2 = 'view_'.$func;
		if(method_exists($this, $func2)) {
		} else {
            $func2 = $func.'Action';
        }

        if(method_exists($this, $func2)) {
            $this->fw->protokollRequestVars($this->fw->QS[0], $this->fw->QS[1]);
            if($params!=array()) {
                $res = $this->$func2($QS, $params);
            } else {
                $res = $this->$func2($QS);
            }
            if(isset($_REQUEST['fw_ajax']) && $_REQUEST['fw_ajax']==1) exit;
			if(isset($_REQUEST["fwAjaxOutput"]) && $_REQUEST["fwAjaxOutput"]=="direct") {echo $res;exit;}
            return $res;
        } else {
            $this->fw->error('methode &raquo;' . $func2 . '&laquo; existiert nicht');
        }


        return false;
		// }}}
	}
	
	public function index($QS) {
		// {{{
		#echo "<p>X</p>";
		// }}}
	}
	
	public function setVariable($area, $name, $value="") {
		// {{{
		if(!is_array($this->VAR[$area])) $this->VAR[$area] = array();
		if(is_array($name)) {
			$this->VAR[$area] = array_merge($this->VAR[$area], $name);
		} else {
			$this->VAR[$area][$name] = $value;
		}
		// }}}
	}

	
	public function tplGet($area, $tpl) {
		// {{{
		$VAR = $this->VAR[$area];
		ob_start();
		
		if(file_exists(projectPath.'/templates/'.$tpl)) include_once(projectPath.'/templates/'.$tpl);
		else if(file_exists(libPath.'/templates/'.$tpl)) include_once(libPath.'/templates/'.$tpl);
		else $this->fw->error('Template '.$tpl.' not found');
		
		$html = ob_get_clean();
		return($html);
		// }}}
	}
	public function get($area, $tpl) {
		return($this->tplGet($area, $tpl));
	}

	protected function newTpl($modulName='') {
		#vd($this->modulName);
		//$tpl = $this->fw->fw_useClass('template');
		$tpl = new \classes\template();
		if($modulName=='') $tpl->modulName = $this->modulName;
		else $tpl->modulName = $modulName;
		return $tpl;
	}

	protected function setS($name, $value) {
		setS(md5($this->fw->QS[0]).'_'.$name, $value);
	}
	protected function getS($name) {
		return getS(md5($this->fw->QS[0]).'_'.$name);
	}
}
?>