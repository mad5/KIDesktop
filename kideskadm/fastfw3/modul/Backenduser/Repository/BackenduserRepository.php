<?php

namespace Backenduser\Repository;

/**
 * Repository der Backendbenutzer
 */
class BackenduserRepository extends \classes\AbstractRepository {

	/**
	 * @var string
	 */
	protected $table = 'backenduser';

	/**
	 * @var string
	 */
	protected $model = '\Backenduser\Model\BackenduserModel';

	/**
	 * @var string
	 */
	protected $prefix = 'bu';

	/**
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->setOrderBy('bu_username');
		$this->setSearchFields(Array('bu_username', 'bu_firstname', 'bu_lastname', 'bu_email'));
	}	

	/**
	 * @param string $username
	 *
	 * @return \Backenduser\Model\BackenduserModel
	 */
	public function findByUsername($username) {
		#echo $username;exit;
		$query = "SELECT * " .
			"FROM backenduser " .
			"WHERE bu_username = '" . $this->fw->DC->sql_escape($username) . "' " .
			$this->enrichEntryQuery();
		$query = str_Replace("\\\\", "\\", $query);
		#echo $query;exit;
		$dbData = $this->fw->DC->getByQuery($query);
		#var_dump($dbData);exit;
		if (!is_array($dbData)) {
			return NULL;
		}
		$backenduser = new \Backenduser\Model\BackenduserModel($dbData);

		return $backenduser;
	}


	/**
	 * @param integer $pk
	 *
	 * @return boolean
	 */
	public function deleteByPk($pk) {
		$query = "UPDATE backenduser " .
			"SET bu_changedate = '" . now() . "', " .
			"bu_deleted = 1 " .
			"WHERE bu_pk = " . (int)$pk . " ";

		return $this->fw->DC->sendQuery($query);
	}

	/**
	 * @param Array $data
	 *
	 * @return integer
	 */
	public function insert(Array $data, $hasBaseData = TRUE) {
		$dbData = Array(
			'bu_createdate' => now(),
			'bu_changedate' => now(),
			'bu_deleted'    => 0,
			'bu_hidden'     => 0,
			'bu_username'   => $data['bu_username'].'',
			'bu_password'   => $data['bu_password'].'',
			'bu_firstname'  => $data['bu_firstname'].'',
			'bu_lastname'   => $data['bu_lastname'].'',
			'bu_email'      => $data['bu_email'].'',
			'bu_admin'      => (int)$data['bu_admin'],
		);

		$pk = (int)$this->fw->DC->insert($dbData, 'backenduser');

		if ($pk > 0 && is_array($data['bu_roles']) && count($data['bu_roles']) > 0) {
			$this->insertRolesForUser($data['bu_roles'], $pk);
		}

		return $pk;
	}

	/**
	 * @param Array   $data
	 * @param integer $pk
	 *
	 * @return integer
	 */
	public function update(array $data, $pk, $hasBaseData = TRUE) {
		$dbData = Array(
			'bu_changedate' => now(),
			'bu_username'   => $data['bu_username'],
			'bu_firstname'  => $data['bu_firstname'],
			'bu_lastname'   => $data['bu_lastname'],
			'bu_email'      => $data['bu_email'],
			'bu_admin'      => (int)$data['bu_admin'],
		);

		$this->fw->DC->update($dbData, 'backenduser', $pk, 'bu_pk');

		$query = "DELETE FROM backenduser_backendrole_nm WHERE bu_fk = " . (int)$pk . " ";
		$this->fw->DC->sendQuery($query);

		if (is_array($data['bu_roles']) && count($data['bu_roles']) > 0) {
			$this->insertRolesForUser($data['bu_roles'], (int)$pk);
		}

		return $pk;
	}

	public function setNewPassword($pw, $pk) {
		$dbData = Array(
				'bu_changedate' => now(),
				"bu_password" => md5(USER_SECRET.$pw)
		);
		$this->fw->DC->update($dbData, 'backenduser', $pk, 'bu_pk');
	}

	public function testPassword($pw, $pk) {
		$data = $this->getDataByPk($pk);
		#vd($data);
		#vd($pw);
		#vd(md5(USER_SECRET.$pw));
		#exit;
		return ($data["bu_password"]===md5(USER_SECRET.$pw));
	}

	/**
	 * @param Array   $rolePks
	 * @param integer $userPk
	 *
	 * @return void
	 */
	public function insertRolesForUser(Array $rolePks, $userPk) {
		if ((int)$userPk > 0) {
			foreach ($rolePks as $rolePk) {
				if ((int)$rolePk > 0) {
					$dbData = Array(
						'bu_fk' => (int)$userPk,
						'br_fk' => (int)$rolePk,
					);
					$this->fw->DC->insert($dbData, 'backenduser_backendrole_nm');
				}
			}
		}
	}

	/**
	 * @param integer $roleTypeFk
	 *
	 * @return Array
	 */
	public function findAllByRoletypeFk($roleTypeFk) {
		$query = "SELECT DISTINCT backenduser.* " .
			"FROM backenduser " .
			"INNER JOIN backenduser_backendrole_nm ON backenduser.bu_pk = backenduser_backendrole_nm.bu_fk " .
			"INNER JOIN backendrole ON backenduser_backendrole_nm.br_fk = backendrole.br_pk " .
			"WHERE 1 = 1 " .
			"AND br_roletype_fk = " . (int)$roleTypeFk . " " .
			"AND bu_deleted = 0 " .
			"AND bu_hidden = 0 " .
			"AND br_deleted = 0 " .
			"AND br_hidden = 0 " .
			"ORDER BY bu_username ASC ";
		#echo $query;
		$list = $this->fw->DC->getAllByQuery($query);

		$experts = Array();
		foreach ($list as $entry) {
			$experts[(int)$entry['bu_pk']] = new \Backenduser\Model\BackenduserModel($entry);
		}

		return $experts;
	}

	public function countAllWithoutRole() {
		$Q = " SELECT count(*) FROM backenduser
 				LEFT JOIN backenduser_backendrole_nm ON backenduser.bu_pk = backenduser_backendrole_nm.bu_fk
 				WHERE br_fk IS NULL AND bu_admin=0 AND bu_disable=0 AND bu_deleted=0 AND bu_hidden=0 AND bu_norights=1
			";

		return $this->fw->DC->countByQuery($Q);
	}

	/**
	 * @return Array
	 */
	public function findAllEmployees() {
		return $this->findAllByRoletypeFk(1);
	}

	/**
	 * @return Array
	 */
	public function findAllExamLeaders() {
		return $this->findAllByRoletypeFk(2);
	}

	/**
	 * @return Array
	 */
	public function findAllExperts() {
		return $this->findAllByRoletypeFk(3);
	}

	public function findAllProductManager() {
		return $this->findAllByRoletypeFk(4);
	}

	public function findAllAuthors() {
		return $this->findAllByRoletypeFk(5);
	}




	public function checkLogin($username, $password) {
		if(trim($username)=="" || trim($password)=="") return NULL;
		$query = "SELECT * " .
				"FROM backenduser " .
				"WHERE bu_username = '" . addslashes($username) . "' AND bu_password = '" . md5(USER_SECRET.$password) . "' " .
				$this->enrichEntryQuery();
		#vd($query);exit;

		$dbData = $this->fw->DC->getByQuery($query);
		#vd($dbData);exit;
		if (!is_array($dbData)) {
			return NULL;
		}
		$backenduser = new \Backenduser\Model\BackenduserModel($dbData);

		return $backenduser;

	}

}

?>