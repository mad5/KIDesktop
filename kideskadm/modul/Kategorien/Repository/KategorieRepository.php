<?php

namespace Kategorien\Repository;

/**
 */
class KategorieRepository extends \classes\AbstractRepository {

	/**
	 * @var string
	 */
	protected $table = 'kategorie';

	/**
	 * @var string
	 */
	protected $model = '\Kategorien\Model\KategorieModel';

	/**
	 * @var string
	 */
	protected $prefix = 'ka';

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