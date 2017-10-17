<?php
namespace classes;

/**
 * Class AbstractCrudController
 * Abstrakter CRUD-Controller mit Basisfunktionalität für das Anlegen, Bearbeiten und Löschen von Objekten sowie die
 * Listenansicht.
 *
 * @package classes
 */
abstract class AbstractCrudController extends AbstractController {

	/**
	 * Name des Moduls, zu dem das Objekt gehört
	 * @var string
	 */
	protected $moduleName;

	/**
	 * Name des Objekt-Models
	 * @var string
	 */
	protected $modelName;

	/**
	 * Prefix des Objekt-Models bei Formular- und DB-Feldern (z.B. 'p' bei 'Product')
	 * @var string
	 */
	protected $prefix;

	/**
	 * Passendes Repository für das Objekt
	 * @var \classes\AbstractRepository
	 */
	protected $repository;

	/**
	 * Passendes Model des Objekts
	 * @var object
	 */
	protected $model;

	/**
	 * Passender Validator für das Objekt
	 * @var object
	 */
	protected $validator;

	/**
	 * Zusätzliche Parameter, die z.B. bei der Form-Action URL oder dem Weiterleiten im Fehlerfall angehangen werden
	 * sollen
	 *
	 * @var array
	 */
	protected $parameters = array();

	/**
	 * FlashMessages für verschiedene Typen (wie z.B. 'success') bei unterschiedlichen Aktionen (z.B. 'insert').
	 *
	 * @var array
	 */
	protected $flashMessages = array(
		'success' => array(
			'insert' => 'Die Daten wurden erfolgreich gespeichert.',
			'update' => 'Die Daten wurden erfolgreich aktualisiert.',
			'delete' => 'Die Daten wurden erfolgreich gelöscht.',
		),
		'error' => array(
			'insert' => 'Die Daten konnten nicht gespeichert werden.',
			'update' => 'Die Daten konnten nicht aktualisiert werden.',
			'delete' => 'Die Daten konnten nicht gelöscht werden.',
		),
	);

	/**
	 * Liste mit Feldbezeichnern, wonach die Spalten in der Listenansicht sortiert werden können.
	 *
	 * Beispiel:
	 *
	 * $sortableColumns = array(
	 *     'name',
	 *     'email',
	 * );
	 *
	 * @var array
	 */
	protected $sortableColumns = array();

	/**
	 * Liste mit Feld- bzw. Spaltenbezeichnern für die Listenansicht.
	 *
	 * Beispiel:
	 *
	 * $listColumns = array(
	 *     0 => array(
	 *         'field' => 'name',
	 *         'title' => 'Name',
	 *     ),
	 * );
	 *
	 * @var array
	 */
	protected $listColumns = array();

	/**
	 * Liste von Spaltenbezeichnern, für die HTML-Tags nicht umgewandelt werden
	 *
	 * @var array
	 */
	protected $htmlEnabledColumns = Array();

	/**
	 * Soll der Button "Neuer Eintrag" ausgeblendet werden?
	 *
	 * @var boolean
	 */
	protected $disableNewButton = FALSE;
	protected $disablePerPageSelect = FALSE;
	protected $disableQuicksearch = FALSE;

	protected $actionNewTitle = "";
	protected $actionNew = "";

	/**
	 * @var boolean
	 */
	protected $disableGroupCommand = FALSE;
	protected $disableLineButtons = FALSE;

	/**
	 * Wenn für die Crud-Ansichten (bisher nur INDEX) ein weiteres Template drumherum gelegt werden soll,
	 * dann hier das Template rein-injecten und Placeholder und Dateinamen setzen.
	*/
	protected $surroundingTemplate = NULL;
	protected $surroundingTemplatePlaceholder = "CONTENT";
	protected $surroundingTemplateFile = "";
	protected $surroundingTemplateEdit = false;
	protected $surroundingTemplateNew = false;

	protected $headButton = array();

	/**
	 * @var Array
	 */
	protected $groupActions = Array(
		'delete' => 'Löschen',
		'copy' => 'Kopieren',
	);

	protected $languageDistinction = false;
	
	/**
	 * Konstruktor
	 */
	public function __construct() {
		if (($GLOBALS["FW"]->QS[1] == "view" || $GLOBALS["FW"]->QS[1] == "edit") && (!isset($GLOBALS["FW"]->QS[2]) || (int)$GLOBALS["FW"]->QS[2] <= 0)) {
			jump2page("*/index");
		}
		parent::__construct();
	}

	/**
	 * Initialisierung des CRUD-Controllers
	 * Es müssen der Name des Moduls, zu dem das Objekt gehört, der Name des Objekt-Models sowie der Prefix
	 * (bei Formular- und DB-Feldern) übergeben werden.
	 *
	 * @param string $moduleName (z.B. 'Product')
	 * @param string $modelName  (z.B. 'Productgroup')
	 * @param string $prefix     (z.B. 'pg')
	 */
	public function initCrud($moduleName, $modelName, $prefix) {
		$this->moduleName = $moduleName;
		$this->modelName = $modelName;
		$this->prefix = $prefix;

		if ($this->modelName == '') {
			throw new \Exception('In der Klasse '.get_class($this).' ist die Eingenschaft "modelName" nicht gesetzt!');
		}

		$this->model = ModelFactory::create($moduleName, $modelName);
		$this->repository = RepositoryFactory::create($moduleName, $modelName);
		$this->validator = ValidatorFactory::create($moduleName, $modelName);

		$this->templates = array(
			'head'          => $modelName . '/tpl.Head.php',
			'list'          => $modelName . '/tpl.Index.php',
			'form'          => $modelName . '/tpl.Form.php',
			'deleteConfirm' => $modelName . '/tpl.DeleteConfirm.php',
			'copyConfirm' => $modelName . '/tpl.CopyConfirm.php',
		);

		// @review (ay) Sortfelder prüfen, ob sie wirklich existieren, (erstmal nicht umsetzen, müßte Struktur der Tabelle geprüft werden)
		$sort = CrudService::getSortSessionData($this->moduleName . "_" . $this->modelName);
		$this->repository->setOrderBy($sort['orderBy']);
		$this->repository->setOrderDir($sort['orderDir']);
	}

	public function addHeadButton($title, $link, $symbol="") {
		$this->headButton[] = array("title" => $title, "link" => $link, "symbol" => $symbol);
	}

	/**
	 * Setzt die Spalten, nach denen eine Listenansicht per Klick auf den Spaltenbezeichner sortiert werden kann
	 *
	 * @param array $columns Spaltennamen nach denen sortiert werden kann
	 * @param string $defaultSortColumn Wenn noch keine Sortierung vorgegeben ist, dann diese verwenden.
	 */
	protected function setSortableColumns(Array $columns, $defaultSortColumn='') {
		$this->sortableColumns = $columns;
		if (getS("crudListSortOrder_" . $this->moduleName . "_" . $this->modelName) == '') {
			setS("crudListSortOrder_" . $this->moduleName . "_" . $this->modelName, "asc");
		}
		if (getS("crudListSort_" . $this->moduleName . "_" . $this->modelName) == '') {
			setS("crudListSort_" . $this->moduleName . "_" . $this->modelName, ($defaultSortColumn!='' ? $defaultSortColumn : $this->sortableColumns[0]));
		}
	}

	/**
	 * Fügt eine der Spalten, die in der Listenansicht angezeigt werden sollen, mit Feldnamen und Titel hinzu
	 *
	 * @param String $field
	 * @param String $title
	 */
	protected function addListColumn($field, $title,$class="") {
		$this->listColumns[] = array(
			"field" => $field,
			"title" => $title,
            "class" => $class,
		);
	}

	/**
	 * @param string $key
	 * @param string $value
	 */
	protected function addParameter($key, $value) {
		$this->parameters[$key] = $key.'='.urlencode($value);
	}

	/**
	 * @return string
	 */
	protected function getParameters() {
		return implode('&', $this->parameters);
	}

	/**
	 * Anzeige einer Liste
	 */
	public function indexAction(array $paramArray=array()) {
#echo "<pre>";
		$tpl = $this->newTpl();
		$tplList = $this->newTpl();

		$perPage = CrudService::getPerPageSessionData($this->moduleName . "_" . $this->moduleName);
		$this->repository->setLimit($perPage);
		$tplList->setVariable("perPage", $perPage);

		$page = CrudService::getPageSessionData($this->moduleName . "_" . $this->modelName);
		$this->repository->setOffset($perPage * $page);
		$tplList->setVariable("page", $page);

		$search = CrudService::getSearchSessionData($this->moduleName . "_" . $this->modelName);
		$this->repository->setSearch($search);
		$tplList->setVariable("search", $search);

		$tpl->setVariable("sort", $this->repository->getOrderBy());
		$tpl->setVariable("order", $this->repository->getOrderDir());

#vd($this->repository->createQuery());exit;
		$list = $this->repository->findAll();
		foreach($list as $key => $one) {
			$list[$key]->controller = $this;
		}
		#vd($this->repository->lastQuery);exit;
		#vd($list);
		#vd($this->fw->DC->lastQuery);
		#vd($this->fw->DC->foundRows);

		$tpl->setVariable("list", $list);
		$tpl->setVariable("quickSearch", $search);

		$tpl->setVariable("foundRows", $this->repository->getFoundRows());
		$tplList->setVariable("foundRows", $this->repository->getFoundRows());

		$tpl->setVariable("listColumns", $this->listColumns);
		$tpl->setVariable("sortableColumns", $this->sortableColumns);

		$tpl->setVariable("disableGroupCommand", $this->disableGroupCommand);
		$tpl->setVariable("disableLineButtons", $this->disableLineButtons);

		$tplList->setVariable('modelName', $this->repository->getModel());
		$tpl->setVariable($this->variables);
		$tplList->setVariable($this->variables);

		$tplList->setVariable("listContent", $tpl->get($this->templates['list']) );

		$tplList->setVariable("disableNewButton", $this->disableNewButton);
		$tplList->setVariable("disablePerPageSelect", $this->disablePerPageSelect);
		$tplList->setVariable("disableQuicksearch", $this->disableQuicksearch);
		$tplList->setVariable("actionNewTitle", (string)$this->actionNewTitle);
		$tplList->setVariable("actionNew", (string)$this->actionNew);
		$tplList->setVariable("disableGroupCommand", $this->disableGroupCommand);
		$tplList->setVariable("disableLineButtons", $this->disableLineButtons);

		$tplList->setVariable("groupActions", $this->groupActions);

		$tplList->setVariable("headButtons", $this->headButton);

		$crudOutput = $this->addHead($this->variables) . $tplList->get("Helper/tpl.crudList.php");

		if($this->surroundingTemplate!==NULL) {
			$this->surroundingTemplate->setVariable($this->variables);
			$this->surroundingTemplate->setVariable($this->surroundingTemplatePlaceholder, $crudOutput);
			$crudOutput = $this->surroundingTemplate->get($this->surroundingTemplateFile);
		}

		$this->fw->setVariable('CONTENT', $crudOutput);
	}

	protected function setSortBy($sortby, $sortOrder="asc") {
		$id = $this->moduleName . "_" . $this->modelName;
		setS("crudListSort_" . $id, $sortby);
		setS("crudListSortOrder_" . $id, $sortOrder);
	}

	protected function setInitialSortBy($sortby, $sortOrder="asc") {
		$id = $this->moduleName . "_" . $this->modelName;
		if(getS("crudListSort_" . $id)=="") {
			setS("crudListSort_" . $id, $sortby);
			setS("crudListSortOrder_" . $id, $sortOrder);
			
			$this->repository->setOrderBy($sortby);
			$this->repository->setOrderDir($sortOrder);
		}
	}

	/**
	 * Liefert die in einer Session gespeicherten Formulardaten als Objekt zurück.
	 * Diese werden beim Wechsel zwischen verschiedenen Aktionen (z.B. Übergang von "insert" zurück zu "new", weil nicht
	 * alle Felder ausgefüllt waren) in der Session zwischengespeichert.
	 *
	 * @return object
	 */
	protected function getFormSessionData() {
		$this->model->clearData();

		$formData = getS($this->prefix . "FormData");
		if ($formData != "") {
			$this->model->setData($formData);
			setS($this->prefix . "FormData", "");
		}

		return $this->model;
	}

	/**
	 * @return mixed
	 */
	protected function getRawFormSessionData() {
		return getS($this->prefix . "FormData");
	}

	/**
	 * Anzeige eines Formulars zum Neuanlegen eines Objekts.
	 */
	public function newAction() {


		$tpl = $this->newTpl();

		$tpl->setVariable("formAction", "insert");
		$tpl->setVariable("formActionParameters", $this->getParameters());

		$tpl->setVariable($this->variables);

		$model = $this->getFormSessionData();

		$this->preNew($tpl, $model);

		$tpl->setVariable(strtolower($this->modelName), $model);

		$crudOutput = $this->addHead() . $tpl->get($this->templates['form']);
		if($this->surroundingTemplateNew && $this->surroundingTemplate!==NULL) {
			$this->surroundingTemplate->setVariable($this->variables);
			$this->surroundingTemplate->setVariable($this->surroundingTemplatePlaceholder, $crudOutput);
			$crudOutput = $this->surroundingTemplate->get($this->surroundingTemplateFile);
		}

		$this->fw->setVariable('CONTENT', $crudOutput);

		$this->fw->setVariable('CONTENT', $crudOutput);
	}

	/**
	 * @param object $tpl
	 * @param object $model
	 */
	protected function preNew($tpl, $model) {

	}

	/**
	 * Schaut selber nach, ob ein Fileupload vorhanden ist, der zum Model-Namen passt.
	 * Dann wird die Datei an die richtige Stelle verschoben und der Pfad+Dateiname in das
	 * $_POST-Array geschrieben.
	 */
	protected function handleFileUploads() {

		if(!isset($_FILES)) return;
		if(!isset($_FILES[strtolower($this->modelName)])) return;

		foreach($_FILES[strtolower($this->modelName)]['name'] as $id => $file) {
			if(isset($_POST[strtolower($this->modelName)][$id.'_remove'])) {
				$_POST[strtolower($this->modelName)][$id] = "";
				unset($_POST[strtolower($this->modelName)][$id.'_remove']);
			}
			if($_FILES[strtolower($this->modelName)]['tmp_name'][$id]=="") continue;
			$_POST[strtolower($this->modelName)][$id] = \classes\FileUtils::moveUploadedFile($_FILES[strtolower($this->modelName)]['tmp_name'][$id], strtolower($this->modelName), $_FILES[strtolower($this->modelName)]['name'][$id]);
		}
	}

	/**
	 * Neuanlegen eines Objekts.
	 * Nach erfolgreicher Validierung der ggb. Formulardaten wird ein Objekt angelegt. Im Fehlerfall werden die
	 * Formulardaten in einer Session zwischengespeichert und zur Anzeige des Formulars zum Neuanlegen eines Objekts
	 * gewechselt, wo eine entsprechende Fehlermeldug ausgegeben wird.
	 *
	 * @see newAction()
	 */
	public function insertAction() {


		$this->handleFileUploads();
		if(isset($_POST[strtolower($this->modelName)]) && is_array($_POST[strtolower($this->modelName)])) {
			foreach ($_POST[strtolower($this->modelName)] as $index => $value) {
				$_POST[strtolower($this->modelName)][$index] = \classes\Utils::prepareForSaving($value, ($this->inHtmlEnabledColumn($index) ? FALSE : TRUE));
			}
		} else {
			$_POST[strtolower($this->modelName)] = array();
		}

		$this->validator->setData($_POST[strtolower($this->modelName)]);
		if ($this->validator->isValid()) {

			$_POST[strtolower($this->modelName)] = $this->preInsert($_POST[strtolower($this->modelName)]);

			$pk = $this->repository->insert($_POST[strtolower($this->modelName)]);


			if($this->getLanguageDistinction()) {
				$this->processLanguageDistinction($pk);
			}

			$_POST[strtolower($this->modelName)] = $this->postInsert((int)$pk, $_POST[strtolower($this->modelName)]);

			\classes\FlashMessage::add($this->getFlashMessage('success', 'insert'), 'success');

			setS('highlightCrudLine', (int)$pk);

			if (isset($_POST["sendback"])) {
				jump2page("*/index");
			} else {
				jump2page("*/edit/" . $pk . ($this->getParameters() != '' ? '&' . $this->getParameters() : ''));
			}
		} else {
			setS($this->prefix . "FormData", $_POST[strtolower($this->modelName)]);
			jump2page("*/new" . ($this->getParameters() != '' ? '&' . $this->getParameters() : ''));
		}
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	protected function preInsert($data) {
		return $data;
	}

	/**
	 * @param int $pk
	 * @param array $data
	 *
	 * @return array
	 */
	protected function postInsert($pk, $data) {

		return $data;
	}

	/**
	 * Anzeige eines Formulars zum Bearbeiten des ggb. Objekts (ID steht in den bei Aufruf der URL mitgegebenen
	 * Parametern).
	 *
	 * @param array $queryArray Bei Aufruf der URL mitgegebene Parameter
	 */
	public function editAction(array $queryArray) {


		$tpl = $this->newTpl();

		if (!isset($queryArray[0]) || (int)$queryArray[0] <= 0) {
			jump2page("*/index");
		}

		if ($formData = getS($this->prefix . "FormData") == "") {
			$model = $this->repository->findByPk($queryArray[0]);
			if(isNullObj($model)) {
				\classes\FlashMessage::add("Datensatz existiert nicht.", "danger");
				jump2page("*");
			}
		} else {
			$model = $this->getFormSessionData();
			$model->setPk((int)$queryArray[0]);
		}

		$tpl->setVariable("formAction", "update");
		$tpl->setVariable("formActionParameters", $this->getParameters());

		$tpl->setVariable($this->variables);

		$this->preEdit($tpl, $model);

		$tpl->setVariable(strtolower($this->modelName), $model);

		$crudOutput = $this->addHead() . $tpl->get($this->templates['form']);
		if($this->surroundingTemplateEdit && $this->surroundingTemplate!==NULL) {
			$this->surroundingTemplate->setVariable($this->variables);
			$this->surroundingTemplate->setVariable($this->surroundingTemplatePlaceholder, $crudOutput);
			$crudOutput = $this->surroundingTemplate->get($this->surroundingTemplateFile);
		}

		$this->fw->setVariable('CONTENT', $crudOutput);
	}

	/**
	 * @param object $tpl
	 * @param object $model
	 */
	protected function preEdit($tpl, $model) {

	}

	/**
	 * Aktualisieren des ggb. Objekts (ID steht in den bei Aufruf der URL mitgegebenen Parametern).
	 * Nach erfolgreicher Validierung der ggb. Formulardaten wird das Objekt aktualisiert. Im Fehlerfall werden die
	 * Formulardaten in einer Session zwischengespeichert und zur Anzeige des Formulars zum Bearbeiten eines Objekts
	 * gewechselt, wo eine entsprechende Fehlermeldug ausgegeben wird.
	 *
	 * @see editAction()
	 *
	 * @param array $queryArray Bei Aufruf der URL mitgegebene Parameter
	 */
	public function updateAction(array $queryArray) {

#vd($_POST);
		$this->handleFileUploads();
		if(is_array($_POST[strtolower($this->modelName)])) {
			foreach ($_POST[strtolower($this->modelName)] as $index => $value) {
				$_POST[strtolower($this->modelName)][$index] = \classes\Utils::prepareForSaving($value, ($this->inHtmlEnabledColumn($index) ? FALSE : TRUE));
			}
			$this->validator->setData($_POST[strtolower($this->modelName)]);
		}
		$testData = $this->repository->findByPk($queryArray[0]);
		if($testData===NULL) {
			\classes\FlashMessage::add("Datensatz existiert nicht.", "danger");
		}
		if ($testData && $this->validator->isValid()) {

			$_POST[strtolower($this->modelName)] = $this->preUpdate($queryArray[0], $_POST[strtolower($this->modelName)]);
			$pk = $this->repository->update((array)$_POST[strtolower($this->modelName)], $queryArray[0]);

			if($this->getLanguageDistinction()) {
				$this->processLanguageDistinction($pk);
			}

			$_POST[strtolower($this->modelName)] = $this->postUpdate($queryArray[0],$_POST[strtolower($this->modelName)]);

			\classes\FlashMessage::add($this->getFlashMessage('success', 'update'), 'success');

			setS('highlightCrudLine', $pk);

			if(isset($_POST["sendandnext"])) {
				jump2page($_POST["sendandnext"].'/'.$pk);
			} else if (isset($_POST["sendback"])) {
				jump2page("*/index");
			} else {
				jump2page("*/edit/" . $pk . ($this->getParameters() != '' ? '&' . $this->getParameters() : ''));
			}
		} else {
			setS($this->prefix . "FormData", $_POST[strtolower($this->modelName)]);
			jump2page("*/edit/" . (int)$queryArray[0] . ($this->getParameters() != '' ? '&' . $this->getParameters() : ''));
		}
		
	}
	
	protected function processLanguageDistinction($pk) {
		$className = "\\".$this->moduleName."\\Repository\\".$this->modelName."langRepository";

		$langRepository = new $className();
		#vd($_POST);vd($_FILES);vd($this->prefix."Lang");exit;

		if(isset($_FILES)) {
			if(isset($_FILES[strtolower($this->modelName)."Lang"])) {
				foreach ($_FILES[strtolower($this->modelName) . "Lang"]['name'] as $lang => $files) {
					foreach($files as $id => $file) {
						if (isset($_POST[strtolower($this->modelName) . "Lang"][$lang][$id . '_remove'])) {
							$_POST[strtolower($this->modelName) . "Lang"][$lang][$id] = "";
							unset($_POST[strtolower($this->modelName) . "Lang"][$lang][$id . '_remove']);
						}
						if ($_FILES[strtolower($this->modelName) . "Lang"]['tmp_name'][$lang][$id] == "") {
							continue;
						}
						$_POST[strtolower($this->modelName) . "Lang"][$lang][$id] = \classes\FileUtils::moveUploadedFile($_FILES[strtolower($this->modelName) . "Lang"]['tmp_name'][$lang][$id], strtolower($this->modelName), $_FILES[strtolower($this->modelName) . "Lang"]['name'][$lang][$id]);
					}
				}
			}
		}


		if(isset($_POST[strtolower($this->modelName)."Lang"])) {
			foreach ($_POST[strtolower($this->modelName)."Lang"] as $lang => $values) {
				$Lpk = $langRepository->getPkByFkAndLang($pk, $lang, $this->prefix);

				$langData = array(
					$langRepository->getPrefix()."_".$this->prefix."_fk" => $pk,
					$langRepository->getPrefix()."_lang" => $lang,
				);
				foreach($values as $key => $val) {
					$langData[$key] = $val;
				}

				if(isNullObj($Lpk)) {
					$langRepository->insert($langData);
				} else {
					$langRepository->update($langData, $Lpk);
				}

			}
		}

	}
	

	public function viewAction(array $queryArray) {

	}

	/**
	 * @param int $pk
	 * @param array $data
	 *
	 * @return array
	 */
	protected function preUpdate($pk, $data) {
		return $data;
	}

	/**
	 * @param int $pk
	 * @param array $data
	 * @return array
	 */
	protected function postUpdate($pk, $data) {
		return $data;
	}

	public function copyConfirmAction($queryArray) {


		$tpl = $this->newTpl();
		$tpl->setVariable($this->variables);
		$tpl->setVariable('multiple', (count(explode(',', $queryArray[0])) > 1 ? 1 : 0));

		$fn = $this->templates['copyConfirm'];
		if (!($fn = $tpl->tplFileExists($fn))) {
			$fn = $tpl->tplFileExists("tpl.CopyConfirm.php");
		}
		$this->fw->setVariable('CONTENT', $this->addHead() . $tpl->get($fn));

	}

	public function copyAction($queryArray) {


		// @review (ay) Sehr unschön, aber ich will, dass beim kaskadierenden Kopieren nur beim ersten Eintrag ein (kopie) beim Namen angehängt wird. (bleibt erstmal so)
		$GLOBALS["copyKaskade"] = 0;

		if (isset($queryArray[0])) {
			$pks = explode(",", $queryArray[0]);
			for ($i = 0; $i < count($pks); $i++) {
				$this->preCopy((int)$pks[$i]);
				$newPk = $this->repository->copyByPk((int)$pks[$i], array(), $this->getLanguageDistinction() );
				$this->postCopy((int)$pks[$i], $newPk);
			}
		}
		jump2page("*/index");
	}

	/**
	 * @param integer $pk
	 * @return void
	 */
	protected function preCopy($pk) {
	}

	/**
	 * wird aufgerufen nachdem eine Eintrag kopiert wurde. Es wird die alte und die neue PK übergeben.
	 * @param integer $pk
	 * @param integer $newPk
	 * @return void
	 */
	protected function postCopy($pk, $newPk) {
	}

	/**
	 * Anzeige einer Bestätigungsaufforderung, dass man wirklich den Löschprozess starten möchte.
	 * @param array $queryArray
	 */
	public function deleteConfirmAction($queryArray) {


		$tpl = $this->newTpl();
		$tpl->setVariable($this->variables);
		$tpl->setVariable('multiple', (count(explode(',', $queryArray[0])) > 1 ? 1 : 0));

		$fn = $this->templates['deleteConfirm'];
		if (!($fn = $tpl->tplFileExists($fn))) {
			$fn = $tpl->tplFileExists("tpl.DeleteConfirm.php");
		}

		$this->fw->setVariable('CONTENT', $this->addHead() . $tpl->get($fn));
	}

	/**
	 * Löschen von Objekten
	 * @param array $queryArray
	 */
	public function deleteAction($queryArray) {


		if (isset($queryArray[0])) {
			$pks = explode(",", $queryArray[0]);
			for ($i = 0; $i < count($pks); $i++) {
				$this->preDelete((int)$pks[$i]);
				$this->repository->deleteByPk((int)$pks[$i]);
				if($this->getLanguageDistinction()) {
					$this->deleteLanguageDistinction((int)$pks[$i]);
				}
				$this->postDelete((int)$pks[$i]);
			}
		}
		jump2page("*/index");
	}

	protected function deleteLanguageDistinction($pk) {
		$className = "\\".$this->moduleName."\\Repository\\".$this->modelName."langRepository";
		$langRepository = new $className();
		$langRepository->deleteByWhere($langRepository->getPrefix()."_".$this->prefix."_fk='".(int)$pk."' ");
	}

	/**
	 * @param integer $pk
	 * @return void
	 */
	protected function preDelete($pk) {
	}

	/**
	 * @param integer $pk
	 * @return void
	 */
	protected function postDelete($pk) {
	}

	/**
	 * Aktion für mehrere gewählte Objekte ausführen
	 */
	public function groupCommandAction() {
		if (isset($_REQUEST["groupAction"]) && $_REQUEST["groupAction"] != "") {
			jump2page("*/".$_REQUEST["groupAction"]."Confirm/" . $_REQUEST["pks"]);
		}
	}

	/**
	 * @return string
	 */
	protected function addHead($addVariables = array()) {
		if (!file_exists(projectPath . '/modul/' . $this->moduleName . '/Templates/' . $this->templates['head'])) {
			return "";
		}
		$tpl = $this->newTpl();

		$tpl->setVariable($addVariables);

		return $tpl->get($this->templates['head']);
	}

	/**
	 * @param string $htmlEnabledColumn
	 * @return void
	 */
	public function addHtmlEnabledColumn($htmlEnabledColumn) {
		$this->htmlEnabledColumns[] = (string)$htmlEnabledColumn;
	}

	/**
	 * @param string $htmlEnabledColumn
	 * @return boolean
	 */
	public function inHtmlEnabledColumn($htmlEnabledColumn) {
		foreach ($this->htmlEnabledColumns as $index => $column) {
			if ($column == (string)$htmlEnabledColumn) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param string $htmlEnabledColumn
	 * @return boolean
	 */
	public function removeHtmlEnabledColumn($htmlEnabledColumn) {
		foreach ($this->htmlEnabledColumns as $index => $column) {
			if ($column == (string)$htmlEnabledColumn) {
				unset($this->htmlEnabledColumns[$index]);
				return true;
			}
		}

		return false;
	}

	/**
	 * @return void
	 */
	public function removeAllHtmlEnabledColumns() {
		$this->htmlEnabledColumns = Array();
	}

	/**
	 * @param Array $htmlEnabledColumns
	 * @return void
	 */
	public function setHtmlEnabledColumns(Array $htmlEnabledColumns) {
		$this->htmlEnabledColumns = $htmlEnabledColumns;
	}

	/**
	 * @return Array
	 */
	public function getHtmlEnabledColumns() {
		return $this->htmlEnabledColumns;
	}

	/**
	 * @param Array $groupActions z.B.: Array('version' => 'Versionieren')
	 * @return void
	 */
	public function addGroupActions(Array $groupActions) {
		$this->groupActions = array_merge($this->groupActions, $groupActions);
	}

	public function setLanguageDistinction($tf) {
		$this->languageDistinction = $tf;
	}
	public function getLanguageDistinction() {
		return $this->languageDistinction;
	}
	
}

?>