<?php

namespace classes;

/**
 */
class FormViewHelper {

	/**
	 * @param string $model
	 * @param string $field
	 * @return string 
	 */
	static public function showErrorMessage($model, $field) {
		$result = "";
		$msg = \classes\FormErrorHandler::getMessage($model, $field);
		if ($msg != "") {
			$result = "<div>".$msg."</div>";
		}

		return $result;
	}

}

?>