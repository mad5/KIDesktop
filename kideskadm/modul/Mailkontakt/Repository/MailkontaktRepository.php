<?php

namespace Mailkontakt\Repository;

/**
 */
class MailkontaktRepository extends \classes\AbstractRepository {

	/**
	 * @var string
	 */
	protected $table = 'mailkontakt';

	/**
	 * @var string
	 */
	protected $model = '\Mailkontakt\Model\MailkontaktModel';

	/**
	 * @var string
	 */
	protected $prefix = 'mk';

	/**
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		#$this->setOrderBy($this->getPrefix().'_name');
		#$this->setSearchFields(array($this->getPrefix().'_name'));
	}

}

?>