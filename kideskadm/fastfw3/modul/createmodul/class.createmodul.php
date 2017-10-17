<?php
class fastfw_createmodul extends fastfw_modul {

	public $index;

	public function __construct() {
		// {{{
        $this->fw = $GLOBALS["FastFW"];
        if (!$this->fw->getDevelop()) die("development-mode not active!");

        // }}}
	}

	public function view_index($QS) {
		// {{{

		#vd($_POST);

		$modulName = $_POST['modulName'];
		$modulPath = projectPath.'/modul/'.$modulName;
		if(!file_exists($modulPath)) {
			mkdir($modulPath);
			chmod($modulPath, 02775);
		}
		if(!file_exists($modulPath.'/templates')) {
			mkdir($modulPath.'/templates');
			chmod($modulPath.'/templates', 02775);
		}
		if(!file_exists($modulPath.'/classes')) {
			mkdir($modulPath.'/classes');
			chmod($modulPath.'/classes', 02775);
		}

		$MP = '';
		$M = explode("\n", trim($_POST['methods']));
		for($i=0;$i<count($M);$i++) {
			$me = trim($M[$i]);
			if($me!='') {
				$tpl = file_get_contents(dirname(__FILE__).'/templates/tpl.indextpl.txt');
				file_put_contents($modulPath.'/templates/tpl.'.$me.'.php', $tpl);
				chmod($modulPath.'/templates/tpl.'.$me.'.php', 0664);

				$tpl = file_get_contents(dirname(__FILE__).'/templates/tpl.method.txt');
				$tpl = str_replace('##METHOD##', $me, $tpl);

				$MP .= $tpl;

			}
		}

		$tpl = file_get_contents(dirname(__FILE__).'/templates/tpl.class.txt');
		$tpl = str_replace('##MODULNAME##', $modulName, $tpl);
		file_put_contents($modulPath.'/classes/class.'.$modulName.'.php', $tpl);
		chmod($modulPath.'/classes/class.'.$modulName.'.php', 0664);

		$tpl = file_get_contents(dirname(__FILE__).'/templates/tpl.modul.txt');
		$tpl = str_replace('##MODULNAME##', $modulName, $tpl);
		$tpl = str_replace('##METHOD##', $MP, $tpl);
		file_put_contents($modulPath.'/class.'.$modulName.'.php', $tpl);
		chmod($modulPath.'/class.'.$modulName.'.php', 0664);

		echo "<a href='".getLink($modulName)."'>Modul aufrufen : ".$modulName."</a><br/><br/>";
		for($i=0;$i<count($M);$i++) {
			$me = trim($M[$i]);
			if($me!='') {
				echo "<a href='".getLink($modulName.'/'.$me)."'>Modul aufrufen : ".$modulName."/".$me."</a><br/>";
			}
		}

		exit;
        #$this->fw->setContentBody('CONTENT', $tpl->get('tpl.test.php'));
		// }}}
	}

}
?>