<?php

namespace Backenduser\Repository;

/**
 */
class RoleAreaRepository extends \classes\AbstractRepository {

	/**
	 * @var string
	 */
	protected $table = 'backendrolearea';

	/**
	 * @var string
	 */
	protected $model = '\backendrolearea\Model\RoleAreaModel';

	/**
	 * @var string
	 */
	protected $prefix = 'bra';

	/**
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * @param string $id
	 * @return \Backenduser\Model\RoleAreaModel|NULL
	 */
	public function findById($id) {
		$query = "SELECT * ".
					"FROM backendrolearea ".
					"WHERE bra_id = '".(string)$id."' ".
						"AND bra_deleted = 0 ".
						"AND bra_hidden = 0 ";
		$entry = $this->fw->DC->getbyQuery($query);

		return $entry ? new \Backenduser\Model\RoleAreaModel($entry) : NULL;
	}

	/**
	 * @return Array
	 */
	public function findAllFirsLevel() {
		$query = "SELECT backendrolearea.* ".
					"FROM backendrolearea ".
						"INNER JOIN backendroleareagroup ON brag_pk = bra_brag_fk ".
					"WHERE bra_bra_fk = 0 ".
						"AND bra_deleted = 0 ".
						"AND bra_hidden = 0 ".
						"AND brag_deleted = 0 ".
						"AND brag_hidden = 0 ".
					"ORDER BY brag_sort ASC, ".
						"bra_sort ASC ";
		$list = $this->fw->DC->getAllbyQuery($query);

		$objs = Array();
		foreach ($list as $entry) {
			$objs[] = new \Backenduser\Model\RoleAreaModel($entry);
		}

		return $objs;
	}

	/**
	 * @param string $parentId
	 * @return Array
	 */
	public function findChildIdsByParentId($parentId) {
		$parent = $this->findById($parentId);

		$query = "SELECT bra_id ".
					"FROM backendrolearea ".
					"WHERE bra_bra_fk = ".$parent->getPk()." ".
						"AND bra_deleted = 0 ".
						"AND bra_hidden = 0 ";
		$list = $this->fw->DC->getAllbyQuery($query);

		$ids = Array();
		foreach ($list as $entry) {
			$ids[] = $entry['bra_id'];
		}

		return $ids;
	}

}

?>