<?php

namespace classes;

/**
 */
class fwResponse {
	protected $html="";

	public function setHtml($html) {
		$this->html = $html;
	}
	public function getHtml($html) {
		return $this->html;
	}

	public function send() {
		echo $this->html;
	}

	public function __toString() {
		return $this->html;
	}
}
?>