<?php
namespace Index;
class IndexModel {
	public $fw;
	public $id;
	public $params = array();
	public function __construct($params=array()) {
		// {{{
		$this->params = $params;
        $this->fw = $GLOBALS["FastFW"];
		// }}}
	}
	
}
?>