<?php
if(file_exists("config.json")) {
	die("config.json already exists!\nwill not overwrite...\n");
}
if(file_exists(dirname(__FILE__)."/sqlitedb/sqlite.db")) {
	die("Database already setup!\nwill not overwrite...\n");
}

chdir(dirname(__FILE__));

if(file_exists("cache/update.log")) unlink("cache/update.log");
if(file_exists("cache/update.log.count")) unlink("cache/update.log.count");

#$A = exec("/usr/bin/php cmd.php Init/test", $B);
#var_dump($A);var_dump($B);
#$A = exec("/usr/bin/php cmd.php Init/first", $B);
#var_dump($A);var_dump($B);
$nooutput = true;
$argv = array("cmd.php", "Init/first");
include "cmd.php";

$config = array(
	"kideskadmsrv"=> "http://localhost:8000/api.php",
	"mainkey"=> $GLOBALS["UserHash"],
	"key"=> $GLOBALS["RechnerHash"],
	"hosts"=> array(
		"kidesktop.mad5.de"
	)
);

file_put_contents(dirname(__FILE__).'/../config.json', json_encode($config, JSON_PRETTY_PRINT));

echo "config-files created.\n";
echo "Now start your kidesktop with: ./run admin\n";
echo "Use as admin as username and password.\n";
echo "\n";
?>