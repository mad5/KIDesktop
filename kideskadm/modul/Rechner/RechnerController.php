<?php
class RechnerController extends \classes\AbstractCrudController {

    public function __construct() {
    	    
    	    
    	    if(!loggedIn()) {jump2page("Backenduser/login");exit;}
    	    
        parent::__construct();
        #new fe_user(array('checklogin' => true)); // array('requirelogin' => true)

        $this->initCrud("Rechner", "Rechner", "re");
        $this->addListColumn("re_bild", "Bild");
		$this->addListColumn("re_kind", "Kind");
		$this->addListColumn("re_ort", "Ort");
		$this->addListColumn("re_beschreibung", "Beschreibung");
		//$this->addListColumn("re_letzteip", "Letzteip");
		$this->addListColumn("re_zuletztonline", "Zuletztonline");
		/*
		$this->addListColumn("re_offlineab", "Offlineab");
		$this->addListColumn("re_offlinebis", "Offlinebis");
		$this->addListColumn("re_ausab", "Ausab");
		$this->addListColumn("re_ausbis", "Ausbis");
		$this->addListColumn("re_nutzungsdauerinsgesamt", "Nutzungsdauerinsgesamt");
		*/
		$this->addListColumn("re_hash", "Rechner-Kennung");
		$this->setSortableColumns(array("re_kind","re_ort","re_beschreibung","re_letzteip","re_zuletztonline","re_offlineab","re_offlinebis","re_ausab","re_ausbis","re_nutzungsdauerinsgesamt"));
		$this->templates = array(
		    "head"          => "Rechner/tpl.Head.php",
		    "list"          => "Rechner/tpl.Index.php",
		    "form"          => "Rechner/tpl.Form.php",
		    "deleteConfirm" => "Rechner/tpl.DeleteConfirm.php",
		    "copyConfirm"   => "Rechner/tpl.CopyConfirm.php",
		);

		$this->repository->addWhere(" re_bu_fk='".me()->getPk()."' ");
		$this->repository->addEntryWhere(" re_bu_fk='".me()->getPk()."' ");
		
		$this->hideCrudListActionView = true;

    }

    
    public function index2Action() {
        $tpl = $this->newTpl();



        $this->fw->setVariable('CONTENT', $tpl->get('Rechner/tpl.Index.php'));
    }

    protected function preInsert($data) {
    	    $data["re_bu_fk"] = me()->getPk();
    	    $data["re_hash"] = createCode(10);
	return $data;
}
protected function preUpdate($pk, $data) {
	$data["re_bu_fk"] = me()->getPk();
	
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