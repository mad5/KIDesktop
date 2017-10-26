<?php
use Workerman\Worker;
use Workerman\WebServer;
use Workerman\Autoloader;
use PHPSocketIO\SocketIO;

$config = json_decode(file_get_contents("config.json"), true);
$js = 'var CONFIG = {';
$js .= '    "apiserver": "'.$config["kideskadmsrv"].'", ';
$js .= '    "mainkey": "'.$config["mainkey"].'", ';
$js .= '    "key": "'.$config["key"].'" ';
$js .= '};';
file_put_contents(dirname(__FILE__).'/public/config.js', $js);

// composer autoload
include __DIR__ . '/socketio/vendor/autoload.php';
include __DIR__ . '/socketio/src/autoload.php';

$io = new SocketIO(2020);
$io->on('connection', function(\PHPSocketIO\Socket $socket){

	//$socket->join("testraum");
    
    $socket->on('action', function ($data)use($socket){
    	//$socket->to("testraum")->emit('action', $data);
    	print_r($data);
    	
    	$disable = array();
    	
    	if($data["action"]=="shutdown") {
    		exec("sudo halt");
    	}
    	if($data["action"]=="reboot") {
    		exec("sudo reboot");
    	}
    	if($data["action"]=="terminal") {
    		exec("lxterminal");
    	}
    	
    	if($data["action"]=="initrun") {
    		exec('wmctrl -r "myKidesktop" -o 0,0');
    		if($data["cmd"]!="test") exec('wmctrl -r "myKidesktop" -b add,below');
    		exec('wmctrl -r "myKidesktop" -b add,maximized_vert,maximized_horz');
    		if($data["cmd"]=="full") sleep(1);
    		if($data["cmd"]=="full") exec("xdotool mousemove 100 10 && xdotool click 3 && xdotool key t");
    	}
    	
    	if($data["action"]=="openurl") {
    		$url = $data["url"];
    		$E = "chromium-browser ".implode(" ", $disable)." --app=".escapeshellarg($url);
    		exec($E." > /dev/null &");
    	}
    	
    	if($data["action"]=="runcommand") {
		$a = exec($data["app"]." > /dev/null &", $b);
		$socket->emit('action', array("action"=>"dbg", "info" => $E."<br>".print_r($a,1)."<br>".print_r($b,1)));
    	}

    	if($data["action"]=="runapp") {
    		$appname = $data["app"];
    		$E = "chromium-browser ".implode(" ", $disable)." --app=http://localhost:2022/apps/".$appname."/index.php";
    		$a = exec($E." > /dev/null &", $b);
		$socket->emit('action', array("action"=>"dbg", "info" => $E."<br>".print_r($a,1)."<br>".print_r($b,1)));
    	}
    	$socket->emit('action', array("action"=>"dbg", "info" => print_r($data,1)));
    });
   
});

$web = new WebServer('http://0.0.0.0:2022');
$web->addRoot('localhost', __DIR__ . '/public');

Worker::runAll();
