<?php

namespace Backenduser\Validator;

/**
 */
class RoleValidator extends \classes\AbstractValidator {

	/**
	 * @return boolean
	 */
	public function isValid() {
		$isValid = TRUE;
		if (trim($this->data['br_name'])=="") {
			\classes\FormErrorHandler::add("role", "br_name", transFull('validator|Bitte geben Sie einen Namen ein!'));
			$isValid = FALSE;
		}
		if (!$isValid) {
			\classes\FlashMessage::add(transFull('validator|Es sind Fehler aufgetreten!'), 'error');
		}

		return $isValid;
	}

}

?>