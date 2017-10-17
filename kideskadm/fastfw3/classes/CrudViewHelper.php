<?php

namespace classes;

/**
 */
class CrudViewHelper {

	/**
	 * @param array $listColumns
	 * @param array $sortableColumns
	 * @param string $sort
	 * @param string $order
	 * @return string
	 */
	static public function getTableHead(Array $listColumns, Array $sortableColumns, $sort = "", $order = "asc", $disableGroupCommand=false, $disableLineButtons=false) {
		if (count($listColumns) == 0) {
			return '';
		}

		$tpl = new Template();

		$tpl->setVariable('listColumns', $listColumns);
		$tpl->setVariable('sortableColumns', $sortableColumns);
		$tpl->setVariable('sort', $sort);
		$tpl->setVariable('order', $order);
		$tpl->setVariable('disableGroupCommand', $disableGroupCommand);
		$tpl->setVariable('disableLineButtons', $disableLineButtons);

		return $tpl->get('Helper/tpl.crudTableHead.php');
	}

	/**
	 * @param \classes\AbstractModel $entry
	 * @return string
	 */
	static public function getDefaultActions($entry) {
		$tpl = new Template();

		$tpl->setVariable('entry', $entry);

		return $tpl->get('Helper/tpl.crudListActions.php');
	}

	/**
	 * @param array $entry
	 * @return string
	 */
	static public function getFormButtons($entry = array()) {
		$tpl = new Template();

		$tpl->setVariable('entry', $entry);

		return $tpl->get('Helper/tpl.crudFormButtons.php');
	}

}

?>