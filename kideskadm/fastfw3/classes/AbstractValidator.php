<?php

namespace classes;

/**
 * Class AbstractValidator
 * @package classes
 */
abstract class AbstractValidator {

	/**
	 * Werte (z.B. aus Formulareingaben), deren Gültigkeit geprüft werden soll
	 * @var array
	 */
	protected $data;

	/**
	 * Konstruktor
	 *
	 * @param array $data (optional) Werte, deren Gültigkeit geprüft werden soll
	 * @return void
	 */
	public function __construct($data = array()) {
		if (!empty($data)) {
			$this->data = $data;
		}
	}

	/**
	 * Setzen der Werte, deren Gültigkeit geprüft werden soll
	 *
	 * @param array $data
	 * @return void
	 */
	public function setData($data) {
		$this->data = $data;
	}

	/**
	 * @return array
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * Prüfen, ob die ggb. Werte, die z.B. aus Formulareingaben stammen können, gültig sind.
	 * @return boolean TRUE, wenn die Werte gültig sind, sonst FALSE
	 */
	abstract public function isValid();

}

?>