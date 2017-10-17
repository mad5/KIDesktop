<?php

namespace Eintrag\Repository;

/**
 */
class EintragRepository extends \classes\AbstractRepository {

	/**
	 * @var string
	 */
	protected $table = 'eintrag';

	/**
	 * @var string
	 */
	protected $model = '\Eintrag\Model\EintragModel';

	/**
	 * @var string
	 */
	protected $prefix = 'ei';

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