<?php

namespace Backenduser\Model;

/**
 * Benutzerrolle
 */
class RoleModel extends \classes\AbstractModel {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var \Backenduser\Model\RoletypeModel
	 */
	protected $roletype;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var Array
	 */
	protected $roleRights = Array();

	/**
	 * @var string
	 */
	protected $prefix = 'br';

	/**
	 * @param array $data
	 * @return void
	 */
	public function setData(Array $data) {
		parent::setData($data);
		$this->name = $data['br_name'];
		$this->description = $data['br_description'];
		$this->roletype = $data['br_roletype_fk'];
	}

	/**
	 * @param String $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return String
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param \Backenduser\Model\RoletypeModel $roletype
	 */
	public function setRoletype($roletype) {
		$this->roletype = $roletype;
	}

	/**
	 * @return \Backenduser\Model\RoletypeModel
	 */
	public function getRoletype() {
		if (!($this->roletype instanceof \Backenduser\Model\RoletypeModel)) {
			if ((int)$this->roletype > 0) {
				$roletypeRepository = new \Backenduser\Repository\RoletypeRepository();
				$this->setRoletype($roletypeRepository->findByPk($this->roletype));
			}
			else {
				$this->setRoletype(NULL);
			}
		}

		return $this->roletype;
	}

	/**
	 * @param String $description
	 * @return void
	 */
	public function setDescription($desciption) {
		$this->description = $desciption;
	}

	/**
	 * @return String
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param Array $roleRights
	 * @return void
	 */
	public function setRoleRights(Array $roleRights) {
		$this->roleRights = $roleRights;
	}

	/**
	 * @return Array
	 */
	public function getRoleRights() {
		if (count($this->roleRights) == 0) {
			$roleRightRepository = new \Backenduser\Repository\RoleRightRepository();
			$this->roleRights = $roleRightRepository->findByRole($this);
		}

		return $this->roleRights;
	}

} // fastfwModel

?>