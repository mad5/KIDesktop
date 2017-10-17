<?php

namespace Backenduser\Repository;

/**
 */
class RoleAreaGroupRepository extends \classes\AbstractRepository {

	/**
	 * @var string
	 */
	protected $table = 'backendroleareagroup';

	/**
	 * @var string
	 */
	protected $model = '\Backenduser\Model\RoleAreaGroupModel';

	/**
	 * @var string
	 */
	protected $prefix = 'brag';

	/**
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

}

?>