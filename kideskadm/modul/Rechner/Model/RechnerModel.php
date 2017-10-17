<?php
namespace Rechner\Model;

class RechnerModel extends \classes\AbstractModel {

	protected $prefix = "re";

  protected $kind = '';
  protected $ort = '';
  protected $beschreibung = '';
  protected $letzteip = '';
  protected $zuletztonline = null;
  protected $offlineab = 0;
  protected $offlinebis = 0;
  protected $ausab = 0;
  protected $ausbis = 0;
  protected $nutzungsdauerinsgesamt = 0;
  protected $bu_fk;
	protected $hash = '';
	protected $bild = '';

public function setData(Array $data) {
		parent::setData($data);

     $this->setKind($data["re_kind"]);
     $this->setOrt($data["re_ort"]);
     $this->setBeschreibung($data["re_beschreibung"]);
     $this->setLetzteip($data["re_letzteip"]);
     $this->setZuletztonline($data["re_zuletztonline"]);
     $this->setOfflineab($data["re_offlineab"]);
     $this->setOfflinebis($data["re_offlinebis"]);
     $this->setAusab($data["re_ausab"]);
     $this->setAusbis($data["re_ausbis"]);
     $this->setNutzungsdauerinsgesamt($data["re_nutzungsdauerinsgesamt"]);
     $this->setBu_fk($data["re_bu_fk"]);
		$this->setHash($data["re_hash"]);
		$this->setBild($data["re_bild"]);

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
  * @param string $kind
  */
  public function setKind($kind) {
      $this->kind = $kind;
  }

  /**
  * @return string
  */
  public function getKind() {
	return $this->kind;
  }

  /**
  * @param string $ort
  */
  public function setOrt($ort) {
      $this->ort = $ort;
  }

  /**
  * @return string
  */
  public function getOrt() {
	return $this->ort;
  }

  /**
  * @param string $beschreibung
  */
  public function setBeschreibung($beschreibung) {
      $this->beschreibung = $beschreibung;
  }

  /**
  * @return string
  */
  public function getBeschreibung() {
	return $this->beschreibung;
  }

  /**
  * @param string $letzteip
  */
  public function setLetzteip($letzteip) {
      $this->letzteip = $letzteip;
  }

  /**
  * @return string
  */
  public function getLetzteip() {
	return $this->letzteip;
  }

  /**
  * @param string $zuletztonline
  */
  public function setZuletztonline($zuletztonline) {
      $this->zuletztonline = $zuletztonline;
  }

  /**
  * @return string
  */
  public function getZuletztonline() {
	return $this->zuletztonline;
  }

  /**
  * @param int $offlineab
  */
  public function setOfflineab($offlineab) {
      $this->offlineab = $offlineab;
  }

  /**
  * @return int
  */
  public function getOfflineab() {
	return $this->offlineab;
  }

  /**
  * @param int $offlinebis
  */
  public function setOfflinebis($offlinebis) {
      $this->offlinebis = $offlinebis;
  }

  /**
  * @return int
  */
  public function getOfflinebis() {
	return $this->offlinebis;
  }

  /**
  * @param int $ausab
  */
  public function setAusab($ausab) {
      $this->ausab = $ausab;
  }

  /**
  * @return int
  */
  public function getAusab() {
	return $this->ausab;
  }

  /**
  * @param int $ausbis
  */
  public function setAusbis($ausbis) {
      $this->ausbis = $ausbis;
  }

  /**
  * @return int
  */
  public function getAusbis() {
	return $this->ausbis;
  }

  /**
  * @param int $nutzungsdauerinsgesamt
  */
  public function setNutzungsdauerinsgesamt($nutzungsdauerinsgesamt) {
      $this->nutzungsdauerinsgesamt = $nutzungsdauerinsgesamt;
  }

  /**
  * @return int
  */
  public function getNutzungsdauerinsgesamt() {
	return $this->nutzungsdauerinsgesamt;
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

} // fastfwModel
?>