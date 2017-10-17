<?php
namespace Eintrag\Model;

class EintragModel extends \classes\AbstractModel {

	protected $prefix = "ei";

  protected $name = '';
  protected $icon = null;
  protected $kategorie = 0;
  protected $bereich = 0;
  protected $rechner = 0;
  protected $typ = '';
  protected $befehl = '';
	protected $hosts = '';
	protected $bu_fk;
	protected $ips;

public function setData(Array $data) {
		parent::setData($data);

     $this->setName($data["ei_name"]);
     $this->setIcon($data["ei_icon"]);
     $this->setKategorie($data["ei_kategorie"]);
     $this->setBereich($data["ei_bereich"]);
     $this->setRechner($data["ei_rechner"]);
     $this->setTyp($data["ei_typ"]);
     $this->setBefehl($data["ei_befehl"]);
		$this->setHosts($data["ei_hosts"]);
     $this->setBu_fk($data["ei_bu_fk"]);
     $this->setIPs($data["ei_ips"]);
	}
	
	public function setIPs($ips) {
		$this->ips = $ips;
	}

	public function getIPs() {
	return $this->ips;
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
  * @param string $icon
  */
  public function setIcon($icon) {
      $this->icon = $icon;
  }

  /**
  * @return string
  */
  public function getIcon() {
	return new \classes\UploadFile($this->icon);
  }

  /**
  * @param int $kategorie
  */
  public function setKategorie($kategorie) {
      $this->kategorie = $kategorie;
  }

  /**
 * @return \\Model\Model[]
  */
  public function getKategorie() {
	if (!is_array($this->kategorie)) {
if($this->kategorie=="") { $this->kategorie=array();return array();}
		$kategorieRepository = new \Kategorien\Repository\KategorieRepository();
		$this->setKategorie($kategorieRepository->findAllByPks(explode(",",$this->kategorie)));
	}
	return $this->kategorie;
  }

  public function getKategorieName() {
$As = $this->getKategorie();
$V = array();
foreach($As as $A ) {
	$V[] = $A->getName();
}
return implode(", ", $V);
  }

  public function isInKategorie($pk) {
		foreach($this->getKategorie() as $one) {
			if($one->getPk()==$pk) return true;
		}
		return false;
  }

/**
 * @return \Kategorien\Model\KategorieModel[]
 */
  public function getAllPossibleKategorie() {
		$kategorieRepository = new \Kategorien\Repository\KategorieRepository();
		return $kategorieRepository->findAll();
  }

  /**
  * @param int $bereich
  */
  public function setBereich($bereich) {
      $this->bereich = $bereich;
  }

  /**
 * @return \Kategorien\Model\KategorieModel
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
		foreach($this->getBereich() as $one) {
			if($one->getPk()==$pk) return true;
		}
		return false;
  }

/**
 * @return \Bereiche\Model\BereichModel[]
 */
  public function getAllPossibleBereich() {
		$bereichRepository = new \Bereiche\Repository\BereichRepository();
		$bereichRepository->addWhere("be_bu_fk='".me()->getPk()."' ");
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
if($this->rechner=="") { $this->rechner=array();return array();}
		$rechnerRepository = new \Rechner\Repository\RechnerRepository();
		$this->setRechner($rechnerRepository->findAllByPks(explode(",",$this->rechner)));
	}
	return $this->rechner;
  }

  public function getRechnerKind() {
$As = $this->getRechner();
$V = array();
foreach($As as $A ) {
	$V[] = $A->getKind();
}
return implode(", ", $V);
  }

  public function isInRechner($pk) {
		foreach($this->getRechner() as $one) {
			if($one->getPk()==$pk) return true;
		}
		return false;
  }

/**
 * @return \Rechner\Model\RechnerModel[]
 */
  public function getAllPossibleRechner() {
		$rechnerRepository = new \Rechner\Repository\RechnerRepository();
		$rechnerRepository->addWhere("re_bu_fk='".me()->getPk()."' ");
		return $rechnerRepository->findAll();
  }

  /**
  * @param string $typ
  */
  public function setTyp($typ) {
      $this->typ = $typ;
  }

  /**
  * @return string
  */
  public function getTyp() {
	return $this->typ;
  }

  /**
  * @param string $befehl
  */
  public function setBefehl($befehl) {
      $this->befehl = $befehl;
  }

  /**
  * @return string
  */
  public function getBefehl() {
	return $this->befehl;
  }



	/**
	 * @param string $hosts
	 */
	public function setHosts($hosts) {
		$this->hosts = $hosts;
	}
	/**
	 * @return string
	 */
	public function getHosts() {
		return $this->hosts;
	}

} // fastfwModel
?>