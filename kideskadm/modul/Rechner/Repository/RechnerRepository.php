<?php

namespace Rechner\Repository;

/**
 */
class RechnerRepository extends \classes\AbstractRepository {

	/**
	 * @var string
	 */
	protected $table = 'rechner';

	/**
	 * @var string
	 */
	protected $model = '\Rechner\Model\RechnerModel';

	/**
	 * @var string
	 */
	protected $prefix = 're';

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