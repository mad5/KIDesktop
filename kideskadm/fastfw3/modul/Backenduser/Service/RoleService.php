<?php

namespace Backenduser\Service;

class RoleService extends \classes\AbstractService {

	static public function hasRole($bra_id) {
		return $bra_id;
	}

	static public function hasRoleType($type) {
		/**
		 * @var $role \Backenduser\Model\RoleModel
		 */

		if(\Backenduser\Service\ActiveBackenduserService::isAdmin()) return true;

		$allRoles = \Backenduser\Service\ActiveBackenduserService::getUser()->getRoles();
		foreach($allRoles as $role) {
			if($role==NULL) return FALSE;
			if($role->getRoletype()==NULL) return FALSE;
			if($role->getRoletype()->getPk()==$type) {
				return true;
			}
		}
	}

	static public function getRoleTypes() {
		$whatRoles = array();
		if (\Backenduser\Service\RoleService::hasRoleType(\Backenduser\Repository\RoletypeRepository::ROLETYPE_PRODUKTVERANTWORTLICHER) ) {
			$whatRoles[] = array("title" => "Produktverantwortlicher", "type" => \Backenduser\Repository\RoletypeRepository::ROLETYPE_PRODUKTVERANTWORTLICHER);
		}
		if (\Backenduser\Service\RoleService::hasRoleType(\Backenduser\Repository\RoletypeRepository::ROLETYPE_PRUEFUNGSMANAGEMENT) ) {
			$whatRoles[] = array("title" => "Prüfungsmanagement", "type" => \Backenduser\Repository\RoletypeRepository::ROLETYPE_PRUEFUNGSMANAGEMENT);
		}
		if (\Backenduser\Service\RoleService::hasRoleType(\Backenduser\Repository\RoletypeRepository::ROLETYPE_PRUEFUNGSAUFSICHT) ) {
			$whatRoles[] = array("title" => "Prüfungsaufsicht", "type" => \Backenduser\Repository\RoletypeRepository::ROLETYPE_PRUEFUNGSAUFSICHT);
		}
		if (\Backenduser\Service\RoleService::hasRoleType(\Backenduser\Repository\RoletypeRepository::ROLETYPE_EXPERTE) ) {
			$whatRoles[] = array("title" => "Experte", "type" => \Backenduser\Repository\RoletypeRepository::ROLETYPE_EXPERTE);
		}if (\Backenduser\Service\RoleService::hasRoleType(\Backenduser\Repository\RoletypeRepository::ROLETYPE_FRAGENAUTOR) ) {
			$whatRoles[] = array("title" => "Fragenautor", "type" => \Backenduser\Repository\RoletypeRepository::ROLETYPE_FRAGENAUTOR);
		}
		return $whatRoles;
	}

	static public function getActiveRoleType() {
		$activeRoleType = getS("activeRoleType");
		if($activeRoleType=="") {
			$roleTypes = self::getRoleTypes();
			$activeRoleType = $roleTypes[0];
			setS("activeRoleType", $activeRoleType);
		}
		return $activeRoleType["type"];
	}

	static public function setActiveRoleType($mytype) {
		$roleTypes = self::getRoleTypes();
		foreach($roleTypes as $roletype) {
			if($roletype["type"]==$mytype) {
				setS("activeRoleType", $roletype);
				return;
			}
		}
	}

}

?>