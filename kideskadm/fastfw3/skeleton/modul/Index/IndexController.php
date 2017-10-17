<?php
class IndexController extends \classes\AbstractController {

	public function __construct() {
		parent::__construct();
	}

	public function indexAction($QS) {
		$tpl = $this->newTpl();
		$tpl->setVariable('test', date('H:i:s'));
	
		\fwdevelop\Service\fwdevelopService::checkFolders();
	
		$this->fw->setVariable('CONTENT', $tpl->get('tpl.Index.php'));
	}
	
} // fastfwController
?>