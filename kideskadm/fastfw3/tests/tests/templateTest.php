<?php

error_reporting(E_WARNING | E_ERROR);
define('projectPath', dirname(__FILE__));
include_once dirname(__FILE__).'/../../classes/class.template.php';

class templateTest extends PHPUnit_Framework_TestCase {
    protected function setUp() {


    }

    protected function tearDown() {

    }

    public function test_tploutput() {
        $tpl = new template();
        $html = $tpl->get(projectPath.'/templates/tpl.test1.php');
        $this->assertEquals("ABC", $html);
    }

    public function test_setVariableEmpty() {
        $tpl = new template();
        $tpl->setVariable("X", "123");
        $html = $tpl->get(projectPath.'/templates/tpl.test2.php');
        $this->assertEquals("123", $html);
    }
    public function test_setVariableIntotext() {
        $tpl = new template();
        $tpl->setVariable("X", "123");
        $html = $tpl->get(projectPath.'/templates/tpl.test3.php');
        $this->assertEquals("ABC123XYZ", $html);
    }

    public function test_unsetVariable() {
        $tpl = new template();
        $html = $tpl->get(projectPath.'/templates/tpl.test3.php');
        $this->assertEquals("ABCXYZ", $html);
    }

}
?>