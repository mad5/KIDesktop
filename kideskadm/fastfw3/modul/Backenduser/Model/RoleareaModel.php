<?php

namespace Backenduser\Model;

/**
 */
class RoleAreaModel extends \classes\AbstractModel {

	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var \Backenduser\Model\RoleAreaGroupModel
	 */
	protected $group;

	/**
	 * @var integer
	 */
	protected $parentPk;

	/**
	 * @var integer
	 */
	protected $sort;

	/**
	 * @var string
	 */
	protected $prefix = 'bra';

	/**
	 * @param Array $data
	 * @return void
	 */
	public function setData(Array $data) {
		parent::setData($data);
		$this->setId($data['bra_id']);
		$this->setName($data['bra_name']);
		$this->setDescription($data['bra_description']);
		$this->setGroup((int)$data['bra_brag_fk']);
		$this->setParentPk($data['bra_bra_fk']);
		$this->setSort($data['bra_sort']);
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
	 * @param string $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = (string)$name;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $description
	 * @return void
	 */
	public function setDescription($desciption) {
		$this->description = (string)$desciption;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param \Backenduser\Model\RoleAreaGroupModel|integer $group
	 * @return void
	 */
	public function setGroup($group) {
		$this->group = $group;
	}

	/**
	 * @return \Backenduser\Model\RoleAreaGroupModel|NULL
	 */
	public function getGroup() {
		if (!($this->group instanceof \Backenduser\Model\RoleAreaGroupModel)) {
			if ((int)$this->group > 0) {
				$roleAreaGroupRepository = new \Backenduser\Repository\RoleAreaGroupRepository();
				$this->setGroup($roleAreaGroupRepository->findByPk($this->group));
			}
			else {
				$this->setGroup(NULL);
			}
		}

		return $this->group;
	}

	/**
	 * @param integer $parentPk
	 * @return void
	 */
	public function setParentPk($parentPk) {
		$this->parentPk = (int)$parentPk;
	}

	/**
	 * @return integer
	 */
	public function getParentPk() {
		return $this->parentPk;
	}

	/**
	 * @param integer $sort
	 * @return void
	 */
	public function setSort($sort) {
		$this->sort = (int)$sort;
	}

	/**
	 * @return integer
	 */
	public function getSort() {
		return $this->sort;
	}

} // fastfwModel

?>