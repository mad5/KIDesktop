<?php

namespace classes;

/**
 */
class fwResponseRedirect {
	protected $html="";
	protected $route="";

	public function setRedirectRoute($route) {
		$this->route = $route;
	}

	public function send() {
		$L = getLink($this->route);
		if(defined("baseHref") && baseHref!="") {
			header('location: ' . baseHref.$L);
		} else {
			header('location: ' . $L);
		}
	}

	public function __toString() {
		$this->send();
		return "";
	}
}
?>