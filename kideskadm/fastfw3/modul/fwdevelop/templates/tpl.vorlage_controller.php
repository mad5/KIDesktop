<?php
class ##MODNAME##Controller extends \classes\##ABSTRACTCTRL## {

    public function __construct() {
        parent::__construct();
        #new fe_user(array('checklogin' => true)); // array('requirelogin' => true)

        ##CRUDINIT##
    }

    public function index##CRUDINDEXACTION##Action() {
        $tpl = $this->newTpl();



        $this->fw->setVariable('CONTENT', $tpl->get('##MODNAME##/tpl.Index.php'));
    }

    ##CRUDMETHODS##

} // fastfwController
?>