<?php
namespace classes;

/**
 */
class Utils {

	static public function intoOneLine($str) {

		$str = str_replace("\n", " ", $str);
		$str = str_replace("\r", " ", $str);
		$str = trim($str);

		return $str;
	}

	/**
	 * @param string $date
	 * @param boolean $htmlspecialchars
	 * @return string
	 */
	static public function prepareForSaving($data, $htmlspecialchars = true) {
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				$data[$key] = self::prepareForSaving($value, $htmlspecialchars);
			}
		}
		else {
			$data = trim($data);
			if ($htmlspecialchars) {
				$data = htmlspecialchars($data);
			}
		}

		return $data;
	}

	/**
	 * Gibt egal bei welchem Datumsformat immer das Format YYYY-mm-dd zurück
	 *
	 * @param string $date
	 * @return string
	 */
	static public function getIsoDate($date) {
		$date = trim($date);
		if (strstr($date, '.')) {
			$parts = explode(".", $date);
			$parts[2] = self::expandYear($parts[2]);
			$date = sprintf("%04s", $parts[2]) . '-' . sprintf("%02s", $parts[1]) . '-' . sprintf("%02s",$parts[0]);
		} elseif (strstr($date, '/')) {
			$parts = explode("/", $date);
			$parts[2] = self::expandYear($parts[2]);
			$date = sprintf("%04s",$parts[2]) . '-' . sprintf("%02s",$parts[0]) . '-' . sprintf("%02s",$parts[1]);
		} elseif (strstr($date, '-')) {
			$parts = explode("-", $date);
			$parts[0] = self::expandYear($parts[0]);
			$date = sprintf("%04s",$parts[0]) . '-' . sprintf("%02s",$parts[1]) . '-' . sprintf("%02s",$parts[2]);
		} else return "";

		$test = explode("-", $date);
		//vd($test);exit;
		if($test[0]<1000 || $test[0]>2100) return "";
		if((int)$test[1]<1 || (int)$test[1]>12) return "";
		if((int)$test[2]<1 || (int)$test[2]>31) return "";


		return $date;
	}

	/**
	 * Jahresangabe sinnvoll ergänzen, wenn nur 2 Ziffern eingegeben werden.
	 * @param $year
	 *
	 * @return string
	 */
	static function expandYear($year) {
		if($year<100) {
			if($year<date("y")+15) {
				$year = "20".$year;
			} else {
				$year = "19".$year;
			}
		}
		return $year;
	}

	static public function niceDate($date) {
		if (strstr($date, '.')) {
			$parts = explode(".", $date);
			$parts[2] = self::expandYear($parts[2]);
			$date = sprintf("%02s", $parts[0]).".".sprintf("%02s", $parts[1]).".".sprintf("%04s", $parts[2]);
		} elseif (strstr($date, '/')) {
			$parts = explode("/", $date);
			$parts[2] = self::expandYear($parts[2]);
			$date = sprintf("%02s", $parts[0])."/".sprintf("%02s", $parts[1])."/".sprintf("%04s", $parts[2]);
		}
		return $date;
	}

	/**
	 * Ändert ein Datum im Format YYYY-mm-dd in ein gewünschtes anderes Format
	 *
	 * @param string $date
	 * @return string
	 */
	static public function changeIsoDate($date, $type = 'de') {
		if (strstr($date, '-')) {
			switch ($type) {
				case 'en':
					$date = substr($date, 5, 2) . '/' . substr($date, 8, 2) . '/' . substr($date, 0, 4);
					break;

				case 'de':
				default:
					$date = substr($date, 8, 2) . '.' . substr($date, 5, 2) . '.' . substr($date, 0, 4);
					break;
			}
		}

		return $date;
	}

	/**
	 * Gibt immer das Zeitformat HH:mm zurück
	 *
	 * @param string $time
	 * @return string
	 */
	static public function formatTime($time) {
		if (strstr($time, ':')) {
			$expl = explode(':', $time);
			$hours = substr($expl[0], 0, 2);
			$minutes = substr($expl[1], 0, 2);
		}
		else {
			$hours = substr($time, 0, 2);
			$minutes = substr($time, 2, 2);
		}

		while (strlen($hours) < 2) {
			$hours = '0'.$hours;
		}
		while (strlen($minutes) < 2) {
			$minutes = '0'.$minutes;
		}
		$time = $hours.':'.$minutes;

		return $time;
	}

	/**
	 * @param string $datetime
	 * @return string
	 */
	static public function formatTimeByDateTime($datetime) {
		return static::formatTime(substr($datetime, 11));
	}

	/**
	 * @param array $array
	 * @return bool
	 */
	static public function shuffle_assoc(&$array) {
		$keys = array_keys($array);
		shuffle($keys);
		foreach($keys as $key) {
			$new[$key] = $array[$key];
		}
		$array = $new;
		return true;
	}

	/**
	 * @return integer
	 */
	static public function getUnixTimestampByDatetime($datetime) {
		$Y = (int)substr($datetime, 0, 4);
		$M = (int)substr($datetime, 5, 2);
		$D = (int)substr($datetime, 8, 2);
		$h = (int)substr($datetime, 11, 2);
		$m = (int)substr($datetime, 14, 2);
		$s = (int)substr($datetime, -2);

		return mktime($h, $m, $s, $M, $D, $Y);
	}

	/**
	 * @return integer
	 */
	static public function getUnixTimestampByDate($date) {
		return static::getUnixTimestampByDatetime($date.' 00:00:00');
	}

	/**
	 * @param string $str
	 * @return string
	 */
	static public function fixString($str) {
		$str = trim($str);
		$str = str_replace(' ', '-', $str);
		$str = str_replace('ä', 'ae', $str);
		$str = str_replace('ö', 'oe', $str);
		$str = str_replace('ü', 'ue', $str);
		$str = str_replace('Ä', 'Ae', $str);
		$str = str_replace('Ö', 'Oe', $str);
		$str = str_replace('Ü', 'Ue', $str);
		$str = str_replace('ß', 'ss', $str);
		$str = mb_strtolower($str);
		$str = str_replace(',', '_', $str);
		$str = str_replace('&', '_', $str);
		$str = str_replace('/', '_', $str);
		$str = str_replace('?', '_', $str);
		$str = str_replace('#', '_', $str);
		$str = str_replace(';', '_', $str);
		$str = str_replace(':', '_', $str);
		$str = preg_replace('/[^a-z0-9_\-.]/i', '_', $str);

		while (stristr($str, '__')) {
			$str = str_replace('__','_',$str);
		}

		return $str;
	}


	static public function zipFiles(array $filesArray, $zipFilename, $deliver=true) {

		$E = str_replace("/", "\\", projectPath)."\\vendor\\zip\\7z.exe a ".$zipFilename;
		for($i=0;$i<count($filesArray);$i++) {
			$E .= " ".$filesArray[$i];
		}
		$E = str_replace("/", "\\", $E);

		if(file_exists(projectPath."/vendor/cmd.exe")) {
			$E = str_replace("/", "\\", projectPath)."\\vendor\\cmd.exe /c \"".$E."\"";
		} else {
			$E = "c:\\windows\\system32\\cmd.exe /c \"".$E."\"";
		}

		exec($E);
		if($deliver) {
			deliverFile($zipFilename, basename($zipFilename));
			exit;
		}
		return $zipFilename;
	}

	static public function exec($command) {
		#vd($command);exit;
		// " . projectPath . "\\vendor\\imagemagick\\convert.exe -density 300 -background white -alpha remove " . str_replace("/", "\\", $fn) . " " . str_replace("/", "\\", $cacheFN) . "
		if (file_exists(projectPath . "/vendor/cmd.exe")) {
			$E = str_replace("/", "\\", projectPath) . "\\vendor\\cmd.exe /c \"".$command."\"";
		} else {
			$E = "c:\\windows\\system32\\cmd.exe /c \"".$command."\"";
		}

		$result = exec($E);
		return $result;
	}


	static public function avoidPageBreak($prompt) {
		#$prompt = str_replace('<table>', '<div style="page-break-inside:avoid;"><table>', $prompt);
		#$prompt = str_replace('<table ', '<div style="page-break-inside:avoid;"><table ', $prompt);
		#$prompt = str_replace('</table>', '</table></div>', $prompt);

		$prompt = str_replace('<table>', '<div style="page-break-inside:avoid;"><table>', $prompt);
		$prompt = str_replace('<table ', '<div style="page-break-inside:avoid;"><table ', $prompt);
		$prompt = str_replace('</table>', '</table></div>', $prompt);
		return $prompt;
	}

}

?>