<?php
namespace Mailkontakt\Model;

class MailkontaktModel extends \classes\AbstractModel {

	protected $prefix = "mk";

  protected $name = '';
  protected $email = '';
  protected $rechner = 0;
  protected $hash = '';
	protected $bild = '';
	protected $bu_fk;

public function setData(Array $data) {
		parent::setData($data);

     $this->setName($data["mk_name"]);
     $this->setEmail($data["mk_email"]);
     $this->setRechner($data["mk_rechner"]);
     $this->setHash($data["mk_hash"]);
		$this->setBild($data["mk_bild"]);
     $this->setBu_fk($data["mk_bu_fk"]);

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
  * @param string $hash
  */
  public function setHash($hash) {
      $this->hash = $hash;
  }

  /**
  * @return string
  */
  public function getHash() {
	return $this->hash;
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
  * @param string $email
  */
  public function setEmail($email) {
      $this->email = $email;
  }

  /**
  * @return string
  */
  public function getEmail() {
	return $this->email;
  }

  /**
  * @param int $rechner
  */
  public function setRechner($rechner) {
      $this->rechner = $rechner;
  }

  /**
 * @return \\Model\Model[]
  */
  public function getRechner() {
	if (!is_array($this->rechner)) {
if($this->rechner=="") { $this->rechner=array();return array();}
		$rechnerRepository = new \Rechner\Repository\RechnerRepository();
		$rechnerRepository->addWhere("re_bu_fk='".me()->getPk()."' ");
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
	 * @param string $bild
	 */
	public function setBild($bild) {
		$this->bild = $bild;
	}
	/**
	 * @return string
	 */
	public function getBild() {
			return new \classes\UploadFile($this->bild);
	}
	
	public function anzahlUngelesene($rechner) {
			$nachrichtRepository = new \Nachrichten\Repository\NachrichtRepository();
			$nachrichtRepository->addWhere("na_sender='kontakt'");
			$nachrichtRepository->addWhere("na_mailkontakt='".$this->getPk()."'");
			$nachrichtRepository->addWhere("na_rechner='".$rechner->getPk()."'");
			$nachrichtRepository->addWhere("na_gelesen='"._DATE0."'");
			$c = $nachrichtRepository->countAll();
			return $c;
	}

} // fastfwModel
?>