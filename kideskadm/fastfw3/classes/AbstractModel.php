<?php

namespace classes;

/**
 * Class AbstractModel
 * Abstraktes Model mit Basis-Eigenschaften wie z.B. ID, Anlegedatum, Datum letzte Änderung, Flags für "gelöscht" und
 * "deaktiviert" sowie Basis-Funktionalität z.B. für das Holen und Setzen der Eigenschaftswerte.
 *
 * @package classes
 */
abstract class AbstractModel {

	/**
	 * ID des Objekts
	 *
	 * @var integer
	 */
	protected $pk;

	/**
	 * Anlegedatum
	 *
	 * @var string
	 */
	protected $createdate;

	/**
	 * Datum letzte Änderung
	 *
	 * @var string
	 */
	protected $changedate;

	/**
	 * Flag, ob Objekt gelöscht (1) ist oder nicht (0)
	 *
	 * @var integer
	 */
	protected $deleted;

	/**
	 * Flag, ob Objekt aktiv (0) oder deaktiviert (1) ist
	 *
	 * @var integer
	 */
	protected $hidden;

	/**
	 * Prefix für Formular- und DB-Felder für das konkrete Objekt, z.B. 'p' bei 'Product'
	 *
	 * @var string
	 */
	protected $prefix = '';

	/**
	 * Konstruktor
	 * Ist ein (optionales) Array mit Werten gegeben, werden diese beim Erzeugen des Objekts direkt in den ent-
	 * sprechenden Eigenschaften gesetzt. Dem Index eines solchen Array-Feldes muss der Prefix des Models vorangestellt
	 * sein, z.B. 'p' für 'Product'.
	 *
	 * Beispiel:
	 *
	 * $data = array(
	 *   'p_pk' => 5,
	 *   'p_createdate' = '2014-11-01 20:15:43',
	 *   ...
	 * );
	 *
	 * @param array $data (optional) Werte die beim Erzeugen des Objekts direkt in den Eigenschaften gesetzt werden sollen
	 */
	public function __construct(array $data = array()) {
		if (!empty($data)) {
			$this->setData($data);
		}
	}

	/**
	 * Setzen von Eigenschaftswerten gemäß den Werten im ggb. Array. Dem Index eines solchen Array-Feldes muss der
	 * Prefix des Models vorangestellt sein, z.B. 'p' für 'Product'.
	 *
	 * Beispiel:
	 *
	 * $data = array(
	 *   'p_pk' => 5,
	 *   'p_createdate' = '2014-11-01 20:15:43',
	 *   ...
	 * );
	 *
	 * @param array $data
	 * @return void
	 */
	public function setData(Array $data) {
		$this->setPk($data[$this->prefix . "_pk"]);
		$this->setCreatedate($data[$this->prefix . "_createdate"]);
		$this->setChangedate($data[$this->prefix . "_changedate"]);
		$this->setDeleted($data[$this->prefix . "_deleted"]);
		$this->setHidden($data[$this->prefix . "_hidden"]);
	}

	/**
	 * @return array
	 */
	public function getData() {
		return array(
			$this->getPrefix() . "_pk" => (int)$this->getPk() > 0 ? (int)$this->getPk() : NULL,
			$this->getPrefix() . "_createdate" => $this->getCreatedate(),
			$this->getPrefix() . "_changedate" => $this->getChangedate(),
			$this->getPrefix() . "_deleted" => (int)$this->getDeleted(),
			$this->getPrefix() . "_hidden" => (int)$this->getHidden(),
		);
	}

	/**
	 * @return void
	 */
	public function clearData() {
		$this->setData(array());
	}

	/**
	 * Liefert das Datum der letzten Änderung zurück
	 *
	 * @return string
	 */
	public function getChangedate() {
		return new \classes\Date($this->changedate);
	}

	/**
	 * Setzen des Datums der letzten Änderung
	 *
	 * @param string $changedate
	 * @return void
	 */
	public function setChangedate($changedate) {
		$this->changedate = $changedate;
	}

	/**
	 * Liefert das Anlegedatum zurück
	 *
	 * @return \classes\Date
	 */
	public function getCreatedate() {
		return new \classes\Date($this->createdate);
	}

	/**
	 * Setzen des Anlegedatums
	 *
	 * @param string $createdate
	 * @return void
	 */
	public function setCreatedate($createdate) {
		$this->createdate = $createdate;
	}

	/**
	 * Liefert zurück, ob das Objekt gelöscht ist (TRUE) oder nicht (FALSE)
	 *
	 * @return boolean
	 */
	public function isDeleted() {
		return $this->deleted === 1;
	}

	/**
	 * Liefert das Flag, das anzeigt, ob das Objekt gelöscht ist, zurück
	 *
	 * @return integer
	 */
	public function getDeleted() {
		return $this->deleted;
	}

	/**
	 * Setzten des Flags, das anzeigt, ob das Objekt gelöscht ist
	 *
	 * @param integer $deleted 1 = gelöscht, 0 = nicht gelöscht
	 * @throws InvalidArgumentException wenn das Flag nicht 0 oder 1 ist
	 * @return void
	 */
	public function setDeleted($deleted) {
		if ((int)$deleted != 0 && (int)$deleted != 1) {
			throw new \InvalidArgumentException('invalid argument');
		}
		$this->deleted = (int)$deleted;
	}

	/**
	 * Liefert zurück, ob das Objekt deaktiviert ist (TRUE) oder nicht (FALSE)
	 *
	 * @return boolean
	 */
	public function isHidden() {
		return $this->hidden === 1;
	}

	/**
	 * Liefert das Flag, das anzeigt, ob das Objekt deaktiviert ist, zurück
	 *
	 * @return integer
	 */
	public function getHidden() {
		return $this->hidden;
	}

	/**
	 * Setzten des Flags, das anzeigt, ob das Objekt deaktiviert ist
	 *
	 * @param integer $hidden 1 = deaktiviert, 0 = aktiv
	 * @throws InvalidArgumentException wenn das Flag nicht 0 oder 1 ist
	 * @return void
	 */
	public function setHidden($hidden) {
		if ((int)$hidden != 0 && (int)$hidden != 1) {
			throw new \InvalidArgumentException('invalid argument');
		}
		$this->hidden = (int)$hidden;
	}

	/**
	 * Liefert die ID des Objekts zurück
	 * @return integer
	 */
	public function getPk() {
		return $this->pk;
	}

	/**
	 * Setzen der ID des Objekts
	 *
	 * @param integer $pk
	 * @throws InvalidArgumentException wenn die ID kleiner als 0 ist
	 * @return void
	 */
	public function setPk($pk) {
		#if ((int)$pk < 0) {
		#	throw new \InvalidArgumentException('invalid argument');
		#}
		#else {
			$this->pk = $pk;
		#}
	}

	/**
	 * @return string
	 */
	public function getPrefix() {
		return $this->prefix;
	}

	/**
	 * @param string $prefix
	 */
	public function setPrefix($prefix) {
		$this->prefix = $prefix;
	}

}

?>