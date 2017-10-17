<?php

namespace classes;

class routeAjaxResult {
	private $html;
	private $id;
	public function __construct($html) {
		$this->html = $html;
	}
	public function setId($id) {
		$this->id = $id;
	}
	public function getId($id) {
		return $this->id;
	}
	public function getCallFuncName() {
		return "callAjaxRoute_".$this->id;
	}
	public function __call($name, $arguments) {
		#return new \classes\NullObj();
	}
	public function __toString() {
		return $this->html;
	}
}
?>