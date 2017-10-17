<?php
namespace Nachrichten\Model;

class NachrichtModel extends \classes\AbstractModel {

	protected $prefix = "na";

  protected $sender = '';
  protected $mailkontakt = 0;
  protected $rechner = 0;
  protected $nachricht = '';
  protected $uebertragen = null;
  protected $gelesen = null;


	public function setData(Array $data) {
		parent::setData($data);

     $this->setSender($data["na_sender"]);
     $this->setMailkontakt($data["na_mailkontakt"]);
     $this->setRechner($data["na_rechner"]);
     $this->setNachricht($data["na_nachricht"]);
     $this->setUebertragen($data["na_uebertragen"]);
     $this->setGelesen($data["na_gelesen"]);

	}

  /**
  * @param string $sender
  */
  public function setSender($sender) {
      $this->sender = $sender;
  }

  /**
  * @return string
  */
  public function getSender() {
	return $this->sender;
  }

  /**
  * @param int $mailkontakt
  */
  public function setMailkontakt($mailkontakt) {
      $this->mailkontakt = $mailkontakt;
  }

  /**
 * @return \\Model\Model
  */
  public function getMailkontakt() {
	if (!($this->mailkontakt instanceof \Mailkontakt\Model\MailkontaktModel)) {
		$mailkontaktRepository = new \Mailkontakt\Repository\MailkontaktRepository();
		$this->setMailkontakt($mailkontaktRepository->findByPk($this->mailkontakt));
	}
	return $this->mailkontakt;
  }

  public function getMailkontaktName() {
		return $this->getMailkontakt()->getName();
  }

  public function isInMailkontakt($pk) {
		foreach($this->getMailkontakt() as $one) {
			if($one->getPk()==$pk) return true;
		}
		return false;
  }

/**
 * @return \Mailkontakt\Model\MailkontaktModel[]
 */
  public function getAllPossibleMailkontakt() {
		$mailkontaktRepository = new \Mailkontakt\Repository\MailkontaktRepository();
		return $mailkontaktRepository->findAll();
  }

  /**
  * @param int $rechner
  */
  public function setRechner($rechner) {
      $this->rechner = $rechner;
  }

  /**
 * @return \Mailkontakt\Model\MailkontaktModel
  */
  public function getRechner() {
	if (!($this->rechner instanceof \Rechner\Model\RechnerModel)) {
		$rechnerRepository = new \Rechner\Repository\RechnerRepository();
		$this->setRechner($rechnerRepository->findByPk($this->rechner));
	}
	return $this->rechner;
  }

  public function getRechnerKind() {
		return $this->getRechner()->getKind();
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
		return $rechnerRepository->findAll();
  }

  /**
  * @param string $nachricht
  */
  public function setNachricht($nachricht) {
      $this->nachricht = $nachricht;
  }

  /**
  * @return string
  */
  public function getNachricht() {
	return $this->nachricht;
  }

  /**
  * @param string $uebertragen
  */
  public function setUebertragen($uebertragen) {
      $this->uebertragen = $uebertragen;
  }

  /**
  * @return string
  */
  public function getUebertragen() {
	return $this->uebertragen;
  }

  /**
  * @param string $gelesen
  */
  public function setGelesen($gelesen) {
      $this->gelesen = $gelesen;
  }

  /**
  * @return string
  */
  public function getGelesen() {
	return $this->gelesen;
  }



} // fastfwModel
?>