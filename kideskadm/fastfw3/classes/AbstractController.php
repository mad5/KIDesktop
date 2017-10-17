<?php

namespace classes;

abstract class AbstractController extends \classes\fastfwController {

	/**
	 * @var \fastfw
	 */
	public $fw;

	/**
	 * Array mit Platzhaltern und zugehörigen Werten für die Ausgabe in Templates
	 *
	 * Beispiel:
	 *
	 * $variables = array(
	 *     'page' => 5,
	 *     'sort' => 'name',
	 * );
	 *
	 * @var array
	 */
	protected $variables = array();

	/**
	 * Pfade und Dateinamen der Templates für die verschiedenen Ansichten wie Liste, Formulare etc.
	 * (z.B. 'Product/tpl.Index.php' für die Listenansicht bei Produkten).
	 *
	 * Beispiel:
	 *
	 * $templates = array(
	 *   'list' => 'Model/tpl.Index.php',
	 *   'form' => 'Model/tpl.Form.php',
	 *   'deleteConfirm' => 'Model/tpl.DeleteConfirm.php',
	 * );
	 *
	 * @var array
	 */
	protected $templates = array();

	/**
	 * FlashMessages für verschiedene Typen (wie z.B. 'success') bei unterschiedlichen Aktionen (z.B. 'insert').
	 *
	 * Beispiel:
	 *
	 * $flashMessages = array(
	 *   'success' => array(
	 *     'insert' => 'Successfully inserted!',
	 *     ...
	 *   ),
	 *   'error' => array(
	 *     ...
	 *   ),
	 * );
	 *
	 * @var array
	 */
	protected $flashMessages = array();

	/**
	 * Konstruktor
	 */
	public function __construct() {

	}

	/**
	 * Fügt für einen Platzhalter im Template den zugehörigen Wert einem Array hinzu. Dieses wird bei der Ausgabe
	 * des Templates durchlaufen und dabei die entsprechenden Platzhalter mit ihren gesetzten Werten ersetzt.
	 *
	 * @param string $name Platzhalter
	 * @param mixed $value Wert
	 */
	public function addVariable($name, $value) {
		$this->variables[$name] = $value;
	}

	/**
	 * Entfernt wieder für einen Platzhalter den Wert aus dem Array.
	 *
	 * @param string $name Platzhalter
	 */
	public function removeVariable($name) {
		unset($this->variables[$name]);
	}

	/**
	 * Leert das Array für die Template-Platzhalter und entfernt alle gesetzten Werte.
	 */
	public function removeAllVariables() {
		$this->variables = array();
	}

	/**
	 * Es werden anhand der ggb. Modul- und Model-Namen Standard-Templates für verschiedene Ansichten wie Liste,
	 * Formular etc. gesetzt. Mit dieser Methode kann man diese Werte überschreiben oder weitere Templates für
	 * beliebige Ansichten festlegen.
	 *
	 * @param string $view     Ansicht, für die das Template festgelegt werden soll (z.B. 'list')
	 * @param string $template Dateiname des Templates (z.B. 'Product/tpl.Index.php')
	 */
	public function setTemplate($view, $template) {
		$this->templates[$view] = $template;
	}

	/**
	 * Es gibt für verschiedene Meldungen Standard-Texte. Mit dieser Methode kann man diese Werte überschreiben oder
	 * weitere FlashMessages festlegen.
	 *
	 * @param string $type    Art der Meldung (z.B. 'success' für Erfolgsmeldungen)
	 * @param string $action  Aktion, die eine Meldung auslöst (z.B. 'insert' für das Anlegen eines Datensatzes)
	 * @param string $message Die eigentliche Meldung, die erscheinen soll
	 */
	public function setFlashMessage($type, $action, $message) {
		$this->flashMessages[$type][$action] = $message;
	}

	/**
	 * Liefert die festgelegte FlashMessage für die ggb. Art und Aktion
	 *
	 * @param string $type   Art der Meldung (z.B. 'success' für Erfolgsmeldungen)
	 * @param string $action Aktion, die eine Meldung auslöst (z.B. 'insert' für das Anlegen eines Datensatzes)
	 *
	 * @return string
	 */
	public function getFlashMessage($type, $action) {
		return $this->flashMessages[$type][$action];
	}
}

?>