<?php
namespace classes;

/**
 */
class RepositoryFactory {

	/**
	 * @param string $module
	 * @param string $repository
	 * @return \classes\AbstractRepository
	 */
	static function create($module, $repository = '') {
		if ($repository == '') {
			$repository = $module;
		}
		$classname = "\\".$module."\\Repository\\".$repository."Repository";

		return new $classname();
	}

}
?>