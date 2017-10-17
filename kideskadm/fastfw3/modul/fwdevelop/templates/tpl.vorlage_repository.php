<?php

namespace ##MODNAME##\Repository;

/**
 */
class ##MODELNAME##Repository extends \classes\AbstractRepository {

	/**
	 * @var string
	 */
	protected $table = '##TABELLE##';

	/**
	 * @var string
	 */
	protected $model = '\##MODNAME##\Model\##MODELNAME##Model';

	/**
	 * @var string
	 */
	protected $prefix = '##PREFIX##';

	/**
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		#$this->setOrderBy($this->getPrefix().'_name');
		#$this->setSearchFields(array($this->getPrefix().'_name'));
	}

}

?>