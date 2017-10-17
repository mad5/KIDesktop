<?php

namespace Backenduser\Service;

/**
 */
class ActiveBackenduserService extends \classes\AbstractService {

	/**
	 * @var \Backenduser\Model\BackenduserModel
	 */
	static protected $backenduser = NULL;

	/**
	 * @param \Backenduser\Model\BackenduserModel $backenduser
	 * @return void
	 */
	static public function login(\Backenduser\Model\BackenduserModel $backenduser) {
		self::$backenduser = $backenduser;
		setS("activeBackenduser", $backenduser->getPk());
	}

	/**
	 * @return void
	 */
	static public function logout() {
		self::$backenduser = NULL;
		setS("activeBackenduser", "");
	}

	/**
	 * @return \Backenduser\Model\BackenduserModel
	 */
	static public function getUser() {
		if (self::$backenduser == NULL) {
			$bu_pk = self::getUserPk();
			if($bu_pk>0) {
				$backenduserRepository = new \Backenduser\Repository\BackenduserRepository();
				self::$backenduser = $backenduserRepository->findByPk($bu_pk);
			}
		}

		return self::$backenduser;
	}

	/**
	 * @return integer
	 */
	static public function getUserPk() {

		return (int)getS("activeBackenduser");
	}




	/**
	 * @return boolean
	 */
	static public function isLoggedIn() {
		return self::getUser() != NULL;
	}

	/**
	 * @return boolean
	 */
	static public function isAdmin() {
		$user = self::getUser();
		return $user->isAdmin();
	}

	static public function hasAnyRoles() {

		if(\Backenduser\Service\ActiveBackenduserService::isAdmin()) return true; // Admin hat immer alle Rollen
		$r = self::getUser()->getRoles();
		return count($r)>0;
	}

}

?>