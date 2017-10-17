<?php
class NachrichtenController extends \classes\AbstractCrudController {

    public function __construct() {
        parent::__construct();
        #new fe_user(array('checklogin' => true)); // array('requirelogin' => true)

        $this->initCrud("Nachrichten", "Nachricht", "na");
		$this->addListColumn("na_sender", "Sender");
		$this->addListColumn("na_mailkontakt", "Mailkontakt");
		$this->addListColumn("na_rechner", "Rechner");
		$this->addListColumn("na_nachricht", "Nachricht");
		$this->addListColumn("na_uebertragen", "Uebertragen");
		$this->addListColumn("na_gelesen", "Gelesen");
		$this->setSortableColumns(array("na_sender","na_mailkontakt","na_rechner","na_nachricht","na_uebertragen","na_gelesen"));
		$this->templates = array(
		    "head"          => "Nachrichten/tpl.Head.php",
		    "list"          => "Nachrichten/tpl.Index.php",
		    "form"          => "Nachrichten/tpl.Form.php",
		    "deleteConfirm" => "Nachrichten/tpl.DeleteConfirm.php",
		    "copyConfirm"   => "Nachrichten/tpl.CopyConfirm.php",
		);

    }

    public function index2Action() {
        $tpl = $this->newTpl();



        $this->fw->setVariable('CONTENT', $tpl->get('Nachrichten/tpl.Index.php'));
    }

    protected function preInsert($data) {
	return $data;
}
protected function preUpdate($pk, $data) {
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