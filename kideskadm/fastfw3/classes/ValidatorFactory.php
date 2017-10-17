<?php
namespace classes;

/**
 */
class ValidatorFactory {

	/**
	 * @param string $module
	 * @param string $validator
	 * @param array $data
	 * @return \classes\AbstractValidator
	 */
	static function create($module, $validator, array $data = array()) {
		if ($validator == '') {
			$validator = $module;
		}
		$classname = "\\".$module."\\Validator\\".$validator."Validator";

		return new $classname($data);
	}

}

?>