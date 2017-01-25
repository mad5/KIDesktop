<?php
use Workerman\Worker;
use Workerman\WebServer;
use Workerman\Autoloader;
use PHPSocketIO\SocketIO;

// composer autoload
include __DIR__ . '/socketio/vendor/autoload.php';
include __DIR__ . '/socketio/src/autoload.php';

$io = new SocketIO(2020);
$io->on('connection', function(\PHPSocketIO\Socket $socket){

	//$socket->join("testraum");
    
    $socket->on('action', function ($data)use($socket){
    	//$socket->to("testraum")->emit('action', $data);
    	print_r($data);
    	
    	if($data["action"]=="initrun") {
    		exec('wmctrl -r "myKidesktop" -o 0,0');
    		exec('wmctrl -r "myKidesktop" -b add,below');
    		exec('wmctrl -r "myKidesktop" -b add,maximized_vert,maximized_horz');
    		//exec("");
    	}
    	
    	if($data["action"]=="openurl") {
    		$url = $data["url"];
		$disable = array(
			"--disable-translate",
			"--disable-autofill-keyboard-accessory-view",
			"--disable-default-apps",
			"--disable-extensions",
			"--disable-infobars",
			"--disable-notifications",
			"--disable-prompt-on-repost",
			"--disable-popup-blocking",
		);
    		$E = "chromium-browser ".implode(" ", $disable)." --app=".escapeshellarg($url);
    		exec($E." > /dev/null &");
    	}
    	
    	if($data["action"]=="runapp") {
    		exec($data["app"]." > /dev/null &");
    	}
    	
    	$socket->emit('action', $data);
    });
   
});

$web = new WebServer('http://0.0.0.0:2022');
$web->addRoot('localhost', __DIR__ . '/public');

Worker::runAll();
