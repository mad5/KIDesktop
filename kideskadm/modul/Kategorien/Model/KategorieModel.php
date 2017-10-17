<?php
namespace Kategorien\Model;

class KategorieModel extends \classes\AbstractModel {

	protected $prefix = "ka";

	protected $name = '';
	protected $bereich = 0;
	protected $rechner = 0;

	public function setData(Array $data) {
		parent::setData($data);

		$this->setName($data["ka_name"]);
		$this->setBereich($data["ka_bereich"]);
		$this->setRechner($data["ka_rechner"]);
	     $this->setBu_fk($data["ka_bu_fk"]);
	}
/**
	 * @param int $bu_fk
	 */
	public function setBu_fk($bu_fk) {
		$this->bu_fk = $bu_fk;
	}

	/**
	 * @return \\Model\Model
	 */
	public function getBu_fk() {
		if (!($this->bu_fk instanceof \Backenduser\Model\BackenduserModel)) {
			$backenduserRepository = new \Backenduser\Repository\BackenduserRepository();
			$this->setBu_fk($backenduserRepository->findByPk($this->bu_fk));
		}

		return $this->bu_fk;
	}	

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param int $bereich
	 */
	public function setBereich($bereich) {
		$this->bereich = $bereich;
	}

	/**
	 * @return \\Model\Model
	 */
	public function getBereich() {
		if (!($this->bereich instanceof \Bereiche\Model\BereichModel)) {
			$bereichRepository = new \Bereiche\Repository\BereichRepository();
			$this->setBereich($bereichRepository->findByPk($this->bereich));
		}

		return $this->bereich;
	}

	public function getBereichName() {
		return $this->getBereich()->getName();
	}

	public function isInBereich($pk) {
		foreach ($this->getBereich() as $one) {
			if ($one->getPk() == $pk) {
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * @return \Bereiche\Model\BereichModel[]
	 */
	public function getAllPossibleBereich() {
		$bereichRepository = new \Bereiche\Repository\BereichRepository();

		return $bereichRepository->findAll();
	}

	/**
	 * @param int $rechner
	 */
	public function setRechner($rechner) {
		$this->rechner = $rechner;
	}

	/**
	 * @return \Bereiche\Model\BereichModel[]
	 */
	public function getRechner() {
		if (!is_array($this->rechner)) {
			if ($this->rechner == "") {
				$this->rechner = array();

				return array();
			}
			$rechnerRepository = new \Rechner\Repository\RechnerRepository();
			$this->setRechner($rechnerRepository->findAllByPks(explode(",", $this->rechner)));
		}

		return $this->rechner;
	}

	public function getRechnerKind() {
		$As = $this->getRechner();
		$V = array();
		foreach ($As as $A) {
			$V[] = $A->getKind();
		}

		return implode(", ", $V);
	}

	public function isInRechner($pk) {
		foreach ($this->getRechner() as $one) {
			if ($one->getPk() == $pk) {
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * @return \Rechner\Model\RechnerModel[]
	 */
	public function getAllPossibleRechner() {
		$rechnerRepository = new \Rechner\Repository\RechnerRepository();

		return $rechnerRepository->findAll();
	}

} // fastfwModel
?>