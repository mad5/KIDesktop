<?php

namespace Backenduser\Repository;

/**
 * Repository der Rollen der Backendbenutzer
 */
class RoleRepository extends \classes\AbstractRepository {

	/**
	 * @var string
	 */
	protected $table = 'backendrole';

	/**
	 * @var string
	 */
	protected $model = '\Backenduser\Model\RoleModel';

	/**
	 * @var string
	 */
	protected $prefix = 'br';

	/**
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->setOrderBy('br_name');
		$this->setSearchFields(Array('br_name'));
	}

	/**
	 * @return Array
	 */
	public function old_findAll() {
		$query = "SELECT * FROM backendrole WHERE 1 = 1 ".$this->enrichListQuery();
		$list = $this->fw->DC->getAllByQuery($query);
		$this->foundRows = $this->fw->DC->foundRows;

		$objs = Array();
		foreach ($list as $entry) {
			$objs[(int)$entry['br_pk']] = new \Backenduser\Model\RoleModel($entry);
		}

		return $objs;
	}

	/**
	 * @param integer $pk
	 * @return  \Backenduser\Model\RoleModel|NULL
	 */
	public function findByPk($pk) {
		$Q = "SELECT * FROM backendrole WHERE br_pk='" . (int)$pk . "' " . $this->enrichEntryQuery();

		$dbData = $this->fw->DC->getByQuery($Q);
		if ($dbData == "") {
			return NULL;
		}

		$role = new \Backenduser\Model\RoleModel($dbData);

		return $role;
	}

	/**
	 * @param integer $pk
	 * @return void
	 */
	public function deleteByPk($pk) {
		$Q = "UPDATE backendrole SET br_changedate='" . now() . "', br_deleted=1 WHERE br_pk='" . (int)$pk . "' ";
		$this->fw->DC->sendQuery($Q);
	}

	/**
	 * @param Array $data
	 * @return integer
	 */
	public function insert(array $data , $hasBaseData = TRUE) {
		$dbData = Array(
			"br_createdate"  => now(),
			"br_changedate"  => now(),
			"br_deleted"     => 0,
			"br_hidden"      => 0,
			"br_name"        => $data["br_name"],
			"br_description" => $data["br_description"],
			"br_roletype_fk" => $data["br_roletype_fk"],
		);

		$br_pk = $this->fw->DC->insert($dbData, "backendrole");

		return $br_pk;
	}

	/**
	 * @param Array $data
	 * @param integer $pk
	 * @return integer
	 */
	public function update(array $data, $pk, $hasBaseData = TRUE) {
		$dbData = Array(
			"br_changedate"  => now(),
			"br_name"        => $data["br_name"],
			"br_description" => $data["br_description"],
			"br_roletype_fk" => $data["br_roletype_fk"],
		);

		$this->fw->DC->update($dbData, "backendrole", $pk, "br_pk");

		return $pk;
	}

	/**
	 * @param \Backenduser\Model\BackenduserModel $backenduser
	 * @return Array
	 */
	public function findAllByBackenduser(\Backenduser\Model\BackenduserModel $backenduser) {
		$query = "SELECT * ".
					"FROM backendrole ".
						"INNER JOIN backenduser_backendrole_nm ON backendrole.br_pk = backenduser_backendrole_nm.br_fk ".
					"WHERE 1 = 1 ". 
						"AND bu_fk = ".(int)$backenduser->getPk()." ".
						"AND br_deleted = 0 ".
						"AND br_hidden = 0 ".
					"ORDER BY br_name ASC ";
		$list = $this->fw->DC->getAllByQuery($query);
#vd($query);vd($list);exit;
		$roles = Array();
		foreach ($list as $entry) {
			$roles[(int)$entry['br_pk']] = new \Backenduser\Model\RoleModel($entry);
		}

		return $roles;
	}

}

?>