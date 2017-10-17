<?php

namespace classes;

class NullObj extends \classes\AbstractModel {
	public function __construct() {

	}
	public function __call($name, $arguments) {
		return new \classes\NullObj();
	}
	public function __toString() {
		return "";
	}
}
?>