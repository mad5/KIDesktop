<?php

class BereicheController extends \classes\AbstractCrudController {

	public function __construct() {
		
		if(!loggedIn()) {jump2page("Backenduser/login");exit;}
		
		parent::__construct();
		#new fe_user(array('checklogin' => true)); // array('requirelogin' => true)
		
		
		$this->initCrud("Bereiche", "Bereich", "be");
		$this->addListColumn("be_icon", "Icon");
		$this->addListColumn("be_name", "Name");
		$this->addListColumn("be_reihenfolge", "Reihenfolge");
		$this->addListColumn("be_freigegeben", "Freigegeben");
		$this->setInitialSortBy("be_reihenfolge");
		$this->setSortableColumns(array("be_name", "be_icon", "be_reihenfolge", "be_freigegeben"));
		$this->templates = array(
			"head"          => "Bereiche/tpl.Head.php",
			"list"          => "Bereiche/tpl.Index.php",
			"form"          => "Bereiche/tpl.Form.php",
			"deleteConfirm" => "Bereiche/tpl.DeleteConfirm.php",
			"copyConfirm"   => "Bereiche/tpl.CopyConfirm.php",
		);
		
		$this->hideCrudListActionView = true;
		
		$this->repository->addWhere(" be_bu_fk='".me()->getPk()."' ");
		$this->repository->addEntryWhere(" be_bu_fk='".me()->getPk()."' ");
	}

	public function index2Action() {
		$tpl = $this->newTpl();

		$this->fw->setVariable('CONTENT', $tpl->get('Bereiche/tpl.Index.php'));
	}

	protected function preInsert($data) {
		$data["be_freigegeben"] = (int)$data["be_freigegeben"];
		$data["be_bu_fk"] = me()->getPk();
		return $data;
	}

	protected function preUpdate($pk, $data) {
		$data["be_freigegeben"] = (int)$data["be_freigegeben"];
		$data["be_bu_fk"] = me()->getPk();
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