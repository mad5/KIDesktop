<?php

namespace Nachrichten\Repository;

/**
 */
class NachrichtRepository extends \classes\AbstractRepository {

	/**
	 * @var string
	 */
	protected $table = 'nachricht';

	/**
	 * @var string
	 */
	protected $model = '\Nachrichten\Model\NachrichtModel';

	/**
	 * @var string
	 */
	protected $prefix = 'na';

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