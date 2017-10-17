<?php

namespace Backenduser\Model;

/**
 * Backendbenutzer
 */
class BackenduserModel extends \classes\AbstractModel {

	/**
	 * @var string
	 */
	protected $username;

	/**
	 * @var string
	 */
	protected $firstname;

	/**
	 * @var string
	 */
	protected $lastname;

	/**
	 * @var string
	 */
	protected $email;

	/**
	 * @var array
	 */
	protected $roles = Array();

	/**
	 * @var mixed
	 */
	protected $admin;

	protected $language;

	protected $disable;
	protected $noRights;

	/**
	 * @var string
	 */
	protected $prefix = 'bu';

	/**
	 * @param Array $data
	 * @return void
	 */
	public function setData(Array $data) {
		parent::setData($data);
		$this->setUsername($data['bu_username']);
		$this->setFirstname($data['bu_firstname']);
		$this->setLastname($data['bu_lastname']);
		$this->setAdmin((int)$data['bu_admin']);
		$this->setEmail($data['bu_email']);

		$this->setLanguage($data['bu_language']);
		$this->setDisable($data['bu_disable']);
		$this->setNoRights($data['bu_norights']);

		if (count($this->roles) <= 0) {
			$rolesRepository = new \Backenduser\Repository\RoleRepository();
			$this->roles = $rolesRepository->findAllByBackenduser($this);
		}
	}

	/**
	 * @return mixed
	 */
	public function getNoRights() {
		return $this->noRights;
	}

	/**
	 * @param mixed $disable
	 */
	public function setNoRights($noRights) {
		$this->noRights = $noRights;
	}
	/**
	 * @return mixed
	 */
	public function getDisable() {
		return $this->disable;
	}

	/**
	 * @param mixed $disable
	 */
	public function setDisable($disable) {
		$this->disable = $disable;
	}

	/**
	 * @return mixed
	 */
	public function getLanguage() {
		if (!($this->language instanceof \Language\Model\LanguageModel)) {
			if ((int)$this->language > 0) {
				$languageRepository = new \Language\Repository\LanguageRepository();
				$this->setLanguage($languageRepository->findByPk($this->language));
			}
			else {
				$this->setLanguage(NULL);
			}
		}
		return $this->language;
	}

	/**
	 * @param mixed $language
	 */
	public function setLanguage($language) {
		$this->language = $language;
	}



	/**
	 * @param String $username
	 * @return void
	 */
	public function setUsername($username) {
		$this->username = $username;
	}

	/**
	 * @return String
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * @param String $firstname
	 * @return void
	 */
	public function setFirstname($firstname) {
		$this->firstname = (string)$firstname;
	}


	/**
	 * @return String
	 */
	public function getFirstname() {
		return $this->firstname;
	}

	/**
	 * @param String $lastname
	 * @return void
	 */
	public function setLastname($lastname) {
		$this->lastname = (string)$lastname;
	}

	/**
	 * @return String
	 */
	public function getLastname() {
		return $this->lastname;
	}

	public function getFullname() {
		$name = trim($this->getFirstname()." ".$this->getLastname());
		if($name=="") $name = $this->getUsername();
		return $name;
	}

	/**
	 * @return mixed
	 */
	public function getAdmin() {
		return $this->admin;
	}

	/**
	 * @param mixed $admin
	 * @return void
	 */
	public function setAdmin($admin) {
		$this->admin = $admin;
	}

	/**
	 * @return boolean
	 */
	public function isAdmin() {
		return $this->admin == 1;
	}

	/**
	 * @param String $email
	 * @return void
	 */
	public function setEmail($email) {
		$this->email = (string)$email;
	}

	/**
	 * @return String
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @return void
	 */
	public function setRoles(Array $roles) {
		$this->roles = $roles;
	}

	/**
	 * @return Array
	 */
	public function getRoles() {
		return $this->roles;
	}

	/**
	 * @param \Backenduser\Model\RoleModel $role
	 * @return void
	 */
	public function addRole(\Backenduser\Model\RoleModel $role) {
		$this->roles[$role->getPk()] = $role;
	}

	/**
	 * @param \Backenduser\Model\RoleModel $role
	 * @return void
	 */
	public function removeRole(\Backenduser\Model\RoleModel $role) {
		unset($this->roles[$role->getPk()]);
	}

	/**
	 * @return void
	 */
	public function removeAllRoles() {
		$this->roles = Array();
	}

	/**
	 * @param \Backenduser\Model\RoleModel $role
	 * @return boolean
	 */
	public function hasRole(\Backenduser\Model\RoleModel $role) {
		return isset($this->roles[$role->getPk()]);
	}

	/**
	 * @param integer $roletypePk
	 * @return bool
	 */
	public function hasRoleWithRoletype($roletypePk) {
		if(\Backenduser\Service\ActiveBackenduserService::isAdmin()) return TRUE;
		foreach ($this->getRoles() as $role) {
			if($role==NULL) return FALSE;
			if($role->getRoletype()==NULL) return FALSE;
			if ($role->getRoletype()->getPk() == (int)$roletypePk) {
				return TRUE;
			}
		}

		return FALSE;
	}

	public function __toString() {
		return (string)$this->getPk();
	}

} // fastfwModel

?>