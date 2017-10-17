<?php
namespace classes;

/**
 */
class FormErrorHandler {

	/**
	 * @param string $model
	 * @param string $field
	 * @param string $message
	 * @return void
	 */
	public static function add($model, $field, $message = '') {
		$errors = getS("errors");
		$errors[$model][$field] = array("message" => $message);
		setS("errors", $errors);
	}

	/**
	 * @param string $model
	 * @param string $field
	 * @return boolean
	 */
	public static function has($model, $field) {
		$errors = getS("errors");

		return isset($errors[$model][$field]);
	}

	/**
	 * @param string $model
	 * @param string $field
	 * @return boolean
	 */
	public static function hasMessage($model, $field) {
		$errors = getS("errors");

		return $errors[$model][$field]["message"] != '';
	}

	/**
	 * @param string $model
	 * @param string $field
	 * @return string
	 */
	public static function getMessage($model, $field) {
		$errors = getS("errors");
		$msg = "";
		if (\classes\FormErrorHandler::has($model, $field)) {
			$errors = getS("errors");
			$msg = $errors[$model][$field]["message"];
			unset($errors[$model][$field]);
			setS("errors", $errors);
		}

		return $msg;
	}

}

?>