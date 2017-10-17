<?php
namespace Bereiche\Model;

class BereichModel extends \classes\AbstractModel {

	protected $prefix = "be";

  protected $name = '';
  protected $icon = null;
  protected $reihenfolge = 0;
  protected $freigegeben = 0;


	public function setData(Array $data) {
		parent::setData($data);

     $this->setName($data["be_name"]);
     $this->setIcon($data["be_icon"]);
     $this->setReihenfolge($data["be_reihenfolge"]);
     $this->setFreigegeben($data["be_freigegeben"]);
     $this->setBu_fk($data["be_bu_fk"]);
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
  * @param int $reihenfolge
  */
  public function setReihenfolge($reihenfolge) {
      $this->reihenfolge = $reihenfolge;
  }

  /**
  * @return int
  */
  public function getReihenfolge() {
	return $this->reihenfolge;
  }

  /**
  * @param int $freigegeben
  */
  public function setFreigegeben($freigegeben) {
      $this->freigegeben = $freigegeben;
  }

  /**
  * @return int
  */
  public function getFreigegeben() {
	return $this->freigegeben;
  }



} // fastfwModel
?>