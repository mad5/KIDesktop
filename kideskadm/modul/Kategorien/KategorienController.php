<?php

class KategorienController extends \classes\AbstractCrudController {

	public function __construct() {
		
		if(!loggedIn()) {jump2page("Backenduser/login");exit;}
		
		parent::__construct();
		#new fe_user(array('checklogin' => true)); // array('requirelogin' => true)

		$this->initCrud("Kategorien", "Kategorie", "ka");
		$this->addListColumn("ka_name", "Name");
		$this->addListColumn("ka_bereich", "Bereich");
		$this->addListColumn("ka_rechner", "Rechner");
		$this->setSortableColumns(array("ka_name", "ka_bereich", "ka_rechner"));
		$this->templates = array(
			"head"          => "Kategorien/tpl.Head.php",
			"list"          => "Kategorien/tpl.Index.php",
			"form"          => "Kategorien/tpl.Form.php",
			"deleteConfirm" => "Kategorien/tpl.DeleteConfirm.php",
			"copyConfirm"   => "Kategorien/tpl.CopyConfirm.php",
		);
	}

	public function index2Action() {
		$tpl = $this->newTpl();

		$this->fw->setVariable('CONTENT', $tpl->get('Kategorien/tpl.Index.php'));
	}

	protected function prepareRelData($data) {
		if (is_Array($data["ka_rechner"])) {
			$data["ka_rechner"] = implode(",", $data["ka_rechner"]);
		} else {
			$data["ka_rechner"] = "";
		}

		return $data;
	}

	protected function preInsert($data) {
		$data = $this->prepareRelData($data);
    	    $data["ka_bu_fk"] = me()->getPk();
	return $data;
}
protected function preUpdate($pk, $data) {
	$data = $this->prepareRelData($data);
	$data["ka_bu_fk"] = me()->getPk();
	return $data;
}

	function newAction() {
		parent::newAction();
	}

	function editAction(array $queryArray) {
		parent::editAction($queryArray);
	}

} // fastfwController
?>