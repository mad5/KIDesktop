<?php

namespace Backenduser\Model;

/**
 */
class RoleRightModel extends \classes\AbstractModel {

	/**
	 * @var string
	 */
	protected $id = '';

	/**
	 * @var \Backenduser\Model\RoleModel
	 */
	protected $role;

	/**
	 * @var string
	 */
	protected $own = '';

	/**
	 * @var string
	 */
	protected $other = '';

	/**
	 * @var string
	 */
	protected $prefix = 'brr';

	/**
	 * @param array $data
	 * @return void
	 */
	public function setData(Array $data) {
		parent::setData($data);
		$this->setRole($data['brr_br_fk']);
		$this->setId($data['brr_id']);
		$this->setOwn($data['brr_own']);
		$this->setOther($data['brr_other']);
	}

	/**
	 * @param \Backenduser\Model\RoleModel|integer $role
	 */
	public function setRole($role) {
		$this->role = $role;
	}

	/**
	 * @return \Backenduser\Model\RoleModel|NULL
	 */
	public function getRole() {
		if (!($this->role instanceof \Backenduser\Model\RoleModel)) {
			if ((int)$this->role > 0) {
				$roleRepository = new \Backenduser\Repository\RoleRepository();
				$this->setRole($roleRepository->findByPk($this->role));
			}
			else {
				$this->setRole(NULL);
			}
		}

		return $this->role;
	}

	/**
	 * @param string $id
	 * @return void
	 */
	public function setId($id) {
		$this->id = (string)$id;
	}

	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param string $own
	 * @return void
	 */
	public function setOwn($own) {
		$this->own = (string)$own;
	}

	/**
	 * @return string
	 */
	public function getOwn() {
		return $this->own;
	}

	/**
	 * @param string $other
	 * @return void
	 */
	public function setOther($other) {
		$this->other = (string)$other;
	}

	/**
	 * @return string
	 */
	public function getOther() {
		return $this->other;
	}

} // fastfwModel

?>