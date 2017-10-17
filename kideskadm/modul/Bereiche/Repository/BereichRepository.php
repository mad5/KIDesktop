<?php

namespace Bereiche\Repository;

/**
 */
class BereichRepository extends \classes\AbstractRepository {

	/**
	 * @var string
	 */
	protected $table = 'bereich';

	/**
	 * @var string
	 */
	protected $model = '\Bereiche\Model\BereichModel';

	/**
	 * @var string
	 */
	protected $prefix = 'be';

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