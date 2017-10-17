<?php


function transFull($fullText = '', $data = array(), $lang = '') {

	if (strstr($fullText, "|")) {
		$area = str_bis($fullText, "|");
		$fullText = str_nach($fullText, "|");
	} else {
		$area = "global";
	}

	$label = strtolower(substr(strip_tags($fullText), 0, 20));
	$label = str_Replace(array('Ã¤', 'Ã', 'Ã¶', 'Ã', 'Ã¼', 'Ã', 'Ã'), array('ae', 'Ae', 'oe', 'Oe', 'ue', 'Ue', 'ss'), $label);
	#$label = str_Replace(array('ä', 'Ä', 'ö', 'Ö', 'ü', 'Ü', 'ß'), array('ae', 'Ae', 'oe', 'Oe', 'ue', 'Ue', 'ss'), $label);
	$label = preg_replace('/[^a-z0-9_\-.]/i', '_', $label);
	$label .= '_'.md5($fullText);

	return trans($area.'|'.$label, $data, $lang, $fullText);
}

function trans($label = '', $replaceData = array(), $lang = '', $originalText='') {
	global $languageData;
	if(1==2 && me()->getPk()==9 && stristr($_SERVER["HTTP_HOST"],".qs.")) {
		if(!defined("MARK_TRANSLATEDFIELDS")) {
			define("MARK_TRANSLATEDFIELDS", 1);
		}
	}

	$fullText = $originalText;
	
	if(defined("unittesting") && unittesting==1) return $fullText;

	#static $languageData = array();
	if ($lang == '') {
		$lang = $_SESSION['lang'];
	}
	

	if ($lang == '') {
		if(defined("defaultLanguage")) {
			$lang = defaultLanguage;
		}
	}
	if ($lang == '') {
		if(defined("defaultLanguage")) $lang=defaultLanguage;
		else $lang = "de";
	}
	$lang = strtolower($lang);



	if (strstr($label, "|")) {
		$area = str_bis($label, "|");
		$label = str_nach($label, "|");
	} else {
		$area = "global";
	}
	$area = strtolower($area);

	$orig = $label;

	if(defined("MARK_TRANSLATEDFIELDS")&&MARK_TRANSLATEDFIELDS)
	{
		return '##'.strtoupper($fullText).'##';
	}

	#if($originalText!="") return $originalText;
	#if($label!="") return str_replace('_', ' ', $orig);

	if($originalText!="") {
		$originalText = str_replace("\n", " ", $originalText);
		$originalText = str_replace("\r", " ", $originalText);
		$orig = $originalText;
	}

	$label = str_Replace(array('Ã¤', 'Ã', 'Ã¶', 'Ã', 'Ã¼', 'Ã', 'Ã'), array('ae', 'Ae', 'oe', 'Oe', 'ue', 'Ue', 'ss'), $label);
	$label = strtolower($label);
	$label = preg_replace('/[^a-z0-9_\-.]/i', '_', $label);

	if (!isset($languageData[$lang][$area])) {
		$languageData[$lang][$area] = array();
		$filename = projectPath . '/cache/language_' . $lang . '_' . $area . '.lng';
		if (file_exists($filename)) {
			#vd($filename);#exit;
			#error_reporting(-1);
			include_once $filename;
			#vd(file_get_contents($filename));
			#if($GLOBALS["DO"]==1) {echo "\n";vd($filename);echo "\n"; vd($lang);echo "\n"; vd($area); echo "\n";vd($label); echo "\n";vd($GLOBALS["languageData"]);exit; };

		} else {
			$Q = "SELECT * FROM translations WHERE tr_language='" . addslashes($lang) . "' AND tr_area='" . addslashes($area) . "' AND tr_deleted=0 AND tr_hidden=0 ";
			//vd($Q);exit;
			$translations = $GLOBALS["FW"]->DC->getAllByQuery($Q);
			$code = "<" . "?php\n";
			$code .= "// Created: " . date("d.m.Y H:i:s") . "\n";
			for ($i = 0; $i < count($translations); $i++) {
				$code .= '$GLOBALS["languageData"]["' . $lang . '"]["' . $area . '"]["' . $translations[$i]["tr_label"] . '"] = "' . str_replace('"', '&quot;', $translations[$i]["tr_translation"]) . '";' . "\n";
			}
			$code .= "\n?" . ">";
			if (is_writable(dirname($filename))) {
				file_put_contents($filename, $code);
				chmod($filename, 0664);
			} else {
			}

			include $filename;
		}
	}

	if (isset($languageData[$lang][$area]) && $languageData[$lang][$area][$label] != "") {
		#echo "X";exit;
		$res = $languageData[$lang][$area][$label];

	} else {
		if($_GET["x"]==1) {
			#var_dump($lang);
			#var_dump(array($lang,$area,$label,defaultLanguage));exit;
		}
		#vd($label);exit;
		if (!isset($languageData[$lang][$area][$label])) {
			if(1==1 || $lang==defaultLanguage) {
				$data = array(
					"tr_createdate"  => now(),
					"tr_changedate"  => now(),
					"tr_deleted"     => 0,
					"tr_hidden"      => 0,
					"tr_area"        => $area,
					"tr_label"       => strtolower($label),
					"tr_language"    => "de",
					"tr_translation" => str_replace('_', ' ', $orig),
				);
				if ($GLOBALS["FW"]->DC->countByQuery("SELECT count(*) FROM translations WHERE tr_language='de' AND tr_area='" . addslashes($area) . "' AND LOWER(tr_label)='" . addslashes(strtolower($label)) . "' AND tr_deleted=0 AND tr_hidden=0 ") == 0) {
					$GLOBALS["FW"]->DC->insert($data, "translations");
					if (file_exists($filename)) {
						@unlink($filename);
					}
					unset($languageData[$lang][$area]);
				}

				#unset($languageData[$lang][$area]);
			}
			/*
			if($lang==defaultLanguage) {
				$data = array(
					"tr_createdate"  => now(),
					"tr_changedate"  => now(),
					"tr_deleted"     => 0,
					"tr_hidden"      => 0,
					"tr_area"        => $area,
					"tr_label"       => strtolower($label),
					"tr_language"    => $lang,
					"tr_translation" => str_replace('_', ' ', $orig),
				);
				if ($GLOBALS["FW"]->DC->countByQuery("SELECT count(*) FROM translations WHERE tr_language='" . addslashes($lang) . "' AND tr_area='" . addslashes($area) . "' AND LOWER(tr_label)='" . addslashes(strtolower($label)) . "' AND tr_deleted=0 AND tr_hidden=0 ") == 0) {
					$GLOBALS["FW"]->DC->insert($data, "translations");
				}

				if (file_exists($filename)) {
					@unlink($filename);
				}
				unset($languageData[$lang][$area]);
			}
			*/
		}

		$res = $orig;
		#return '##' . $area . "|" . $label . '##';
	}

	for($i=count($replaceData)-1;$i>=0;$i--) {
		$res = str_replace("%".$i, $replaceData[$i], $res);
	}
	return $res;
}

?>