<?php

/**
 */
class BackenduserController extends \classes\AbstractCrudController {

	/**
	 * @var \Backenduser\Repository\RoleRepository
	 */
	protected $roleRepository;

	/**
	 * @return void
	 */
	public function __construct() {
	#echo "U";exit;
		parent::__construct();
		//new fe_user(array('checklogin' => true)); // array('requirelogin' => true)

		$this->initCrud('Backenduser', 'Backenduser', 'bu');
		$this->addListColumn("bu_username", trans("backenduser|Loginname"));
		$this->addListColumn("bu_firstname", trans("backenduser|Vorname"));
		$this->addListColumn("bu_lastname", trans("backenduser|Nachname"));
		$this->addListColumn("bu_email", trans("backenduser|E-Mailadresse"));
		$this->addListColumn("bu_br_fk", trans("backenduser|Rollen"));
		$this->addListColumn("bu_admin", trans("backenduser|Admin"));

		$this->setSortableColumns(array("bu_username", "bu_firstname", "bu_lastname", "bu_email"));

		$this->roleRepository = new \Backenduser\Repository\RoleRepository();
	}

	public function indexAction(array $paramArray=array()) {

		if(!loggedIn()) jump2page("*/login");

		if(isset($paramArray[0])) {
			setS("onlyNew",0);
			$_GET["crudListPage"] = 0;
			$_REQUEST["crudListPage"] = 0;
			if($paramArray[0]=="neue") {
				setS("onlyNew",1);
			}
		}

		if(getS("onlyNew")==1) {
			#$this->repository->addWhere("bu_disable=0");
			#$this->repository->addWhere("bu_norights=1");
		}

		parent::indexAction();
	}

	public function listNewAction() {

		setS("onlyNew",0);
		setS("onlyNew", 1);
		$this->indexAction(array("neue"));
	}

	/**
	 * @return void
	 */
	public function loginAction() {
		session_regenerate_id();
		if(trysso!=0 && isset($_SERVER["AUTH_USER"]) && $_SERVER["AUTH_USER"]!="") jump2page("Index");

		$tpl = $this->newTpl();
		$this->fw->setVariable('CONTENT', $tpl->get('tpl.Login.php'));
	}

	/**
	 * @return void
	 */
	public function checkLoginAction() {
		$backenduserRepository = new \Backenduser\Repository\BackenduserRepository();

		if(trim($_POST["backenduser"]["bu_username"])=="" || trim($_POST["backenduser"]["bu_password"])=="") {
		    jump2page("*/login");
		}

		$user = $backenduserRepository->checkLogin($_POST["backenduser"]["bu_username"], $_POST["backenduser"]["bu_password"]);
		if (! ($user instanceof \Backenduser\Model\BackenduserModel) ) {

			\classes\FlashMessage::add(transFull("backenduser|Login fehlgeschlagen."), "error");
			jump2page("*/login");
			exit;

#vd($_POST);exit;
			$backenduser = $backenduserRepository->findByUsername($_POST["backenduser"]["bu_username"]);

			if (!($backenduser instanceof \Backenduser\Model\BackenduserModel) && $_POST["backenduser"]["bu_username"]!='') {

				$data = array("bu_username" => $_POST["backenduser"]["bu_username"]);
				if($this->fw->DC->countByQuery("SELECT count(*) FROM backenduser")==0) {
					$data["bu_admin"] = 1;
					$data["bu_password"] = md5(USER_SECRET.$_POST["backenduser"]["bu_password"]);
					\classes\FlashMessage::add(transFull("backenduser|Sie sind der erste Benutzer. Daher wurden Sie als Admin eingetragen!"), "info");
					$bu_pk = $backenduserRepository->insert($data);
					$backenduser = $backenduserRepository->findByPk($bu_pk);

					\Backenduser\Service\ActiveBackenduserService::login($backenduser);
					jump2page("Index");
				} else {
					\classes\FlashMessage::add(transFull("backenduser|Login fehlgeschlagen"), "error");
					jump2page("*/login");
				}

			}

			if($backenduser->getDisable()==1) {
				\classes\FlashMessage::add(transFull("backenduser|Login fehlgeschlagen. Ihr Benutzerkonto ist zur Zeit gesperrt."), "error");
				jump2page("*/login");
			}

			jump2page("*/login");
			//\Backenduser\Service\ActiveBackenduserService::login($backenduser);


			jump2page("Index");
		} else {
			$backenduser = $backenduserRepository->findByUsername($_POST["backenduser"]["bu_username"]);
			\Backenduser\Service\ActiveBackenduserService::login($backenduser);
			jump2page("Index");
			#jump2page("Index");
		}
	}

	/**
	 * @return void
	 */
	public function logoutAction() {
		\Backenduser\Service\ActiveBackenduserService::logout();
		session_destroy();
		jump2page("*/login");
	}

	/**
	 * @param Array $queryArray
	 * @return void
	 */
	public function editAction(array $queryArray) {
		if(!loggedIn()) jump2page("*/login");
		$this->addVariable('roles', $this->roleRepository->findAll());

		parent::editAction($queryArray);
	}

	protected function postUpdate($pk, $data) {
		/**
		 * @var $user \Backenduser\Model\BackenduserModel
		 */
		$user = $this->repository->findByPk($pk);

		$roles = array();
		foreach ($user->getRoles() as $role) {
			$roles[] = $role->getName();
		}
		if($roles==array() && !$user->isAdmin()) {
			#$this->fw->DC->sendQuery("UPDATE backenduser SET bu_norights=1 WHERE bu_pk='".(int)$pk."' ");
		} else {
			#$this->fw->DC->sendQuery("UPDATE backenduser SET bu_norights=0 WHERE bu_pk='".(int)$pk."' ");
		}
		//vd($roles);exit;
		return $data;
	}

	/**
	 * @return void
	 */
	public function newAction() {
		if(!loggedIn()) jump2page("*/login");
		$this->addVariable('roles', $this->roleRepository->findAll());

		parent::newAction();
	}

	public function RegistrationAction() {
	#echo "Y";exit;
		$tpl = $this->newTpl();

		$this->fw->setVariable('CONTENT', $tpl->get('tpl.Registration.php'));
	}

	/**
	 * @review (ay) Hier noch validieren, dass alle Daten vorhanden sind. (klären wo das verwendet wird) -> AY
	 */
	public function RegisterAction() {
		if($_POST["backenduser"]["bu_username"]=="") {
			\classes\FlashMessage::add("Bitte Benutzername angeben");
			jump2page("*/Registration");
		}
		if($_POST["backenduser"]["bu_password"]=="") {
			\classes\FlashMessage::add("Bitte Passwort angeben");
			jump2page("*/Registration");
		}
		$data = array(
			'bu_username' => $_POST["backenduser"]["bu_username"],
			'bu_password' => md5(USER_SECRET.$_POST["backenduser"]["bu_password"]),
			'bu_firstname' => $_POST["backenduser"]["bu_firstname"],
			'bu_lastname' => $_POST["backenduser"]["bu_lastname"],
			'bu_email' => $_POST["backenduser"]["bu_email"],
			'bu_admin' => 0,
			"bu_hash" => createCode(10),
		);

		$backenduserRepository = new \Backenduser\Repository\BackenduserRepository();
		$bu_pk = $backenduserRepository->insert($data);
		
		
		$data = array(
			"re_kind" => "Rechner 1",
			"re_ort" => "Kinderzimmer",
			"re_beschreibung" => "",
			"re_letzteip" => "",
			"re_zuletztonline" => "",
			"re_offlineab" => "21:00",
			"re_offlinebis" => "06:00",
			"re_ausab" => "22:00",
			"re_ausbis" => "06:00",
			"re_nutzungsdauerinsgesamt" => "03:00",
			"re_bu_fk" => $bu_pk,
			"re_hash" => createCode(10),
			"re_bild" => "../resources/images/computer.png"
			);
		$rechnerRepository = new \Rechner\Repository\RechnerRepository();
		$re_pk = $rechnerRepository->insert($data);
		
		$data = array(
			"be_name" => "Lernen",
			"be_icon" => "../resources/images/learn.png",
			"be_reihenfolge" => 1,
			"be_freigegeben" => 1,
			"be_bu_fk" => $bu_pk,
			);
		$bereichRepository = new \Bereiche\Repository\BereichRepository();
		$be_pk = $bereichRepository->insert($data);
		
		$data = array(
			"ei_name" => "Antolin",
			"ei_icon" => "../resources/images/antolin.jpg",
			"ei_kategorie" => 0,
			"ei_bereich" => $be_pk,
			"ei_rechner" => $re_pk,
			"ei_typ" => "webseite",
			"ei_befehl" => "https://www.antolin.de/",		
			"ei_hosts" => "antolin.de",
			"ei_bu_fk" => $bu_pk,
			);
		$eintragRepository = new \Eintrag\Repository\EintragRepository();
		$be_pk = $eintragRepository->insert($data);
		
		
		
		
		setS("activeBackenduser", $bu_pk);
		jump2page('Index');
	}

	public function changePWAction() {
		if(!loggedIn()) jump2page("*/login");
		$tpl = $this->newTpl();

		$this->fw->setVariable('CONTENT', $tpl->get('Backenduser/tpl.ChangePW.php'));
	}

	public function changePWRunAction() {
		if(!loggedIn()) jump2page("*/login");

		if($_POST["my_old_pw"]=="") {
			\classes\FlashMessage::add("Bitte geben Sie ihr bisheriges Kennwort an.", "danger");
			jump2page("*/changePW");
		}
		if($_POST["my_new_pw"]=="") {
			\classes\FlashMessage::add("Bitte geben Sie ein neues Kennwort an.", "danger");
			jump2page("*/changePW");
		}
		if($_POST["my_old_pw"]==$_POST["my_new_pw"]) {
			\classes\FlashMessage::add("Das alte Kennwort ist gleich dem Neuen. Bitte verwenden Sie ein anderes neues Kennwort", "danger");
			jump2page("*/changePW");
		}
		if($_POST["my_new_pw2"]!=$_POST["my_new_pw"]) {
			\classes\FlashMessage::add("Das neue Kennwort unterscheidet sich von dem wiederholten Kennwort.", "danger");
			jump2page("*/changePW");
		}

		$backenduserRepository = new \Backenduser\Repository\BackenduserRepository();

		if(!$backenduserRepository->testPassword($_POST["my_old_pw"], me())) {
			\classes\FlashMessage::add("Das alte Kennwort ist leider nicht korrekt!.", "danger");
			jump2page("*/changePW");
		}

		$backenduserRepository->setNewPassword($_POST["my_new_pw"], me());

		$tpl = $this->newTpl();
		$this->fw->setVariable('CONTENT', $tpl->get('Backenduser/tpl.ChangePWDone.php'));

	}
	
	public function fakeAction() {
		//setS("activeBackenduser", 28);
		jump2page("Index");
	}

} // fastfwController

?>