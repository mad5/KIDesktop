<?php
class EinstellungenController extends \classes\AbstractController {

    public function __construct() {
        parent::__construct();
        #new fe_user(array('checklogin' => true)); // array('requirelogin' => true)

        
    }

    public function indexAction() {
        $tpl = $this->newTpl();



        $this->fw->setVariable('CONTENT', $tpl->get('Einstellungen/tpl.Index.php'));
    }
    
       public function changePWAction() {
		if(!loggedIn()) jump2page("Backenduser/login");
		$tpl = $this->newTpl();
		$backenduserRepository = new \Backenduser\Repository\BackenduserRepository();
		$user = $backenduserRepository->findByPk(me()->getPk());
		
		if($user->getHash()=="") {
			$data["bu_hash"] = createCode(10);
			$user->setHash($data["bu_hash"]);
			$backenduserRepository->update($data, me()->getPk());
		}
		
		$tpl->setVariable("user", $user);
		$this->fw->setVariable('CONTENT', $tpl->get('Einstellungen/tpl.ChangePW.php'));
	}

	public function changePWRunAction() {

        /**
         * @var $old \Backenduser\Model\BackenduserModel
         * @var $user \Backenduser\Model\BackenduserModel
         */

        if(trim($_POST['username'])==='') {
            \classes\FlashMessage::add(transFull("Der neue Benutzername darf nicht leer sein."), "danger");
            jump2page("*/changePW");
        } else {
            $backenduserRepository = new \Backenduser\Repository\BackenduserRepository();

            $user = $backenduserRepository->findByUsername($_POST['username']);

            if (!isNullObj($user) && $user->getPk()!=me()->getPk()) {
                \classes\FlashMessage::add(transFull("Der neue Benutzername ist bereits vergeben."), "danger");
                jump2page("*/changePW");
            }
        }
        if($_POST['my_old_pw']!=='') { //Nur validieren wenn eine Passwortänderung vorgenommen werden soll.
            if (!$backenduserRepository->testPassword($_POST["my_old_pw"], me()->getPk())) {
                \classes\FlashMessage::add(transFull("Das alte Kennwort ist leider nicht korrekt!."), "danger");
                jump2page("*/changePW");
            }
            if ($_POST["my_new_pw"] == "") {
                \classes\FlashMessage::add(transFull("Bitte geben Sie ein neues Kennwort an."), "danger");
                jump2page("*/changePW");
            }
            if ($_POST["my_old_pw"] == $_POST["my_new_pw"]) {
                \classes\FlashMessage::add(transFull("Das alte Kennwort ist gleich dem Neuen. Bitte verwenden Sie ein anderes neues Kennwort"), "danger");
                jump2page("*/changePW");
            }
            if ($_POST["my_new_pw2"] != $_POST["my_new_pw"]) {

                \classes\FlashMessage::add(transFull("Das neue Kennwort unterscheidet sich von dem wiederholten Kennwort."), "danger");
                jump2page("*/changePW");
            }

            //$backenduserRepository->setNewPassword($_POST["my_new_pw"], me()->getPk());
        }
        if($_POST['my_new_pw']!=='') {
            $data = Array(
                'bu_password' => md5(USER_SECRET.strtolower($_POST["my_new_pw"])),
                'bu_firstname' => $_POST['firstname'],
                'bu_lastname' => $_POST['lastname'],
                'bu_username' => $_POST['username']
            );
        } else {
            $data = Array(
                'bu_firstname' => $_POST['firstname'],
                'bu_lastname' => $_POST['lastname'],
                'bu_username' => $_POST['username']
            );
        }

        $backenduserRepository->update($data,me()->getPk());
		$tpl = $this->newTpl();
		$this->fw->setVariable('CONTENT', $tpl->get('Einstellungen/tpl.ChangePWDone.php'));

	}	
    

} // fastfwController
?>