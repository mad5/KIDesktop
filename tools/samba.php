<?php
chdir(dirname(__FILE__));
if(!file_exists("samba.conf")) exit;
$confs = json_decode(file_get_contents("samba.conf"), true);

if(!file_exists("mnt")) {
	mkdir("mnt", 0775);
	chmod("mnt", 0775);
}

foreach($confs as $conf) {
	exec("sudo umount -f mnt/".$conf["folder"]);
	if(!file_exists("mnt/".$conf["folder"])) {
		mkdir("mnt/".$conf["folder"], 0775);
		chmod("mnt/".$conf["folder"], 0775);
	}
	$m = "sudo mount -t cifs -o credentials=".$conf["credfile"]." ".$conf["cifs"]." mnt/".$conf["folder"];
	echo $m."\n";
	exec($m);
}

?>