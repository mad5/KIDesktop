<?php
namespace classes;

/**
 */
class ModelFactory {

	/**
	 * @param string $module
	 * @param string $model
	 * @return \classes\AbstractModel
	 */
	static function create($module, $model = '') {
		if ($model == '') {
			$model = $module;
		}
		$classname = "\\".$module."\\Model\\".$model."Model";

		return new $classname();
	}

}
?>