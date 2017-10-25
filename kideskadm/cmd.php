<?php
error_reporting(E_ERROR);
chdir(dirname(__FILE__));
$cmd = array_shift($argv);

$route = array_shift($argv);
$_REQUEST["fw_goto"] = $_GET["fw_goto"] = $route;
foreach($argv as $arg) {
	$n = substr($arg,0,strpos($arg,"="));
	$v = substr($arg,strpos($arg,"=")+1);
	$_REQUEST[$n] = $_GET[$n] = $v;
}

include "index.php";
?>