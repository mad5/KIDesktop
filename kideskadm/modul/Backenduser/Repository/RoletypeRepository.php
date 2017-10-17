<?php

namespace Backenduser\Repository;

/**
 * Rollentyp Repository
 */
class RoletypeRepository {

	/**
	 * @const integer
	 */
	const ROLETYPE_PRUEFUNGSMANAGEMENT = 1;

	/**
	 * @const integer
	 */
	const ROLETYPE_PRUEFUNGSAUFSICHT = 2;

	/**
	 * @const integer
	 */
	const ROLETYPE_EXPERTE = 3;

	/**
	 * @const integer
	 */
	const ROLETYPE_PRODUKTVERANTWORTLICHER = 4;

	/**
	 * @const integer
	 */
	const ROLETYPE_FRAGENAUTOR = 5;

	/**
	 * @var Array
	 */
	private $roletypes = Array(
		1 => 'Prüfungsmanagement',
		2 => 'Prüfungsaufsicht',
		3 => 'Experte',
		4 => 'Produktverantwortlicher',
		5 => 'Fragenautor',

	);

	/**
	 * @var Array
	 */
	private $roletypeobjects = Array();

	/**
	 * @param integer $pk
	 * @return \Backenduser\Model\RoletypeModel|NULL
	 */
	public function findByPk($pk) {
		return isset($this->roletypes[(int)$pk]) ? new \Backenduser\Model\RoletypeModel(Array('pk' => (int)$pk, 'name' => $this->roletypes[(int)$pk])) : NULL;
	}

	/**
	 * @return Array
	 */
	public function findAll() {
		if (count($this->roletypeobjects) <= 0) {
			foreach ($this->roletypes as $pk => $name) {
				$this->roletypeobjects[(int)$pk] = $this->findByPk((int)$pk);
			}
		}

		return $this->roletypeobjects;
	}

}

?>