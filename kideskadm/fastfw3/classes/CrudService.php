<?php

namespace classes;

/**
 * User: jc
 * Date: 27.11.2014
 * Time: 11:54
 */
class CrudService {

	/**
	 * Kleinster Wert für Elemente pro Trefferseite
	 */
	const MIN_PER_PAGE = 10;

	const DEFAULT_PER_PAGE = 25;

	/**
	 * Mindestanzahl von Elementen pro Trefferseite
	 */
	static public $elementsPerPage = array(10, 25, 50,100);

	/**
	 * Setzt die anzuzeigende Seite für die Trefferliste auf die erste Seite zurück.
	 */
	static public function clearSavedPagination($id) {
		setS("crudListPage_" . $id, 0);
		$_REQUEST["crudListPage"] = 0;
	}

	/**
	 * Prüft, ob laut Request eine bestimmte Anzahl Treffer pro Seite für die Trefferliste angefordert wird, speichert
	 * ggf. diesen Wert in der Session zwischen und liefert den gesetzten Session-Wert zurück. Der Wert wird mindestens
	 * 10 betragen.
	 *
	 * @return int
	 */
	static public function getPerPageSessionData($id) {
		if (isset($_REQUEST["crudListPerPage"]) && (int)$_REQUEST["crudListPerPage"] > 0) {
			if (getS("crudListPerPage_" . $id) != $_REQUEST["crudListPerPage"]) {
				self::clearSavedPagination($id);
			}
			setS("crudListPerPage_" . $id, (int)$_REQUEST["crudListPerPage"]);
		}
		$perPage = (int)getS("crudListPerPage_" . $id);
		if($perPage==0) $perPage = self::DEFAULT_PER_PAGE;
		return max(self::MIN_PER_PAGE, $perPage);
	}

	/**
	 * Prüft, ob laut Request eine bestimmte Suchanfrage gemacht wird, speichert ggf. diese Suchanfrage in der Session
	 * zwischen und liefert den gesetzten Session-Wert zurück.
	 *
	 * @return string
	 */
	static public function getSearchSessionData($id) {
		if (isset($_REQUEST["crudListSearch"])) {
			if (getS("crudListSearch_" . $id) != $_REQUEST["crudListSearch"]) {
				self::clearSavedPagination($id);
			}
			setS("crudListSearch_" . $id, $_REQUEST["crudListSearch"]);
		}
		return getS("crudListSearch_" . $id);
	}

	/**
	 * Prüft, ob laut Request eine bestimmte Sortierung der Trefferliste angefordert wird, speichert ggf. diese Werte in
	 * der Session zwischen und liefert die gesetzten Session-Werte zurück.
	 *
	 * Beispiel:
	 *
	 * $sort = array(
	 *     'orderBy' => 'name',
	 *     'orderDir' => 'asc',
	 * );
	 *
	 * @return array
	 */
	static public function getSortSessionData($id) {
		if (isset($_REQUEST["crudListSort"]) && $_REQUEST["crudListSort"] != "") {
			if (getS("crudListSort_" . $id) == $_REQUEST["crudListSort"]) {
				if (getS("crudListSortOrder_" . $id) === 'asc') {
					setS("crudListSortOrder_" . $id, "desc");
				} else {
					setS("crudListSortOrder_" . $id, "asc");
				}
			} else {
				setS("crudListSortOrder_" . $id, "asc");
			}
			setS("crudListSort_" . $id, $_REQUEST["crudListSort"]);
		}
		return array(
			'orderBy' => getS("crudListSort_" . $id),
			'orderDir' => getS("crudListSortOrder_" . $id),
		);
	}

	/**
	 * Prüft, ob laut Request eine bestimmte Seite in der Trefferliste angefordert wird, speichert ggf. diesen Wert in
	 * der Session zwischen und liefert den gesetzten Session-Wert zurück.
	 *
	 * @return int
	 */
	static public function getPageSessionData($id) {
		if (isset($_REQUEST["crudListPage"]) && (int)$_REQUEST["crudListPage"] >= 0) {
			self::setPageSessionData($id, (int)$_REQUEST["crudListPage"]);
		}

		return (int)getS("crudListPage_" . $id);
	}

	/**
	 * @param string $id
	 * @param integer $value
	 * @return void
	 */
	static public function setPageSessionData($id, $value) {
		setS("crudListPage_" . $id, (int)$value);
	}

}

?>