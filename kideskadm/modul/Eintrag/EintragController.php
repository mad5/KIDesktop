<?php
class EintragController extends \classes\AbstractCrudController {

    public function __construct() {
    	    
    	    if(!loggedIn()) {jump2page("Backenduser/login");exit;}
    	    
    	    $rechnerRepository = new \Rechner\Repository\RechnerRepository();
    	    $rechnerRepository->addWhere("re_bu_fk='".me()->getPk()."' ");
    	    if($rechnerRepository->countAll()==0) {
    	    	    \classes\FlashMessage::add("Bitte zunächst einen Rechner anlegen.", "info");
    	    	    return jump2page("Rechner");
    	    }
    	    
    	    
    	    $bereicheRepository = new \Bereiche\Repository\BereichRepository();
    	    $bereicheRepository->addWhere("be_bu_fk='".me()->getPk()."' ");
    	    if($bereicheRepository->countAll()==0) {
    	    	    \classes\FlashMessage::add("Bitte zunächst einen Bereich anlegen.", "info");
    	    	    return jump2page("Bereiche");
    	    }
    	    
        parent::__construct();
        #new fe_user(array('checklogin' => true)); // array('requirelogin' => true)

        $this->initCrud("Eintrag", "Eintrag", "ei");
		$this->addListColumn("ei_name", "Name");
		$this->addListColumn("ei_icon", "Icon");
		//$this->addListColumn("ei_kategorie", "Kategorie");
		$this->addListColumn("ei_bereich", "Bereich");
		$this->addListColumn("ei_rechner", "Rechner");
		$this->addListColumn("ei_typ", "Typ");
		$this->addListColumn("ei_befehl", "Befehl/URL");
		$this->setSortableColumns(array("ei_name","ei_icon","ei_kategorie","ei_bereich","ei_rechner","ei_typ","ei_befehl"));
		$this->templates = array(
		    "head"          => "Eintrag/tpl.Head.php",
		    "list"          => "Eintrag/tpl.Index.php",
		    "form"          => "Eintrag/tpl.Form.php",
		    "deleteConfirm" => "Eintrag/tpl.DeleteConfirm.php",
		    "copyConfirm"   => "Eintrag/tpl.CopyConfirm.php",
		);
		$this->repository->addWhere(" ei_bu_fk='".me()->getPk()."' ");
		$this->repository->addEntryWhere(" ei_bu_fk='".me()->getPk()."' ");
		
		$this->hideCrudListActionView = true;
		
    }

    public function index2Action() {
        $tpl = $this->newTpl();



        $this->fw->setVariable('CONTENT', $tpl->get('Eintrag/tpl.Index.php'));
    }

    protected function prepareRelData($data) {
	if (is_Array($data["ei_kategorie"])) {
		$data["ei_kategorie"] = implode(",", $data["ei_kategorie"]);
	} else {
		$data["ei_kategorie"] = "";
	}
	if (is_Array($data["ei_rechner"])) {
		$data["ei_rechner"] = implode(",", $data["ei_rechner"]);
	} else {
		$data["ei_rechner"] = "";
	}


	$c = "";
	$hosts = explode("\n", $data["ei_hosts"]);
	foreach($hosts as $host) {
		$host = trim($host);
		$ip = getHostByName($host);
		if($ip!=$host) {
			$c .= $ip."\t".$host."\n";
		}
		$ip = getHostByName("www.".$host);
		if($ip!=$host) {
			if($ip!="") $c .= $ip."\t"."www.".$host."\n";
		}
	}
	$data["ei_ips"] = $c;
	
return $data;
}

		

protected function preInsert($data) {
	$data = $this->prepareRelData($data);
    	    $data["ei_bu_fk"] = me()->getPk();

	return $data;
}
protected function preUpdate($pk, $data) {
	$data = $this->prepareRelData($data);
	$data["ei_bu_fk"] = me()->getPk();
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