<?php
namespace ##MODNAME##\Model;

class ##MODELNAME##Model extends \classes\AbstractModel {

	protected $prefix = "##PREFIX##";

##VARS##

	public function setData(Array $data) {
		parent::setData($data);

##RUNSETTER##
	}

##SETTERGETTER##

} // fastfwModel
?>