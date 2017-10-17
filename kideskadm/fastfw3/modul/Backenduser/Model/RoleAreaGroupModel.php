<?php

namespace Backenduser\Model;

/**
 */
class RoleAreaGroupModel extends \classes\AbstractModel {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var integer
	 */
	protected $sort;

	/**
	 * @var string
	 */
	protected $prefix = 'brag';

	/**
	 * @param Array $data
	 * @return void
	 */
	public function setData(Array $data) {
		parent::setData($data);
		$this->setName($data['brag_name']);
		$this->setSort($data['brag_sort']);
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