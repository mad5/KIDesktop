<?php
namespace fwdevelop\Service;

class fwdevelopService extends \classes\AbstractService {

	static public function checkFolders() {
		self::testFolder("uploads");
		self::testFolder("uploads/cache");
		self::testFolder("cache");
		self::testFolder("log");
		self::testFolder("resources/images");
		self::testFolder("resources/js");
		self::testFolder("resources/css");
	}

	static public function testFolder($folder) {
		$D = projectPath.'/'.$folder;
		if(!file_exists($D)) {
			mkdir($D, 0775, true);
			chmod($D, 0775);
		}
	}

	static public function getWelcome() {
		$html = "";

		$html .= "<h1>Willkommen im fastfw-Framework</h1>";

		$html .= "fwDevelop-Console öffnen: <a href='".getLink("fwdevelop/console")."' class='btn btn-success' target='fwconsole' onclick=\"window.open('','fwconsole','width=300,height=800,resizable=yes,scrollbars=yes');return true;\">&ouml;ffnen</a>";

		return $html;
	}

}

?>