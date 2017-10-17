<?php

namespace Backenduser\Repository;

/**
 */
class RoleRightRepository extends \classes\AbstractRepository {

	/**
	 * @var string
	 */
	protected $table = 'backendroleright';

	/**
	 * @var string
	 */
	protected $model = '\Backenduser\Model\RoleRightModel';

	/**
	 * @var string
	 */
	protected $prefix = 'brr';

	/**
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * @param \Backenduser\Model\RoleModel $role
	 * @return Array
	 */
	public function findByRole(\Backenduser\Model\RoleModel $role) {
		$query = "SELECT * ".
					"FROM backendroleright ".
					"WHERE brr_br_fk = ".$role->getPk()." ".
						"AND brr_deleted = 0 ".
						"AND brr_hidden = 0 ";
		$entries = $this->fw->DC->getAllByQuery($query);

		$roleRights = Array();
		if (is_array($entries)) {
			foreach ($entries as $entry) {
				$roleRights[(int)$entry['brr_pk']] = new \Backenduser\Model\RoleRightModel($entry);
			}
		}

		return $roleRights;
	}

	/**
	 * @param integer $brPk
	 * @return Array
	 */
	public function findMatrixByRolePk($brPk) {
		$query = "SELECT * ".
					"FROM backendroleright ".
					"WHERE brr_br_fk = ".(int)$brPk." ".
						"AND brr_deleted = 0 ".
						"AND brr_hidden = 0 ";
		$roleRights = $this->fw->DC->getAllByQuery($query);
		$right = Array();
		foreach ($roleRights as $roleRight) {
			$right[$roleRight['brr_id']]['own'] = $roleRight['brr_own'];
			$right[$roleRight['brr_id']]['other'] = $roleRight['brr_other'];
		}

		return $right;
	}

	/**
	 * @param integer $brPk
	 * @param Array $rightsOwn
	 * @param Array $rightsOther
	 * @return void
	 */
	public function updateByRolePk($brPk, $rightsOwn, $rightsOther) {
		$query = "DELETE FROM backendroleright WHERE brr_br_fk = ".(int)$brPk." ";
		$this->fw->DC->sendQuery($query);

		$roleAreaRepository = new \Backenduser\Repository\RoleAreaRepository();
		foreach ($rightsOwn as $id => $right) {
			// Alle Kindelemente holen und die gleichen Rechte für diese vergeben
			$ids = array_merge(Array($id), $roleAreaRepository->findChildIdsByParentId($id));
			foreach ($ids as $brrId) {
				$data = Array(
					'brr_createdate' => now(),
					'brr_changedate' => now(),
					'brr_deleted'    => 0,
					'brr_hidden'     => 0,
					'brr_br_fk'      => (int)$brPk,
					'brr_id'         => (string)$brrId,
					'brr_own'        => (string)$rightsOwn[$id],
					'brr_other'      => (string)$rightsOther[$id],
				);

				$this->fw->DC->insert($data, 'backendroleright');
			}
		}
	}

}

?>