<?php

namespace Backenduser\Validator;

/**
 */
class BackenduserValidator extends \classes\AbstractValidator {

	/**
	 * @return boolean
	 */
	public function isValid() {
		$isValid = TRUE;
		if (trim($this->data['bu_username']) == '') {
			\classes\FormErrorHandler::add('backenduser', 'bu_username', transFull('validator|Bitte geben Sie einen Loginnamen ein!'));
			$isValid = FALSE;
		}
		if (strlen(trim($this->data['bu_username'])) <= 1) {
			\classes\FormErrorHandler::add('backenduser', 'bu_username', transFull('validator|Der Loginnamen muss mindestens aus zwei Zeichen bestehen!'));
			$isValid = FALSE;
		}
		if (!$isValid) {
			\classes\FlashMessage::add(transFull('validator|Es sind Fehler aufgetreten!'), 'error');
		}

		return $isValid;
	}

}

?>