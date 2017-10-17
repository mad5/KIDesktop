<?php

namespace classes;

/**
 */
class fwResponsePlain {
	protected $plain="";

	public function setPlain($plain) {
		$this->plain = $plain;
	}
	public function getPlain() {
		return $this->plain;
	}

	public function setJson($arrayOrObject) {
		$this->plain = json_encode($arrayOrObject);
	}

	public function send() {
		echo $this->plain;
	}

	public function __toString() {
		return $this->plain;
	}
}
?>