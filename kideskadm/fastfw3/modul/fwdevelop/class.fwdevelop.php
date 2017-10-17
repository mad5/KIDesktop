<?php

class fastfw_fwdevelop extends \classes\fastfw_modul {

	public function  __construct() {
		$this->fw = $GLOBALS["FastFW"];
		if (!$this->fw->getDevelop()) {
			die("development-mode not active!");
		}
	}

	public function view_index($QS) {
		$tpl = $this->newTpl();
		$this->fw->setVariable('CONTENT', $tpl->get("tpl.index.php"));
	}

	public function view_console($QS) {
		$tpl = $this->newTpl();
		echo $tpl->get("tpl.console.php");
		exit;
	}

	public function view_newmodul($QS) {

		$mod = fixname($_POST["modulname"]);

		if (!is_writable(projectPath . '/modul')) {
			die("Ordner " . projectPath . '/modul nicht beschreibbar');
		}

		$this->mkdir("modul/" . ucfirst($mod));
		#$this->mkdir("modul/".$mod.'/classes');
		$this->mkdir("modul/" . ucfirst($mod) . '/Templates');
		#$this->mkdir("modul/".ucfirst($mod).'/Templates/Index');
		$this->mkdir("modul/" . ucfirst($mod) . '/Repository');
		$this->mkdir("modul/" . ucfirst($mod) . '/Service');
		$this->mkdir("modul/" . ucfirst($mod) . '/Model');

		/*
		$source = file_get_contents(dirname(__FILE__)."/templates/tpl.vorlage_model.php");
		$source = str_replace("##MODNAME##", ucfirst($mod), $source);
		file_put_contents("modul/".ucfirst($mod).'/Model/'.ucfirst($mod).'Model.php', $source);
		chmod("modul/".ucfirst($mod).'/Model/'.ucfirst($mod).'Model.php', 0664);
		*/

		/*
		$source = file_get_contents(dirname(__FILE__)."/templates/tpl.vorlage_controller.php");
		$source = str_replace("##MODNAME##", ucfirst($mod), $source);
		file_put_contents("modul/".ucfirst($mod).'/'.ucfirst($mod).'Controller.php', $source);
		chmod("modul/".ucfirst($mod).'/'.ucfirst($mod).'Controller.php', 0664);

		file_put_contents("modul/".ucfirst($mod).'/Templates/Index/tpl.Index.php', "Template: ".$mod);
		chmod("modul/".ucfirst($mod).'/Templates/Index/tpl.Index.php', 0664);
		*/

		jump2page("*/console");
	}

	public function view_newctrl($QS, $noreload=false) {
		$mod = fixname($_POST["modul"]);
		$model = fixname($_POST["modelname"]);
		$ctrl = fixname($_POST["ctrlname"]);
		if (!is_writable(projectPath . '/modul')) {
			die("Ordner " . projectPath . '/modul nicht beschreibbar');
		}

		$source = file_get_contents(dirname(__FILE__) . "/templates/tpl.vorlage_controller.php");
		$source = str_replace("##MODNAME##", ucfirst($ctrl), $source);
		$CRUDMETHOD = "";
		$isLanguageDistinct = false;
		if ($_POST["newControllerType"] == "crud") {

			$prefix = fixname($_POST["modelshort"]);

			$source = str_replace("##ABSTRACTCTRL##", "AbstractCrudController", $source);

			#$source = str_replace('"KÜRZEL"', '', $source);

			$CI = '$this->initCrud("' . ucfirst($mod) . '", "' . ucfirst($model) . '", "'.$prefix.'");' . "\n";

			$CRUDMETHODa = "";

			$feldnamen = array();
			$felder = explode("\n", strtolower(trim($_POST["modelfelder"])));
			foreach ($felder as $line) {
				if (trim($line) == "") {
					continue;
				}
				$data = explode(",", trim($line));

				if($data[1]=="lang") {
					$isLanguageDistinct = true;
					continue;
				}

				$CI .= "\t\t" . '$this->addListColumn("'.$prefix."_".$data[0].'", "'.ucfirst($data[0]).'");' . "\n";
				$feldnamen[] = $prefix."_".$data[0];

				if($data[1] == "rel") {
					if($data[4]=="m") {
						$CRUDMETHODa .= '	if (is_Array($data["'.$prefix.'_'.$data[0].'"])) {'."\n";
						$CRUDMETHODa .= '		$data["'.$prefix.'_'.$data[0].'"] = implode(",", $data["'.$prefix.'_'.$data[0].'"]);'."\n";
						$CRUDMETHODa .= '	} else {'."\n";
						$CRUDMETHODa .= '		$data["'.$prefix.'_'.$data[0].'"] = "";'."\n";
						$CRUDMETHODa .= '	}'."\n";

					}
				}

			}

			if($CRUDMETHODa!="") {
				$CRUDMETHOD .= 'protected function prepareRelData($data) {' . "\n";
				$CRUDMETHOD .= $CRUDMETHODa;
				$CRUDMETHOD .= 'return $data;'."\n";
				$CRUDMETHOD .= '}' . "\n";
			}

			$CRUDMETHOD .= 'protected function preInsert($data) {'."\n";
			if($CRUDMETHODa!="") $CRUDMETHOD .= '	$data = $this->prepareRelData($data);'."\n";
			$CRUDMETHOD .= '	return $data;'."\n";
			$CRUDMETHOD .= '}'."\n";
			$CRUDMETHOD .= 'protected function preUpdate($pk, $data) {'."\n";
			if($CRUDMETHODa!="") $CRUDMETHOD .= '	$data = $this->prepareRelData($data);'."\n";
			$CRUDMETHOD .= '	return $data;'."\n";
			$CRUDMETHOD .= '}'."\n";

			if($data[1] == "subrel") {
				$mm = explode("/", $data[2]);
				$CRUDMETHOD .= 'protected function postInsert($pk, $data) {'."\n";
				$CRUDMETHOD .= '	$'.$mm[1].'Repository = new \\'.ucfirst($mm[0]).'\\Repository\\'.ucfirst($mm[1]).'Repository();'."\n";
				$CRUDMETHOD .= '	foreach($_POST["'.$mm[1].'"] as $key => $'.$mm[1].') {'."\n";
				$CRUDMETHOD .= '		$'.$mm[1].'["'.$data[3].'"] = $pk;'."\n";
				$CRUDMETHOD .= '		$'.$mm[1].'Repository->insert($'.$mm[1].');'."\n";
				$CRUDMETHOD .= '	}'."\n";
				$CRUDMETHOD .= '	return $data;'."\n";
				$CRUDMETHOD .= '}'."\n\n";
				$CRUDMETHOD .= 'protected function postUpdate($pk, $data) {'."\n";
				$CRUDMETHOD .= '	$'.$mm[1].'Repository = new \\'.ucfirst($mm[0]).'\\Repository\\'.ucfirst($mm[1]).'Repository();'."\n";
				$CRUDMETHOD .= '	foreach($_POST["'.$mm[1].'"] as $key => $'.$mm[1].') {'."\n";
				$CRUDMETHOD .= '		if($key>0) {'."\n";
				$CRUDMETHOD .= '			if($'.$mm[1].'==="-1") {'."\n";
				$CRUDMETHOD .= '				$'.$mm[1].'Repository->deleteByPk($key);'."\n";
				$CRUDMETHOD .= '			} else {'."\n";
				$CRUDMETHOD .= '				$'.$mm[1].'Repository->update($'.$mm[1].', $key);'."\n";
				$CRUDMETHOD .= '			}'."\n";
				$CRUDMETHOD .= '		} else {'."\n";
				$CRUDMETHOD .= '			$'.$mm[1].'["'.$data[3].'"] = $pk;'."\n";
				$CRUDMETHOD .= '			$'.$mm[1].'Repository->insert($'.$mm[1].');'."\n";
				$CRUDMETHOD .= '		}'."\n";
				$CRUDMETHOD .= '	}'."\n";
				$CRUDMETHOD .= '	return $data;'."\n";
				$CRUDMETHOD .= '}'."\n\n";
			}

			#$CI .= "\t\t" . '$this->addListColumn("FELDNAME", "BEZEICHNER");' . "\n";
			$CI .= "\t\t" . '$this->setSortableColumns(array("'.implode('","',$feldnamen).'"));' . "\n";

			$CI .= "\t\t" . '$this->templates = array(' . "\n";
			$CI .= "\t\t" . '    "head"          => "' . ucfirst($ctrl) . '/tpl.Head.php",' . "\n";
			$CI .= "\t\t" . '    "list"          => "' . ucfirst($ctrl) . '/tpl.Index.php",' . "\n";
			$CI .= "\t\t" . '    "form"          => "' . ucfirst($ctrl) . '/tpl.Form.php",' . "\n";
			$CI .= "\t\t" . '    "deleteConfirm" => "' . ucfirst($ctrl) . '/tpl.DeleteConfirm.php",' . "\n";
			$CI .= "\t\t" . '    "copyConfirm"   => "' . ucfirst($ctrl) . '/tpl.CopyConfirm.php",' . "\n";
			$CI .= "\t\t" . ');' . "\n";

			if($isLanguageDistinct) {
				$CI .= "\t\t".'$this->setLanguageDistinction(true);'."\n";
			}

			$source = str_replace("##CRUDINIT##", $CI, $source);
			$source = str_replace("##CRUDINDEXACTION##", "2", $source);

			$CRUDMETHOD .= 'function newAction() {'."\n";
			$CRUDMETHOD .= '	parent::newAction();'."\n";
			$CRUDMETHOD .= '}'."\n\n";
			$CRUDMETHOD .= 'function editAction(array $queryArray) {'."\n";
			$CRUDMETHOD .= '	parent::editAction($queryArray);'."\n";
			$CRUDMETHOD .= '}'."\n";

		} else {
			$source = str_replace("##ABSTRACTCTRL##", "AbstractController", $source);
		}
		$source = str_replace("##CRUDINIT##", "", $source);
		$source = str_replace("##CRUDINDEXACTION##", "", $source);

		$source = str_replace("##CRUDMETHODS##", $CRUDMETHOD, $source);


		file_put_contents("modul/" . ucfirst($mod) . '/' . ucfirst($ctrl) . 'Controller.php', $source);
		chmod("modul/" . ucfirst($mod) . '/' . ucfirst($ctrl) . 'Controller.php', 0664);

		$this->mkdir("modul/" . ucfirst($mod) . '/Templates/' . ucfirst($ctrl));
		if ($_POST["newControllerType"] == "crud") {

			$fn = projectPath . "/modul/" . ucfirst($mod) . '/Templates/' . ucfirst($ctrl) . '/tpl.Index.php';
			if(!file_exists($fn)) {
				file_put_contents($fn, file_get_contents(dirname(__FILE__) . "/templates/tpl.crud_index.php"));
				chmod($fn, 0664);
			}

			$fn = projectPath . "/modul/" . ucfirst($mod) . '/Templates/' . ucfirst($ctrl) . '/tpl.Form.php';
			if(!file_exists($fn)) {
				file_put_contents($fn, file_get_contents(dirname(__FILE__) . "/templates/tpl.crud_form.php"));
				chmod($fn, 0664);
			}

			$fn = projectPath . "/modul/" . ucfirst($mod) . '/Templates/' . ucfirst($ctrl) . '/tpl.Head.php';
			if(!file_exists($fn)) {
				file_put_contents($fn, "<h2>".ucfirst($mod)."</h2>");
				chmod($fn, 0664);
			}

		} else {
			$fn = projectPath . "/modul/" . ucfirst($mod) . '/Templates/' . ucfirst($ctrl) . '/tpl.Index.php';
			file_put_contents($fn, "<div></div>");
			chmod($fn, 0664);
		}

		if ($_POST["newControllerType"] == "crud") {

			$model = fixname($_POST["modelname"]);

			$this->mkdir("modul/" . ucfirst($mod) . '/Validator');
			$source = file_get_contents(dirname(__FILE__) . "/templates/tpl.vorlage_validator.php");
			$source = str_replace("##MODNAME##", ucfirst($mod), $source);
			$source = str_replace("##MODELNAME##", ucfirst($model), $source);

			$fn = projectPath . "/modul/" . ucfirst($mod) . '/Validator/' . ucfirst($model) . 'Validator.php';
			file_put_contents($fn, $source);
			chmod($fn, 0664);
		}

		#vd($fn);exit;
		if(!$noreload) {
			jump2page("*/console");
		}
	}

	public function view_newmethod($QS) {
		$mod = $_POST["modul"];
		$method = $_POST["methodname"];

		$fn = projectPath . '/modul/' . $mod . '/' . $_POST['ctrl'];
		$ctrl = str_bis($_POST["ctrl"], 'Controller.php');
		/*
		$fn = projectPath.'/modul/'.$mod.'/class.'.$mod.'.php';
		if(!file_exists($fn)) {
			$mod = ucfirst($_POST["modul"]);
			$fn = projectPath.'/modul/'.ucfirst($mod).'/'.ucfirst($mod).'Controller.php';
		}
		*/

		if (!is_writable($fn)) {
			die("Datei " . $fn . ' nicht beschreibbar');
		}
		$source = file_get_contents($fn);

		$placeholder = '} // fastfwController'; // fastfw_'.$mod;
		if (!stristr($source, $placeholder)) {
			die("Platzhalter " . $placeholder . " nicht gefunden.");
		}

		$new = "\tpublic function " . $method . "Action() {\n";
		$new .= "\t\t\$tpl = \$this->newTpl();\n";
		$new .= "\t\t\n";
		$new .= "\t\t\$this->fw->setVariable('CONTENT', \$tpl->get('" . $ctrl . "/tpl." . $method . ".php'));\n";
		$new .= "\t}\n\n";
		$source = str_replace($placeholder, $new . $placeholder, $source);

		file_put_contents(projectPath . '/modul/' . ucfirst($mod) . '/Templates/' . $ctrl . '/tpl.' . $method . '.php', 'Template: ' . $method);
		chmod(projectPath . '/modul/' . ucfirst($mod) . '/Templates/' . $ctrl . '/tpl.' . $method . '.php', 0664);

		file_put_contents($fn, $source);
		jump2page("*/console");
	}

	public function view_addmodelvar($QS) {
		#vd($_POST);
		$mod = fixname($_POST["modul"]);
		$model = fixname($_POST["model"]);

		$modelfn = "modul/" . ucfirst($mod) . '/Model/' . ucfirst($model) . 'Model.php';
		$txt = file_get_contents($modelfn);
		#vd($txt);

		$prefix = str_nach($txt, '$prefix');
		$prefix = str_zwischen($prefix, '"','"');
		#vd($prefix);

		$varpos = strpos($txt, 'public function ');
		$txt1 = trim(substr($txt, 0, $varpos))."\n";
		$txt2 = substr($txt,  $varpos);
		$txt3 = substr($txt2, strpos($txt2, '} // fastfwModel'));
		$txt2 = substr($txt2, 0,strpos($txt2, '} // fastfwModel'));



		$felder = explode("\n", trim($_POST["modelfelder"]));

		$res = $this->processNewVars($felder);

		$settergetter = $res["settergetter"];
		$rungetter = $res["rungetter"];
		$vars = $res["vars"];
		$langFelder = $res["langFelder"];

		$firstField = "";
		foreach ($felder as $line) {
			if (trim($line) == "") {
				continue;
			}
			$vx = explode(",", $line);
			$vx[0] = trim($vx[0]);
			if ($firstField == "") {
				$firstField = $prefix . "_" . $vx[0];
			}
			$dbType = $this->getDbType($vx[1]);
			$dbDefault = $this->getDbDefault($dbType);
			$sql .= " 'ALTER TABLE  `" . strtolower($model) . "` ADD  `" . $prefix . "_" . $vx[0] . "` " . $dbType . " NOT NULL " . ($dbDefault != '' ? ' default ' . $dbDefault : '') . "', \n";
		}
		#vd($res);exit;

		if(1==2) {
			$firstField = "";
			$sql = "";
			$rungetter = "";
			$settergetter = "";
			$vars = "";
			foreach ($felder as $line) {
				if (trim($line) == "") {
					continue;
				}
				$vx = explode(",", $line);
				$vx[0] = trim($vx[0]);
				if ($firstField == "") {
					$firstField = $prefix . "_" . $vx[0];
				}
				#vd($vx);
				$vars .= '	protected $' . $vx[0] . ' = ' . "'';\n";

				$settergetter .= '	/**' . "\n";
				$settergetter .= '	 * @param string $' . $vx[0] . '' . "\n";
				$settergetter .= '	 */' . "\n";
				$settergetter .= '	public function set' . ucfirst($vx[0]) . '($' . $vx[0] . ') {' . "\n";
				$settergetter .= '		$this->' . $vx[0] . ' = $' . $vx[0] . ';' . "\n";
				$settergetter .= '	}' . "\n";
				$settergetter .= '	/**' . "\n";
				$settergetter .= '	 * @return string' . "\n";
				$settergetter .= '	 */' . "\n";
				$settergetter .= '	public function get' . ucfirst($vx[0]) . '() {' . "\n";
				$settergetter .= '		return $this->' . $vx[0] . ';' . "\n";
				$settergetter .= '	}' . "\n";
				$settergetter .= "\n";

				$rungetter .= "\t\t" . '$this->set' . ucfirst($vx[0]) . '($data["' . $prefix . '_' . $vx[0] . '"]);' . "\n";

				$dbType = $this->getDbType($vx[1]);
				$dbDefault = $this->getDbDefault($dbType);
				$sql .= " 'ALTER TABLE  `" . strtolower($model) . "` ADD  `" . $prefix . "_" . $vx[0] . "` " . $dbType . " NOT NULL " . ($dbDefault != '' ? ' default ' . $dbDefault : '') . "', \n";
			}
		}

		$txt1 .= $vars;
		$txt2 .= $settergetter;
		$txt2a = str_bis($txt2,"}");
		$txt2b = str_nach($txt2,"}");
		$txt2 = rtrim($txt2a)."\n".$rungetter."\n\t}".$txt2b;

		if(!is_writable("inc.update.php")) die("Datei inc.update.php ist nicht beschreibbar.");
		if(!is_writable($modelfn)) die("Datei $modelfn ist nicht beschreibbar.");

		$source = file_get_contents("inc.update.php");
		$source = str_replace("// **AUTOAPPEND**", '/* '.date("d.m.Y H:i").' */ $UPDATE_SQL[] = array("type" => "newfield", "table" => "' . strtolower($model) . '", "field" => "'.$firstField.'", '."\n".'"query" => array(' . $sql . ') );' . "\n\n// **AUTOAPPEND**", $source);
		file_put_contents("inc.update.php", $source);

		#echo $source;

		$txt = $txt1."\n".$txt2.$txt3;
		file_put_contents($modelfn, $txt);

		jump2page("*/console");
	}

	protected function getDbType($kuerzel) {
		$dbtype = "varchar(255)";
		$kuerzel = trim(strtolower($kuerzel));
		if ($kuerzel == "int" || $kuerzel == "i" || $kuerzel=="rel") {
			$dbtype = "bigint";
		} else if ($kuerzel == "ti") {
			$dbtype = "tinyint";
		} else if ($kuerzel == "time") {
			$dbtype = "time";
		} else if ($kuerzel == "vc") {
			$dbtype = "varchar(255)";
		} else if ($kuerzel == "t") {
			$dbtype = "text";
		} else if ($kuerzel == "d") {
			$dbtype = "date";
		} else if ($kuerzel == "dt") {
			$dbtype = "datetime";
		}

		return $dbtype;
	}

	protected function getDbDefault($type) {
		$res = "";
		if($type=="bigint" || $type=='tinyint') $res = "0";
		if($type=="varchar(255)" || $type=='text') $res = '""';
		return $res;
	}

	protected function processNewVars($felder) {

		$isLanguageDistinct = false;
		$settergetter = "";
		$rungetter = "";
		$vars = "";
		$langFelder = array();

		foreach ($felder as $line) {
			if (trim($line) == "") {
				continue;
			}
			$data = explode(",", trim($line));


			if($data[1]=="lang") {
				if($isLanguageDistinct==false) {
					$isLanguageDistinct = TRUE;
					$settergetter .= 'public function getLangDistinction($lang="de") {' . "\n";

					$settergetter .= '$' . $prefix . 'langRepository = new \\' . ucfirst($mod) . '\\Repository\\' . ucfirst($model) . 'langRepository();' . "\n";
					$settergetter .= '$' . $prefix . 'langRepository->addWhere( "' . $prefix . 'l_' . $prefix . '_fk=\'".$this->getPk()."\' ");' . "\n";
					$settergetter .= '$' . $prefix . 'langRepository->addWhere( "' . $prefix . 'l_lang=\'".$lang."\' ");' . "\n";
					$settergetter .= '$' . $prefix . 'l = $' . $prefix . 'langRepository->findOne();' . "\n";

					$settergetter .= 'return $' . $prefix . 'l;' . "\n";
					$settergetter .= '}' . "\n";
				}

				$langFelder[] = implode(",", array($data[0],$data[2],$data[3],$data[4],$data[5],$data[6]));

				continue;
			}

			if($data[1]!="subrel") {
				$vars .= '  protected $' . $data[0] . " = ";
				if ($data[1] == "i" || $data[1] == "int" || $data[1] == "ti" || $data[1] == "rel") {
					$vars .= "0";
					$ret = "int";
				} else if ($data[1] == "vc" || $data[1] == "t") {
					$vars .= "''";
					$ret = "string";
				} else if ($data[1] == "vc" || $data[1] == "t") {
					$vars .= "''";
					$ret = "string";
				} else if ($data[1] == "d" || $data[1] == "dt") {
					$vars .= "null";
					$ret = "string";
				} else {
					$vars .= "null";
				}



				$vars .= ";\n";

				$settergetter .= '  /**' . "\n";
				$settergetter .= '  * @param ' . $ret . ' $' . $data[0] . "\n";
				$settergetter .= '  */' . "\n";
				$settergetter .= '  public function set' . ucfirst($data[0]) . '($' . $data[0] . ') {' . "\n";
				$settergetter .= '      $this->' . $data[0] . ' = $' . $data[0] . ";\n";
				$settergetter .= "  }\n\n";
				$settergetter .= '  /**' . "\n";

				if ($data[1] == "rel") {
					$mm = explode("/", $data[2]);
					if ($data[4] == "m") {
						$settergetter .= ' * @return ' . "\\" . ucfirst($mm[0]) . "\\Model\\" . ucfirst($mm[1]) . "Model[]" . "\n";
					} else {
						$settergetter .= ' * @return ' . "\\" . ucfirst($mm[0]) . "\\Model\\" . ucfirst($mm[1]) . "Model" . "\n";
					}
				} else {
					$settergetter .= '  * @return ' . $ret . '' . "\n";
				}
				$settergetter .= '  */' . "\n";
				$settergetter .= '  public function get' . ucfirst($data[0]) . '() {' . "\n";
				if ($data[1] == "rel") {
					$mm = explode("/", $data[2]);

					if ($data[4] == "m") {
						$settergetter .= '	if (!is_array($this->' . $data[0] . ')) {' . "\n";
						$settergetter .= 'if($this->' . $data[0] . '=="") { $this->' . $data[0] . '=array();return array();}' . "\n";
						$settergetter .= '		$' . $mm[1] . 'Repository = new ' . "\\" . ucfirst($mm[0]) . "\\" . 'Repository' . "\\" . ucfirst($mm[1]) . 'Repository();' . "\n";
						#$settergetter .= '$' . $mm[1] . 'Repository->addWhere( " '.$prefix.'_'.$data[0].' in ( ".$this->'.$data[0].'.")");'."\n";
						$settergetter .= '		$this->set' . ucfirst($data[0]) . '($' . $mm[1] . 'Repository->findAllByPks(explode(",",$this->' . $data[0] . ')));' . "\n";
						$settergetter .= '	}' . "\n";
					} else {

						$settergetter .= '	if (!($this->' . $data[0] . ' instanceof ' . "\\" . ucfirst($mm[0]) . "\\" . 'Model' . "\\" . ucfirst($mm[1]) . 'Model)) {' . "\n";
						#$settergetter .= '		if ((int)$this->'.$data[0].' > 0) {'."\n";
						$settergetter .= '		$' . $mm[1] . 'Repository = new ' . "\\" . ucfirst($mm[0]) . "\\" . 'Repository' . "\\" . ucfirst($mm[1]) . 'Repository();' . "\n";
						$settergetter .= '		$this->set' . ucfirst($data[0]) . '($' . $mm[1] . 'Repository->findByPk($this->' . $data[0] . '));' . "\n";
						#$settergetter .= '	} else {'."\n";
						#$settergetter .= '		$this->setRaum(new '."\\".'classes'."\\".'NullObj());'."\n";
						#$settergetter .= '	}'."\n";
						$settergetter .= '	}' . "\n";
					}

					$settergetter .= '	return $this->' . $data[0] . ";\n";
				} else {
					$settergetter .= '	return $this->' . $data[0] . ";\n";
				}
				$settergetter .= "  }\n\n";

				if ($data[1] == "rel") {
					$settergetter .= '  public function get' . ucfirst($data[0]) . ucfirst($data[3]) . '() {' . "\n";
					if ($data[4] == "m") {
						$settergetter .= '$As = $this->get' . ucFirst($data[0]) . '();' . "\n";
						$settergetter .= '$V = array();' . "\n";
						$settergetter .= 'foreach($As as $A ) {' . "\n";
						$settergetter .= '	$V[] = $A->get' . ucfirst($data[3]) . '();' . "\n";
						$settergetter .= '}' . "\n";
						$settergetter .= 'return implode(", ", $V);' . "\n";
					} else {
						$settergetter .= '		return $this->get' . ucfirst($data[0]) . '()->get' . ucfirst($data[3]) . "();\n";
					}
					$settergetter .= "  }\n\n";

					$settergetter .= '  public function isIn' . ucfirst($data[0]) . '($pk) {' . "\n";
					$settergetter .= '		foreach($this->get' . ucfirst($data[0]) . '() as $one) {' . "\n";
					$settergetter .= '			if($one->getPk()==$pk) return true;' . "\n";
					$settergetter .= '		}' . "\n";
					$settergetter .= '		return false;' . "\n";
					$settergetter .= "  }\n\n";

					$settergetter .= '/**' . "\n";
					$settergetter .= ' * @return ' . "\\" . ucfirst($mm[0]) . "\\Model\\" . ucfirst($mm[1]) . "Model[]" . "\n";
					$settergetter .= ' */' . "\n";
					$settergetter .= '  public function getAllPossible' . ucfirst($data[0]) . '() {' . "\n";
					$settergetter .= '		$' . $mm[1] . 'Repository = new ' . "\\" . ucfirst($mm[0]) . "\\" . 'Repository' . "\\" . ucfirst($mm[1]) . 'Repository();' . "\n";
					$settergetter .= '		return $' . $mm[1] . "Repository->findAll();\n";
					$settergetter .= "  }\n\n";
				}
				$rungetter .= '     $this->set' . ucfirst($data[0]) . '($data["' . $prefix . "_" . strtolower($data[0]) . '"]);' . "\n";

				$dbtype = $this->getDbType($data[1]);
				$sql .= ",\n\t\t\t" . $prefix . "_" . strtolower($data[0]) . " " . $dbtype . " NOT NULL";
			}


			if($data[1]=="subrel") {
				$mm = explode("/", $data[2]);
				$settergetter .= 'public function getAllRelated'.ucfirst($mm[1]).'() {'."\n";
				$settergetter .= '	$'.$mm[1].'Repository = new \\'.ucfirst($mm[0]).'\\Repository\\'.ucfirst($mm[1]).'Repository();'."\n";
				$settergetter .= '	$'.$mm[1].'Repository->addWhere("'.$data[3].'=\'".$this->getPk()."\' ");'."\n";
				$settergetter .= '	return $'.$mm[1].'Repository->findAll();'."\n";
				$settergetter .= '}'."\n\n";
			}

		}
		return array(
			"settergetter" => $settergetter,
			"rungetter" => $rungetter,
			"vars" => $vars,
			"langFelder" => $langFelder,
		);
	}

	public function view_newmodel($QS, $langPrefix="") {
		$mod = fixname($_POST["modul"]);
		$model = fixname($_POST["modelname"]);
		$prefix = fixname($_POST["modelshort"]);

		$felder = explode("\n", strtolower(trim($_POST["modelfelder"])));

		$settergetter = "";
		$rungetter = "";
		$vars = "";

		$sql2 = "";

		$sql = "\nCREATE TABLE IF NOT EXISTS " . strtolower($model) . " (\n";

		$sql .= "\t\t\t" . $prefix . "_pk BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY, \n";
		$sql .= "\t\t\t" . $prefix . "_createdate datetime NOT NULL,\n";
		$sql .= "\t\t\t" . $prefix . "_changedate datetime NOT NULL,\n";
		$sql .= "\t\t\t" . $prefix . "_deleted tinyint NOT NULL,\n";
		$sql .= "\t\t\t" . $prefix . "_hidden tinyint NOT NULL";
		if($langPrefix!="") {
			$sql .= ",\n\t\t\t" . $prefix . "_lang varchar(5) NOT NULL";
			$sql .= ",\n\t\t\t" . $prefix . "_".$langPrefix."_fk bigint NOT NULL";
		}
		#$sql .= $prefix."_\n";
		#$sql .= $prefix."_\n";

		$langFelder = array();

		foreach ($felder as $line) {
			if (trim($line) == "") {
				continue;
			}
			$data = explode(",", trim($line));
			if($data[1]!="subrel") {
				$dbtype = $this->getDbType($data[1]);
				$sql .= ",\n\t\t\t" . $prefix . "_" . strtolower($data[0]) . " " . $dbtype . " NOT NULL";
			}
			$sql .= "\n ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ";
		}

		if(1==1) {
			$res = $this->processNewVars($felder);
			$settergetter = $res["settergetter"];
			$rungetter = $res["rungetter"];
			$vars = $res["vars"];
			$langFelder = $res["langFelder"];
		}
		if(1==2) {
			foreach ($felder as $line) {
				if (trim($line) == "") {
					continue;
				}
				$data = explode(",", trim($line));

				if ($data[1] == "lang") {
					if ($isLanguageDistinct == FALSE) {
						$isLanguageDistinct = TRUE;
						$settergetter .= 'public function getLangDistinction($lang="de") {' . "\n";

						$settergetter .= '$' . $prefix . 'langRepository = new \\' . ucfirst($mod) . '\\Repository\\' . ucfirst($model) . 'langRepository();' . "\n";
						$settergetter .= '$' . $prefix . 'langRepository->addWhere( "' . $prefix . 'l_' . $prefix . '_fk=\'".$this->getPk()."\' ");' . "\n";
						$settergetter .= '$' . $prefix . 'langRepository->addWhere( "' . $prefix . 'l_lang=\'".$lang."\' ");' . "\n";
						$settergetter .= '$' . $prefix . 'l = $' . $prefix . 'langRepository->findOne();' . "\n";

						$settergetter .= 'return $' . $prefix . 'l;' . "\n";
						$settergetter .= '}' . "\n";
					}

					$langFelder[] = implode(",", array($data[0], $data[2], $data[3], $data[4], $data[5], $data[6]));

					continue;
				}

				if ($data[1] != "subrel") {
					$vars .= '  protected $' . $data[0] . " = ";
					if ($data[1] == "i" || $data[1] == "int" || $data[1] == "ti" || $data[1] == "rel") {
						$vars .= "0";
						$ret = "int";
					} else {
						if ($data[1] == "vc" || $data[1] == "t") {
							$vars .= "''";
							$ret = "string";
						} else {
							if ($data[1] == "vc" || $data[1] == "t") {
								$vars .= "''";
								$ret = "string";
							} else {
								if ($data[1] == "d" || $data[1] == "dt") {
									$vars .= "null";
									$ret = "string";
								} else {
									$vars .= "null";
								}
							}
						}
					}

					$vars .= ";\n";

					$settergetter .= '  /**' . "\n";
					$settergetter .= '  * @param ' . $ret . ' $' . $data[0] . "\n";
					$settergetter .= '  */' . "\n";
					$settergetter .= '  public function set' . ucfirst($data[0]) . '($' . $data[0] . ') {' . "\n";
					$settergetter .= '      $this->' . $data[0] . ' = $' . $data[0] . ";\n";
					$settergetter .= "  }\n\n";
					$settergetter .= '  /**' . "\n";

					if ($data[1] == "rel") {
						if ($data[4] == "m") {
							$settergetter .= ' * @return ' . "\\" . ucfirst($mm[0]) . "\\Model\\" . ucfirst($mm[1]) . "Model[]" . "\n";
						} else {
							$settergetter .= ' * @return ' . "\\" . ucfirst($mm[0]) . "\\Model\\" . ucfirst($mm[1]) . "Model" . "\n";
						}
					} else {
						$settergetter .= '  * @return ' . $ret . '' . "\n";
					}
					$settergetter .= '  */' . "\n";
					$settergetter .= '  public function get' . ucfirst($data[0]) . '() {' . "\n";
					if ($data[1] == "rel") {
						$mm = explode("/", $data[2]);

						if ($data[4] == "m") {
							$settergetter .= '	if (!is_array($this->' . $data[0] . ')) {' . "\n";
							$settergetter .= 'if($this->' . $data[0] . '=="") { $this->' . $data[0] . '=array();return array();}' . "\n";
							$settergetter .= '		$' . $mm[1] . 'Repository = new ' . "\\" . ucfirst($mm[0]) . "\\" . 'Repository' . "\\" . ucfirst($mm[1]) . 'Repository();' . "\n";
							#$settergetter .= '$' . $mm[1] . 'Repository->addWhere( " '.$prefix.'_'.$data[0].' in ( ".$this->'.$data[0].'.")");'."\n";
							$settergetter .= '		$this->set' . ucfirst($data[0]) . '($' . $mm[1] . 'Repository->findAllByPks(explode(",",$this->' . $data[0] . ')));' . "\n";
							$settergetter .= '	}' . "\n";
						} else {

							$settergetter .= '	if (!($this->' . $data[0] . ' instanceof ' . "\\" . ucfirst($mm[0]) . "\\" . 'Model' . "\\" . ucfirst($mm[1]) . 'Model)) {' . "\n";
							#$settergetter .= '		if ((int)$this->'.$data[0].' > 0) {'."\n";
							$settergetter .= '		$' . $mm[1] . 'Repository = new ' . "\\" . ucfirst($mm[0]) . "\\" . 'Repository' . "\\" . ucfirst($mm[1]) . 'Repository();' . "\n";
							$settergetter .= '		$this->set' . ucfirst($data[0]) . '($' . $mm[1] . 'Repository->findByPk($this->' . $data[0] . '));' . "\n";
							#$settergetter .= '	} else {'."\n";
							#$settergetter .= '		$this->setRaum(new '."\\".'classes'."\\".'NullObj());'."\n";
							#$settergetter .= '	}'."\n";
							$settergetter .= '	}' . "\n";
						}

						$settergetter .= '	return $this->' . $data[0] . ";\n";
					} else {
						$settergetter .= '	return $this->' . $data[0] . ";\n";
					}
					$settergetter .= "  }\n\n";

					if ($data[1] == "rel") {
						$settergetter .= '  public function get' . ucfirst($data[0]) . ucfirst($data[3]) . '() {' . "\n";
						if ($data[4] == "m") {
							$settergetter .= '$As = $this->get' . ucFirst($data[0]) . '();' . "\n";
							$settergetter .= '$V = array();' . "\n";
							$settergetter .= 'foreach($As as $A ) {' . "\n";
							$settergetter .= '	$V[] = $A->get' . ucfirst($data[3]) . '();' . "\n";
							$settergetter .= '}' . "\n";
							$settergetter .= 'return implode(", ", $V);' . "\n";
						} else {
							$settergetter .= '		return $this->get' . ucfirst($data[0]) . '()->get' . ucfirst($data[3]) . "();\n";
						}
						$settergetter .= "  }\n\n";

						$settergetter .= '  public function isIn' . ucfirst($data[0]) . '($pk) {' . "\n";
						$settergetter .= '		foreach($this->get' . ucfirst($data[0]) . '() as $one) {' . "\n";
						$settergetter .= '			if($one->getPk()==$pk) return true;' . "\n";
						$settergetter .= '		}' . "\n";
						$settergetter .= '		return false;' . "\n";
						$settergetter .= "  }\n\n";

						$settergetter .= '/**' . "\n";
						$settergetter .= ' * @return ' . "\\" . ucfirst($mm[0]) . "\\Model\\" . ucfirst($mm[1]) . "Model[]" . "\n";
						$settergetter .= ' */' . "\n";
						$settergetter .= '  public function getAllPossible' . ucfirst($data[0]) . '() {' . "\n";
						$settergetter .= '		$' . $mm[1] . 'Repository = new ' . "\\" . ucfirst($mm[0]) . "\\" . 'Repository' . "\\" . ucfirst($mm[1]) . 'Repository();' . "\n";
						$settergetter .= '		return $' . $mm[1] . "Repository->findAll();\n";
						$settergetter .= "  }\n\n";
					}
					$rungetter .= '     $this->set' . ucfirst($data[0]) . '($data["' . $prefix . "_" . strtolower($data[0]) . '"]);' . "\n";

					#$dbtype = $this->getDbType($data[1]);
					#$sql .= ",\n\t\t\t" . $prefix . "_" . strtolower($data[0]) . " " . $dbtype . " NOT NULL";
				}

				if ($data[1] == "subrel") {
					$mm = explode("/", $data[2]);
					$settergetter .= 'public function getAllRelated' . ucfirst($mm[1]) . '() {' . "\n";
					$settergetter .= '	$' . $mm[1] . 'Repository = new \\' . ucfirst($mm[0]) . '\\Repository\\' . ucfirst($mm[1]) . 'Repository();' . "\n";
					$settergetter .= '	$' . $mm[1] . 'Repository->addWhere("' . $data[3] . '=\'".$this->getPk()."\' ");' . "\n";
					$settergetter .= '	return $' . $mm[1] . 'Repository->findAll();' . "\n";
					$settergetter .= '}' . "\n\n";
				}
			}
			#$sql .= "\n ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ";
		}

		$source = file_get_contents(dirname(__FILE__) . "/templates/tpl.vorlage_model.php");
		$source = str_replace("##MODNAME##", ucfirst($mod), $source);
		$source = str_replace("##MODELNAME##", ucfirst($model), $source);
		$source = str_replace("##PREFIX##", $prefix, $source);
		$source = str_replace("##RUNSETTER##", $rungetter, $source);
		$source = str_replace("##VARS##", $vars, $source);
		$source = str_replace("##SETTERGETTER##", $settergetter, $source);

		file_put_contents("modul/" . ucfirst($mod) . '/Model/' . ucfirst($model) . 'Model.php', $source);
		chmod("modul/" . ucfirst($mod) . '/Model/' . ucfirst($model) . 'Model.php', 0664);

		$source = file_get_contents(dirname(__FILE__) . "/templates/tpl.vorlage_repository.php");
		$source = str_replace("##MODNAME##", ucfirst($mod), $source);
		$source = str_replace("##MODELNAME##", ucfirst($model), $source);
		$source = str_replace("##PREFIX##", $prefix, $source);
		$source = str_replace("##TABELLE##", strtolower($model), $source);
		file_put_contents("modul/" . ucfirst($mod) . '/Repository/' . ucfirst($model) . 'Repository.php', $source);
		chmod("modul/" . ucfirst($mod) . '/Repository/' . ucfirst($model) . 'Repository.php', 0664);

		$source = file_get_contents("inc.update.php");
		$source = str_replace("// **AUTOAPPEND**", '$UPDATE_SQL[] = array("type" => "newtable", "table" => "' . strtolower($model) . '", "query" => "' . $sql . '");' . "\n\n// **AUTOAPPEND**", $source);
		file_put_contents("inc.update.php", $source);

		if($_POST["ctrlname"]!="") {
			$ctrl = fixname($_POST["ctrlname"]);

			$this->mkdir("modul/" . ucfirst($mod) . '/Templates/' . ucfirst($ctrl));

			$fn = projectPath . "/modul/" . ucfirst($mod) . '/Templates/' . ucfirst($ctrl) . '/tpl.Index.php';
			if(!file_exists($fn)) {
				$src = file_get_contents(dirname(__FILE__) . "/templates/tpl.crud_index.php");
				$html = "";
				foreach ($felder as $line) {
					if (trim($line) == "") {
						continue;
					}
					$data = explode(",", trim($line));

					if($data[1]=="lang") {
						$html .= '<td><' . '?= $entry->getLangDistinction()->get' . ucfirst($data[0]) . '(); ?' . '></td>' . "\n";
					} else if($data[1]=="rel") {
						$html .= '<td><' . '?= $entry->get' . ucfirst($data[0]) . ucfirst($data[3]) . '(); ?' . '></td>' . "\n";
					} else {
						$html .= '<td><' . '?= $entry->get' . ucfirst($data[0]) . '(); ?' . '></td>' . "\n";
					}

				}
				$src = str_replace("##FORMFIELDS##", $html, $src);
				$src = str_replace("##MODUL##", ucfirst($mod), $src);
				$src = str_replace("##MODEL##", ucfirst($model), $src);
				$src = str_replace("##MODEL2##", $model, $src);
				file_put_contents($fn, $src);
				chmod($fn, 0664);
			}

			$fn = projectPath . "/modul/" . ucfirst($mod) . '/Templates/' . ucfirst($ctrl) . '/tpl.Form.php';
			if(!file_exists($fn)) {

				$src = file_get_contents(dirname(__FILE__) . "/templates/tpl.crud_form.php");
				$html = "";
				foreach ($felder as $line) {
					if (trim($line) == "") {
						continue;
					}
					$data = explode(",", trim($line));
					#vd($data);
					$isLangDistinct = false;
					if($data[1]=="lang") {
						$isLangDistinct = true;
						array_splice($data, 1, 1);
					}
					#vd($data);
					#echo "<hr>";
					/*
					name,vc
					adressen,rel,adressen/adressen,name,s
					 */
					$feldName = $model.'['.$prefix.'_'.$data[0].']';
					if($isLangDistinct) {
						$feldName = $model.'Lang[de]['.$prefix.'l_'.$data[0].']';
					}

					if ($data[1] == "subrel") {

						include dirname(__FILE__).'/templates/tpl.formpart_subrel.php';

					} else if ($data[1] == "rel") {

						include dirname(__FILE__).'/templates/tpl.formpart_rel.php';

					} else if ($data[1] == "i" || $data[1] == "ti") {

						include dirname(__FILE__).'/templates/tpl.formpart_int.php';

					} else if ($data[1] == "dt" || $data[1] == "d") {

						include dirname(__FILE__).'/templates/tpl.formpart_date.php';

					} else if ($data[1] == "t") {

						include dirname(__FILE__).'/templates/tpl.formpart_time.php';

					} else if ($data[1] == "f") {

						include dirname(__FILE__).'/templates/tpl.formpart_file.php';

					} else {

						include dirname(__FILE__).'/templates/tpl.formpart_input.php';
					}

				}



				$src = str_replace("##FORMFIELDS##", $html, $src);
				$src = str_replace("##MODUL##", ucfirst($mod), $src);
				$src = str_replace("##MODEL##", ucfirst($model), $src);
				$src = str_replace("##MODEL2##", $model, $src);
				file_put_contents($fn, $src);
				chmod($fn, 0664);
			}


			$_POST["newControllerType"] = "crud";
			$this->view_newctrl($QS, true);
		}

		#vd($langFelder);exit;
		if(count($langFelder)>0) {
			unset($_POST["ctrlname"]);
			$origPrefix = $_POST["modelshort"];
			$_POST["modelname"] = $_POST["modelname"]."Lang";
			$_POST["modelshort"] = $_POST["modelshort"]."L";
			$_POST["modelfelder"] = implode("\n", $langFelder);
			$this->view_newmodel($QS, $origPrefix);
		}

		jump2page("*/console");
	}

	private function mkdir($path) {
		$full = projectPath . '/' . $path;
		if (!file_Exists($full)) {
			mkdir($full, 0775, TRUE);
			chmod($full, 0775);
		}
	}

	public function view_designer($QS) {
		if (!file_exists(projectPath . '/designer') || !is_writable(projectPath . '/designer')) {
			die("create writable folder " . projectPath . '/designer');
		}
		$tpl = $this->newTpl();

		if (file_exists(projectPath . '/designer/screens.json')) {
			$screens = file_get_contents(projectPath . '/designer/screens.json');
		} else {
			$screens = "[]";
		}
		$tpl->setVariable("screens", $screens);

		echo $tpl->get("tpl.designer.php");
		exit;
	}

	public function ajax_save($QS) {
		file_put_contents(projectPath . '/designer/screens.json', json_encode($_POST["screens"]));
	}

}

?>