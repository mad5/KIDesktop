<?php

namespace Backenduser\Service;

/**
 */
class AccessService extends \classes\AbstractService {

	/**
	 * @const string
	 */
	const READ_IDENTIFIER = 'read';

	/**
	 * @const string
	 */
	const WRITE_IDENTIFIER = 'write';

	/**
	 * @param \classes\AbstractModel $model
	 * @return boolean
	 */
	static public function hasReadAccessByModel($model) {
		if (!$model) {
			return false;
		}

		return static::hasAccessByModelAndType($model, static::READ_IDENTIFIER);
	}

	/**
	 * @param \classes\AbstractModel $model
	 * @return boolean
	 */
	static public function hasWriteAccessByModel($model) {
		if (!$model) {
			return false;
		}

		return static::hasAccessByModelAndType($model, static::WRITE_IDENTIFIER);
	}

	/**
	 * @param string $modelName
	 * @return boolean
	 */
	static public function hasReadAccessByModelname($modelName) {
		return static::hasAccessByModelnameAndType($modelName, static::READ_IDENTIFIER);
	}

	/**
	 * @param string $modelName
	 * @return boolean
	 */
	static public function hasWriteAccessByModelname($modelName) {
		return static::hasAccessByModelnameAndType($modelName, static::WRITE_IDENTIFIER);
	}

	/**
	 * @param string $roleAreaId
	 * @return boolean
	 */
	static public function hasReadAccessByArea($roleAreaId) {
		return static::hasAccessByAreaAndType($roleAreaId, static::READ_IDENTIFIER);
	}

	/**
	 * @param string $roleAreaId
	 * @return boolean
	 */
	static public function hasWriteAccessByArea($roleAreaId) {
		return static::hasAccessByAreaAndType($roleAreaId, static::WRITE_IDENTIFIER);
	}

	/**
	 * @param \classes\AbstractModel $model
	 * @param string $type (read|write)
	 * @return boolean
	 */
	static public function hasAccessByModelAndType($model, $type) {
		if (!$model) {
			return false;
		}

		return static::hasAccessByModelnameAndType(get_class($model), $type);
	}

	/**
	 * @param string $modelName
	 * @param string $type (read|write)
	 * @return boolean
	 */
	static public function hasAccessByModelnameAndType($modelName, $type) {
		$user = \Backenduser\Service\ActiveBackenduserService::getUser();
		if ($user->isAdmin()) {
			return true;
		}

		$expl = explode('\\', $modelName);
		$roleAreaId = $expl[(count($expl) - 1)];
		if (substr($roleAreaId, -5) == 'Model') {
			$roleAreaId = substr($roleAreaId, 0, -5);
		}
		if ($roleAreaId != '') {
			return static::hasAccessByAreaAndType($roleAreaId, $type);
		}

		return false;
	}

	/**
	 * @param string $roleAreaId
	 * @param string $type (read|write)
	 * @return boolean
	 */
	static public function hasAccessByAreaAndType($roleAreaId, $type) {
		$user = \Backenduser\Service\ActiveBackenduserService::getUser();
		if ($user->isAdmin()) {
			return true;
		}

		$roleAreaId = strtolower($roleAreaId);
		foreach ($user->getRoles() as $role) {
			foreach ($role->getRoleRights() as $roleRight) {
				if (strtolower($roleRight->getId()) == $roleAreaId) {
					if ($type == static::WRITE_IDENTIFIER && ($roleRight->getOwn() == 'w' || $roleRight->getOther() == 'w')) {
						return true;
					}
					if ($type == static::READ_IDENTIFIER && ($roleRight->getOwn() != '' || $roleRight->getOther() != '')) {
						return true;
					}
					break;
				}
			}
		}

		return false;
	}

	/**
	 * @param string $roleAreaId
	 * @return boolean
	 */
	static public function hasReadAccessForOwnByArea($roleAreaId) {
		$user = \Backenduser\Service\ActiveBackenduserService::getUser();
		if ($user->isAdmin()) {
			return true;
		}

		$roleAreaId = strtolower($roleAreaId);
		foreach ($user->getRoles() as $role) {
			foreach ($role->getRoleRights() as $roleRight) {
				if ($roleRight->getId() == $roleAreaId) {
					if ($roleRight->getOwn() != '') {
						return true;
					}
					break;
				}
			}
		}

		return false;
	}

	/**
	 * @param string $roleAreaId
	 * @return boolean
	 */
	static public function hasReadAccessForOtherByArea($roleAreaId) {
		$user = \Backenduser\Service\ActiveBackenduserService::getUser();
		if ($user->isAdmin()) {
			return true;
		}

		$roleAreaId = strtolower($roleAreaId);
		foreach ($user->getRoles() as $role) {
			foreach ($role->getRoleRights() as $roleRight) {
				if ($roleRight->getId() == $roleAreaId) {
					if ($roleRight->getOther() != '') {
						return true;
					}
					break;
				}
			}
		}

		return false;
	}

	/**
	 * Überprüft, ob der Benutzer nur Rollen besitzt, deren Rollentyp "Mitarbeiter" ist
	 *
	 * @return boolean
	 */
	static public function isOnlyMitarbeiter() {
		return true;
		$user = \Backenduser\Service\ActiveBackenduserService::getUser();
		$roleFound = false;
		foreach ($user->getRoles() as $role) {
			if ($role->getRoletype()->getPk() == \Backenduser\Repository\RoletypeRepository::ROLETYPE_PRUEFUNGSMANAGEMENT) {
				$roleFound = true;
			}
			else {
				return false;
			}
		}

		return $roleFound;
	}

	/**
	 * Überprüft, ob der Benutzer nur Rollen besitzt, deren Rollentyp "Prüfungsleiter" ist
	 *
	 * @return boolean
	 */
	static public function isOnlyPruefungsleiter() {
		return true;
		/*
		$user = \Backenduser\Service\ActiveBackenduserService::getUser();
		$roleFound = false;
		foreach ($user->getRoles() as $role) {
			if ($role->getRoletype()->getPk() == \Backenduser\Repository\RoletypeRepository::ROLETYPE_PRUEFUNGSLEITER) {
				$roleFound = true;
			}
			else {
				return false;
			}
		}
		*/
		return $roleFound;
	}

	/**
	 * Überprüft, ob der Benutzer "Prüfungsleiter" ist
	 *
	 * @return boolean
	 */
	static public function isPruefungsleiter() {
		return true;
		$user = \Backenduser\Service\ActiveBackenduserService::getUser();
		foreach ($user->getRoles() as $role) {
			if ($role->getRoletype()->getPk() == \Backenduser\Repository\RoletypeRepository::ROLETYPE_PRUEFUNGSAUFSICHT) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Überprüft, ob der Benutzer nur Rollen besitzt, deren Rollentyp "Experte" ist
	 *
	 * @return boolean
	 */
	static public function isOnlyExperte() {
		$user = \Backenduser\Service\ActiveBackenduserService::getUser();
		$roleFound = false;
		foreach ($user->getRoles() as $role) {
			if ($role->getRoletype()->getPk() == \Backenduser\Repository\RoletypeRepository::ROLETYPE_EXPERTE) {
				$roleFound = true;
			}
			else {
				return false;
			}
		}

		return $roleFound;
	}

	/**
	 * Überprüft, ob der Benutzer "Experte" ist
	 *
	 * @return boolean
	 */
	static public function isExperte() {
		$user = \Backenduser\Service\ActiveBackenduserService::getUser();
		foreach ($user->getRoles() as $role) {
			if ($role->getRoletype()->getPk() == \Backenduser\Repository\RoletypeRepository::ROLETYPE_EXPERTE) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Überprüft, ob der Benutzer "Mitarbeiter" ist
	 *
	 * @return boolean
	 */
	static public function isMitarbeiter() {
		$user = \Backenduser\Service\ActiveBackenduserService::getUser();
		foreach ($user->getRoles() as $role) {
			if ($role->getRoletype()->getPk() == \Backenduser\Repository\RoletypeRepository::ROLETYPE_PRUEFUNGSMANAGEMENT) {
				return true;
			}
		}
		return false;
	}

	/**
	 *
	 * @return boolean
	 */
	static public function isAdmin() {
		$user = \Backenduser\Service\ActiveBackenduserService::getUser();

		return $user->isAdmin();
	}

}

?>