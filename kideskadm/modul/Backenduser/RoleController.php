<?php

/**
 */
class RoleController extends \classes\AbstractCrudController {

	/**
	 * @var \Backenduser\Repository\RoletypeRepository
	 */
	protected $roletypeRepository;

	/**
	 * @var \Backenduser\Repository\RoleAreaRepository
	 */
	protected $roleAreaRepository;

	/**
	 * @var \Backenduser\Repository\RoleRightRepository
	 */
	protected $roleRightRepository;

	/**
	 */
	public function __construct() {
		parent::__construct();

		$this->initCrud('Backenduser', 'Role', 'br');
		$this->addListColumn('br_name', 'Name');
		$this->addListColumn('br_roletype_fk', trans('backenduser|Rollentyp'));

		$this->setSortableColumns(array('br_name', 'br_roletype_fk'));

		$this->roletypeRepository = new \Backenduser\Repository\RoletypeRepository();
		$this->roleAreaRepository = new \Backenduser\Repository\RoleAreaRepository();
		$this->roleRightRepository = new \Backenduser\Repository\RoleRightRepository();
	}

	/**
	 * @param integer $pk
	 * @param Array $data
	 */
	protected function postInsert($pk, $data) {
		$this->roleRightRepository->updateByRolePk($pk, $_POST["role"]["rightown"], $_POST["role"]["rightother"]);

		return $data;
	}

	/**
	 * @param integer $pk
	 * @param Array $data
	 */
	protected function postUpdate($pk, $data) {
		$this->roleRightRepository->updateByRolePk($pk, $_POST["role"]["rightown"], $_POST["role"]["rightother"]);

		return $data;
	}

	/**
	 * @param Array $queryArray
	 */
	public function editAction($queryArray) {
		$this->addVariable('areas', $this->roleAreaRepository->findAllFirsLevel());
		$this->addVariable('selectedAreas', $this->roleRightRepository->findMatrixByRolePk($queryArray[0]));
		$this->addVariable('roletypes', $this->roletypeRepository->findAll());

		parent::editAction($queryArray);
	}

	/**
	 */
	public function newAction() {
		$this->addVariable('areas', $this->roleAreaRepository->findAllFirsLevel());
		$this->addVariable('roletypes', $this->roletypeRepository->findAll());

		parent::newAction();
	}

	/**
	 * @param Array $queryArray
	 */
	public function viewAction($queryString) {
		if (!\Backenduser\Service\AccessService::hasReadAccessByModelname($this->modelName)) {
			die ('Access denied!');
		}
	}

}

?>