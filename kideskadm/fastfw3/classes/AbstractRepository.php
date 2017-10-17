<?php

namespace classes;

/**
 * Class AbstractRepository
 * Abstraktes Repository mit Basis-Funktionalität wie z.B. das Setzen von Offset und Limit bei SELECT-Queries etc.
 *
 * @package classes
 */
abstract class AbstractRepository {

	/**
	 * FastFW Objekt
	 *
	 * @var \fastfw
	 */
	protected $fw;

	/**
	 * Datenbank-Tabelle
	 *
	 * @var string
	 */
	protected $table = '';

	/**
	 * Objekt-Model
	 *
	 * @var string
	 */
	protected $model = '';

	/**
	 * Prefix des Objekts bei Formular- und DB-Felder, z.B. 'p' für 'Product'
	 *
	 * @var string
	 */
	protected $prefix = '';

	/**
	 * Flag, ob die Daten historisiert werden sollen
	 *
	 * @var bool
	 */
	protected $historicization = FALSE;

	/**
	 * Max. Anzahl Datensätze, die bei einem SELECT geholt werden sollen
	 *
	 * @var integer
	 */
	protected $limit = 0;

	/**
	 * Nummer des Datensatzes, ab dem bei einem SELECT Datensätze geholt werden sollen
	 *
	 * @var integer
	 */
	protected $offset = 0;

	/**
	 * Feld, nach dem bei einem SELECT sortiert werden soll
	 *
	 * @var string
	 */
	protected $orderBy;

	/**
	 * Sortierrichtung: aufsteigend (ASC) oder absteigend (DESC)
	 *
	 * @var string
	 */
	protected $orderDir = "ASC";

	protected $groupBy = "";

	/**
	 * Flag, das anzeigt, ob auch deaktivierte Datensätze geholt werden sollen (FALSE) oder nicht (TRUE)
	 * @var bool
	 */
	protected $respectHidden = FALSE;

	/**
	 * Flag, das anzeigt, ob auch gelöschte Datensätze geholt werden sollen (FALSE) oder nicht (TRUE)
	 *
	 * @var bool
	 */
	protected $respectDeleted = TRUE;

	/**
	 * @var integer
	 */
	protected $foundRows = 0;

	/**
	 * @var string
	 */
	protected $search = "";

	/**
	 * @var Array
	 */
	protected $searchFields = Array();

	/**
	 * @var Array
	 */
	protected $where = Array();
	protected $entrywhere = Array();

	/**
	 * @var array
	 */
	protected $joins = Array();

	/**
	 * @return void
	 */
	public function __construct() {
		$this->fw = $GLOBALS['FW'];
		if ($this->table == '') {
			throw new \Exception('In der Klasse '.get_class($this).' ist die Eingenschaft "table" nicht gesetzt!');
		}
		if ($this->model == '') {
			throw new \Exception('In der Klasse '.get_class($this).' ist die Eingenschaft "model" nicht gesetzt!');
		}
		/*if ($this->prefix == '') {
			throw new \Exception('In der Klasse '.get_class($this).' ist die Eingenschaft "prefix" nicht gesetzt!');
		}*/
	}

	/**
	 * Liefert für einen SELECT-Query für eine Liste einen Subquery, der mögliche Offset und Limit berücksichtigt
	 *
	 * @return string
	 */
	public function enrichListQuery() {
		$subQuery = $this->enrichEntryQuery();

		if ($this->search != '' && $this->searchFields != array()) {
			$subStrings = explode(" ", $this->search);
			foreach ($subStrings as $str) {
				if (count($this->searchFields) > 1) {
					$subQuery .= " AND CONCAT(" . implode(",", $this->searchFields) . ") LIKE '%" . $this->fw->DC->sql_escape($str) . "%' ";
				} else {
					$subQuery .= " AND " . $this->searchFields[0] . " LIKE '%" . $this->fw->DC->sql_escape($str) . "%' ";
				}
			}
		}

		if (count($this->where) > 0) {
			foreach ($this->where as $where) {
				$subQuery .= " AND (" . $where . ")";
			}
		}
		if ($this->getGroupBy() != '') {
			$subQuery .= " GROUP BY ".$this->getGroupBy()." ";
		}
		if ($this->getOrderBy() != '') {
			$subQuery .= " ORDER BY " . $this->getOrderBy() . " " . $this->getOrderDir() . " ";
		}
		if ($this->getOffset() > 0 || $this->getLimit() > 0) {
			//$subQuery .= " OFFSET " . $this->getOffset() . " ROWS " . ($this->getLimit() > 0 ? "FETCH NEXT " . $this->getLimit() . " ROWS ONLY " : "");
			$subQuery .= " LIMIT " . $this->getOffset() . "," . ($this->getLimit() > 0 ?  $this->getLimit() : "");
		}

		return $subQuery;
	}

	/**
	 * Liefert für einen SELECT-Query für eine Eintrag einen Subquery, der mögliche Flags "gelöscht" und "deaktiviert" berücksichtigt
	 *
	 * @return string
	 */
	public function enrichEntryQuery() {
		$subQuery = "";
		if ($this->isRespectHidden()) {
			$subQuery .= " AND " . $this->getPrefix() . "_hidden = 0 ";
		}
		if ($this->isRespectDeleted()) {
			$subQuery .= " AND " . $this->getPrefix() . "_deleted = 0 ";
		}

		if (count($this->entrywhere) > 0) {
			foreach ($this->entrywhere as $where) {
				$subQuery .= " AND (" . $where . ")";
			}
		}
		
		return $subQuery;
	}

	/**
	 * @param string $str
	 * @return void
	 */
	public function setSearch($str) {
		$this->search = $str;
	}

	/**
	 * @param Array $dbFields
	 * @return void
	 */
	public function setSearchFields(Array $dbFields) {
		$this->searchFields = $dbFields;
	}

	/**
	 * @return integer
	 */
	public function getFoundRows() {
		return $this->foundRows;
	}

	/**
	 * Liefert zurück, wieviele Datensätze max. bei einem SELECT geholt werden sollen
	 *
	 * @return integer
	 */
	public function getLimit() {
		return $this->limit;
	}

	/**
	 * Setzen, wieviele Datensätze max. bei einem SELECT geholt werden sollen
	 *
	 * @param integer $limit
	 * @return void
	 */
	public function setLimit($limit) {
		$this->limit = (int)$limit;
	}

	/**
	 * Liefert zurück, ab dem wievielten Datensätze bei einem SELECT Datensätze geholt werden sollen
	 * @return integer
	 */
	public function getOffset() {
		return $this->offset;
	}

	/**
	 * Setzen, ab dem wievielten Datensätze bei einem SELECT Datensätze geholt werden sollen
	 *
	 * @param integer $offset
	 * @return void
	 */
	public function setOffset($offset) {
		if ($offset < 0) {
			$offset = 0;
		}
		$this->offset = (int)$offset;
	}

	/**
	 * @return string
	 */
	public function getGroupBy() {
		return $this->groupBy;
	}

	/**
	 * @param string $groupBy
	 */
	public function setGroupBy($groupBy) {
		$this->groupBy = $groupBy;
	}



	/**
	 * Liefert zurück, nach welchem Feld sortiert werden soll
	 *
	 * @return string
	 */
	public function getOrderBy() {
		return $this->orderBy;
	}

	/**
	 * Setzen, nach welchem Feld sortiert werden soll
	 *
	 * @param string $orderBy
	 * @return void
	 */
	public function setOrderBy($orderBy, $force=false) {
		if($force==false && stristr($orderBy,"'")) die("Wrong order by! (".$orderBy.")");
		#if(stristr($orderBy," ")) die("Wrong order by! (".$orderBy.")");
		if($orderBy!="") $this->orderBy = $orderBy;
	}

	/**
	 * Liefert zurück, ob auf- (ASC) oder absteigend (DESC) sortiert werden soll
	 *
	 * @return string
	 */
	public function getOrderDir() {
		return $this->orderDir;
	}

	/**
	 * Setzen, ob auf- (ASC) oder absteigend (DESC) sortiert werden soll
	 *
	 * @param string $orderDir
	 * @return void
	 * @throws InvalidArgumentException wenn die Sortierrichtung weder 'ASC' noch 'DESC' ist (Groß-/Kleinschreibung
	 *                                  egal)
	 */
	public function setOrderDir($orderDir) {
		if ($orderDir === '') {
			$orderDir = 'asc';
		}
		if (strtolower($orderDir) != 'asc' && strtolower($orderDir) != 'desc') {
			throw new \InvalidArgumentException('invalid argument');
		}
		$this->orderDir = $orderDir;
	}

	/**
	 * Liefert das Prefix für Formular- und DB-Felder für das Objekt zurück
	 *
	 * @return string
	 */
	public function getPrefix() {
		return $this->prefix;
	}

	/**
	 * Setzen des Prefixs für Formular- und DB-Felder für das Objekt
	 *
	 * @param string $prefix
	 * @return void
	 */
	public function setPrefix($prefix) {
		$this->prefix = $prefix;
	}

	/**
	 * Liefert die Datenbank-Tabelle zurück
	 *
	 * @return string
	 */
	public function getTable() {
		return $this->table;
	}

	/**
	 * Setzen der Datenbank-Tabelle
	 *
	 * @param string $table
	 * @return void
	 */
	public function setTable($table) {
		$this->table = $table;
	}

	/**
	 * Liefert den Model-Bezeichner zurück
	 *
	 * @return string
	 */
	public function getModel() {
		return $this->model;
	}

	/**
	 * Setzen den Model-Bezeichner
	 *
	 * @param string $model
	 * @return void
	 */
	public function setModel($model) {
		$this->model = $model;
	}

	/**
	 * @return boolean
	 */
	public function hasHistoricization() {
		return $this->historicization;
	}

	/**
	 * @param boolean $historicization
	 * @return void
	 */
	public function setHistoricization($historicization) {
		$this->historicization = $historicization;
	}

	/**
	 * Liefert zurück, ob gelöschte Datensätze bei SELECTs auch geholt werden sollen (FALSE) oder nicht (TRUE)
	 *
	 * @return boolean
	 */
	public function isRespectDeleted() {
		return $this->respectDeleted;
	}

	/**
	 * Setzen, ob gelöschte Datensätze bei SELECTs auch geholt werden sollen (FALSE) oder nicht (TRUE)
	 *
	 * @param boolean $respectDeleted
	 * @return void
	 */
	public function setRespectDeleted($respectDeleted) {
		$this->respectDeleted = $respectDeleted;
	}

	/**
	 * Liefert zurück, ob deaktivierte Datensätze bei SELECTs auch geholt werden sollen (FALSE) oder nicht (TRUE)
	 *
	 * @return boolean
	 */
	public function isRespectHidden() {
		return $this->respectHidden;
	}

	/**
	 * Setzen, ob deaktivierte Datensätze bei SELECTs auch geholt werden sollen (FALSE) oder nicht (TRUE)
	 *
	 * @param boolean $respectHidden
	 * @return void
	 */
	public function setRespectHidden($respectHidden) {
		$this->respectHidden = $respectHidden;
	}

	/**
	 * Nested Set Felder aktualisieren
	 * der Nested-Set-Baum wird neu aufgebaut anhand der id/parent_id-Beziehung die ebenfalls in der Tabelle vorhanden
	 * ist.
	 *
	 * @param     $prefix
	 * @param     $table
	 * @param     $orderBy
	 * @param int $parent
	 * @param int $depth
	 * @param string $parentname
	 * @return void
	 */
	public function updateNestedSet($prefix, $table, $orderBy, $parent = 0, $depth = 0, $parentname = '') {
		$Q = "SELECT * FROM " . $table . " WHERE " . $prefix . '_' . $prefix . "_fk=" . $parent . " ORDER BY " . $orderBy;
		$data = $this->fw->DC->getAllbyQuery($Q);

		if ($parent == 0) {
			$this->fw->DC->sendQuery("UPDATE " . $table . " SET " . $prefix . "_lft=0," . $prefix . "_rgt=0");
			$left = 0;
		} else {
			$Q = "SELECT * FROM " . $table . " WHERE " . $prefix . "_pk=" . $parent;
			$parentData = $this->fw->DC->getByQuery($Q);
			$left = $parentData[$prefix . "_rgt"];
		}

		for ($i = 0; $i < count($data); $i++) {
			if ($parent == 0) {
				$this->fw->DC->sendQuery("UPDATE " . $table . " SET " . $prefix . "_lft=" . $left . "," . $prefix . "_rgt=" . ($left + 1) . ", " . $prefix . "_depth=0, " . $prefix . "_fullname='" . $this->fw->DC->sql_escape($data[$i][$prefix . '_name']) . "' WHERE " . $prefix . "_pk=" . $data[$i][$prefix . "_pk"]);
				$left += 2;
			} else {
				$this->fw->DC->sendQuery("UPDATE " . $table . " SET " . $prefix . "_lft=" . $prefix . "_lft+2 WHERE " . $prefix . "_lft>" . $left);
				$this->fw->DC->sendQuery("UPDATE " . $table . " SET " . $prefix . "_rgt=" . $prefix . "_rgt+2 WHERE " . $prefix . "_rgt>=" . $left);
				$this->fw->DC->sendQuery("UPDATE " . $table . " SET " . $prefix . "_lft=" . $left . "," . $prefix . "_rgt=" . ($left + 1) . ", " . $prefix . "_depth=" . $depth . ", " . $prefix . "_fullname='" . $this->fw->DC->sql_escape($parentname . ' ' . $data[$i][$prefix . '_name']) . "' WHERE " . $prefix . "_pk=" . $data[$i][$prefix . "_pk"]);
				$left += 2;
			}
		}
		for ($i = 0; $i < count($data); $i++) {
			$this->updateNestedSet($prefix, $table, $orderBy, $data[$i][$prefix . '_pk'], $depth + 1, trim($parentname . ' ' . $data[$i][$prefix . '_name']));
		}
	}

	/**
	 * @param string $join
	 */
	public function addJoin($join) {
		$this->joins[] = $join;
	}

	/**
	 *
	 */
	public function clearJoins() {
		$this->joins = Array();
	}

	/**
	 * @return string
	 */
	public function getJoinsString() {
		return implode(' ', $this->joins).' ';
	}

	/**
	 * @param string $where
	 * @return void
	 */
	public function addWhere($where) {
		$this->where[] = $where;
	}

	/**
	 * @return void
	 */
	public function clearWhere() {
		$this->where = array();
	}
	
	
	public function addEntryWhere($where) {
		$this->entrywhere[] = $where;
	}

	/**
	 * @return void
	 */
	public function clearEntryWhere() {
		$this->entrywhere = array();
	}	

	/**
	 * Liefert (gefiltert / alle) Datensätze im System zurück.
	 *
	 * @return \Language\Model\LanguageModel[] Gefundene Datensätze; leeres Array, wenn keine Datensätze gefunden wurden.
	 */
	public function findAll() {
		$list = array();

		$query = $this->createQuery();
		$this->lastQuery = $query;
		#vd($query );exit;
		$rows = $this->fw->DC->getAllByQuery($query);

		$this->foundRows = $this->fw->DC->foundRows;

		foreach ($rows as $data) {
			$list[$data[$this->getPrefix() . "_pk"]] = new $this->model($data);
			$list[$data[$this->getPrefix() . "_pk"]]->allData = $data;
		}

		return $list;
	}

	public function createQuery($countQuery=false) {
		$query = "SELECT ";
		if($countQuery) {
			$query .= "count(*) ";
		} else {
			if(DB_TYPE!="sqlite") {
				$query .= "SQL_CALC_FOUND_ROWS * ";
			} else $query .= " * ";
		}
		$query .= "FROM " . $this->getTable() . " ".
				$this->getJoinsString().
				"WHERE 1 = 1 " .
				$this->enrichListQuery();
		return $query;
	}

	public function countAll() {
		$query = $this->createQuery(true);
		$rows = $this->fw->DC->countByQuery($query);
		return $rows;
	}

	/**
	 * Liefert einen Datensatz gemäß dem ggb. Filter zurück.
	 *
	 * @return NULL|object
	 */
	public function findOne() {
		$query = "SELECT * ".
				"FROM ".$this->getTable()." ".
				$this->getJoinsString().
				"WHERE 1 = 1 ";

		if (count($this->where) > 0) {
			foreach ($this->where as $where) {
				$query .= " AND (" . $where . ") ";
			}
		}

		#$this->enrichEntryQuery();
		$query .= $this->enrichListQuery();
		#vd($query);

		$data = $this->fw->DC->getByQuery($query);

		return $data !== NULL ? new $this->model($data) : new \classes\NullObj();
	}

	/**
	 * Liefert einen Datensatz gemäß der ggb. ID zurück.
	 *
	 * @param integer $pk
	 * @return NULL|object
	 */
	public function findByPk($pk) {
		if($pk instanceof \classes\NullObj) return $pk;
		if((int)$pk==0) return new \classes\NullObj();
		$query = "SELECT * ".
				"FROM ".$this->getTable()." ".
				$this->getJoinsString().
				"WHERE ".$this->getPrefix()."_pk = ".(int)$pk." ".
				$this->enrichEntryQuery();
				
		$data = $this->fw->DC->getByQuery($query);

		if($data !== NULL) {
			$obj = new $this->model($data);
			$obj->allData = $data;
		} else $obj = new \classes\NullObj();

		return $obj;
	}

	public function findAllByPks(array $pks) {
		$G = array_filter($pks);
		if(count($G)==0) return array();
		$this->addWhere("".$this->getPrefix()."_pk in (".implode(",", $G).")");
		return $this->findAll();
	}

	/**
	 * Liefert den ganzen Datensatz zurück ohne Objekt
	 *
	 * @param integer $pk
	 * @return Array
	 */
	public function getDataByPk($pk) {
		$query = "SELECT * " .
				"FROM " . $this->getTable() . " " .
				$this->getJoinsString() .
				"WHERE " . $this->getPrefix() . "_pk = " . (int)$pk . " " .
				$this->enrichEntryQuery();
		$data = $this->fw->DC->getByQuery($query);

		return $data;
	}

	/**
	 * @param integer $pk
	 * @param Array $changeData
	 * @return integer
	 */
	public function copyByPk($pk, $changeData = Array(), $langDistinct=false) {
		$data = $this->getDataByPk((int)$pk);
		$data = array_merge($data, $changeData);
		$data = $this->processDataBeforeCopy($data);
		$GLOBALS["copyKaskade"]++;
		$newPk = $this->insert($data);
		setS('highlightCrudLine',$newPk);

		if($langDistinct) {
			$query = "SELECT * " .
				"FROM " . $this->getTable() . "lang " .
				"WHERE " . $this->getPrefix() . "l_". $this->getPrefix() . "_fk = " . (int)$pk . " " .
				" AND " . $this->getPrefix() . "l_deleted=0  ";
			$langdatas = $this->fw->DC->getAllByQuery($query);
			foreach($langdatas as $langdata) {
				unset($langdata[ $this->getPrefix() . "l_pk"]);
				$langdata[ $this->getPrefix() . "l_".$this->getPrefix()."_fk"] = $newPk;
				$langdata[ $this->getPrefix() . "l_createdate"] = now();
				$langdata[ $this->getPrefix() . "l_changedate"] = now();

				$langdata = $this->processLangDataBeforeCopy($langdata);

				$this->fw->DC->insert($langdata, $this->getTable() . "lang");
			}
		}

		$this->postCopy($pk, $newPk);

		return $newPk;
	}

	protected function postCopy($pk, $newPk) {

	}

	/**
	 * @param Array $data
	 * @return Array
	 */
	public function processDataBeforeCopy(Array $data) {
		if ($GLOBALS["copyKaskade"] == 0) { // Wird im AbstractCrudController->copyAction auf 0 gesetzt
			if (isset($data[$this->getPrefix() . "_name"])) {
				$data[$this->getPrefix() . "_name"] .= " (kopie - ".date("d.m.y H:i:s").")";
			}
		}

		return $data;
	}

	public function processLangDataBeforeCopy($data) {
		if(isset($data[$this->getPrefix() ."l_name"])) {
			if ($data[$this->getPrefix() ."l_lang"] == "de") {
				$data[$this->getPrefix() ."l_name"] .= " - Kopie";
			} else {
				$data[$this->getPrefix() ."l_name"] .= " - Copy";
			}
		}
		return $data;
	}

	/**
	 * Fügt einen neuen Datensatz ein und liefert die ID dieses DS zurück.
	 *
	 * @param array   $data        Einzufügende Werte
	 * @param boolean $hasBaseData Flag, ob Basisdaten wie Datumsangaben gesetzt werden sollen
	 * @return integer
	 */
	public function insert(array $data, $hasBaseData = TRUE) {

		unset($data[$this->getPrefix() . "_pk"]);
		if ($hasBaseData) {
			$data[$this->getPrefix()."_createdate"] = (isset($data[$this->getPrefix()."_createdate"]) ? $data[$this->getPrefix()."_createdate"] : now());
			$data[$this->getPrefix()."_changedate"] = (isset($data[$this->getPrefix()."_changedate"]) ? $data[$this->getPrefix()."_changedate"] : now());
			$data[$this->getPrefix()."_deleted"] = (isset($data[$this->getPrefix()."_deleted"]) ? (int)$data[$this->getPrefix()."_deleted"] : 0);
			$data[$this->getPrefix()."_hidden"] = (isset($data[$this->getPrefix()."_hidden"]) ? (int)$data[$this->getPrefix()."_hidden"] : 0);
		}

		$pk = $this->fw->DC->insert($data, $this->getTable());
		$this->historize($pk);

		return $pk;
	}

	public function historize($pk) {
		if ($this->hasHistoricization()) {
			Historize::go($this->getTable(), $this->getPrefix(), $pk);
		}
	}

	/**
	 * Aktualisiert einen vorhandenen Datensatz und liefert die ID dieses DS zurück.
	 *
	 * @param array   $data        Zu aktualisierende Werte
	 * @param int     $pk          ID des vorhandenen Datensatzes
	 * @param boolean $hasBaseData Flag, ob Basisdaten wie Datumsangaben gesetzt werden sollen
	 * @return integer
	 */
	public function update(array $data, $pk, $hasBaseData = TRUE) {
		if ($hasBaseData) {
			$data[$this->getPrefix()."_changedate"] = (isset($data[$this->getPrefix()."_changedate"]) ? $data[$this->getPrefix()."_changedate"] : now());
		}

		$this->fw->DC->update($data, $this->getTable(), $pk, $this->getPrefix()."_pk");
		$this->historize($pk);

		return $pk;
	}

	/**
	 * Löschen eines Datensatzes, indem das "deleted"-Flag auf 1 gesetzt wird
	 *
	 * @param integer $pk ID des zu löschenden Datensatzes
	 * @return void
	 */
	public function deleteByPk($pk) {
		$query = "UPDATE ".$this->getTable()." ".
				"SET ".$this->getPrefix()."_changedate = '".now()."', ".
				$this->getPrefix()."_deleted = 1 ".
				"WHERE ".$this->getPrefix()."_pk = ".(int)$pk." ";
		$this->fw->DC->sendQuery($query);

		$this->historize($pk);
	}

	/**
	 * @param string $where
	 * @return void
	 */
	public function deleteByWhere($where) {
		$this->clearWhere();
		$this->addWhere($where);
		$all = $this->findAll();
		foreach($all as $one) {
			$this->deleteByPk($one->getPk());
		}
	}

	public function getPkByFkAndLang($pk, $lang, $parentPrefix) {
		$Q = "SELECT * FROM ".$this->table."
			  WHERE ".$this->prefix."_".$parentPrefix."_fk='".(int)$pk."'
			  AND ".$this->prefix."_lang='".$lang."'
			   AND ".$this->prefix."_deleted='0'
			  ";
		$data = $this->fw->DC->getByQuery($Q);
		if($data=="") return null;
		return $data[$this->prefix."_pk"];
	}
}

?>