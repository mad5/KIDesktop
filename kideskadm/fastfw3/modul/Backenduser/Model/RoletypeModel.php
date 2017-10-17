<?php

namespace Backenduser\Model;

/**
 * Benutzerrollentyp
 */
class RoletypeModel {

	/**
	 * @var integer
	 */
	protected $pk = 0;

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @param Array $data
	 */
	public function __construct(Array $data = array()) {
		if (isset($data['pk'])) {
			$this->setPk($data['pk']);
		}
		if (isset($data['name'])) {
			$this->setName($data['name']);
		}
	}

	/**
	 * @return integer
	 */
	public function getPk() {
		return $this->pk;
	}

	/**
	 * @param integer $pk
	 * @throws InvalidArgumentException wenn die ID kleiner als 0 ist
	 * @return void
	 */
	public function setPk($pk) {
		if ((int)$pk < 0) {
			throw new \InvalidArgumentException('invalid argument');
		}
		else {
			$this->pk = (int)$pk;
		}
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = (string)$name;
	}

} // fastfwModel

?>