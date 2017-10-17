<?php

namespace classes;

/**
 */
class FileUtils {

	static public function glob($pattern, $flags=null) {
		$A = glob($pattern, $flags);
		if($A=="") $A = array();
		return $A;
	}

	/**
	 * @param string $file Quellfile-Name, meist der upload-Temp-Name
	 * @param string $area Der Bereich in den die Datei gehört
	 * @param string $path
	 *
	 * @return string
	 */
	static public function moveUploadedFile($file, $area, $path, $suffixPath="") {
		#vdf(getTrace());
		$folderSuffix = self::getAreaFolder($area, $suffixPath);
		$destination = FILE_UPLOAD_FOLDER . '/' . $folderSuffix . '/' . $path;

		$newPath = self::getUniquePathByFilePath($destination);
		$res = move_uploaded_file($file, $newPath);
		#vdf(array($file, $newPath));
		if($res==FALSE && !file_exists($newPath)) {
			rename($file, $newPath);
		}
		chmod($newPath, 0664);
		$newFilename = basename($newPath);

		return $folderSuffix . '/' . $newFilename;
	}

	/**
	 * @param string $file
	 * @param string $area
	 * @param string $path
	 *
	 * @return string
	 */
	static public function copyFile($file, $area, $path) {
		$folderSuffix = self::getAreaFolder($area);
		$destination = FILE_UPLOAD_FOLDER . '/' . $folderSuffix . '/' . $path;
		$newPath = self::getUniquePathByFilePath($destination);
		copy($file, $newPath);
		chmod($newPath, 0664);
		$newFilename = basename($newPath);

		return $folderSuffix . '/' . $newFilename;
	}

	static public function getCacheFolder() {
		self::createSubPaths(FILE_UPLOAD_FOLDER . '/cache' );
		return "uploads/cache";
	}

	/**
	 * @param string $area
	 *
	 * @return string
	 */
	static function getAreaFolder($area, $suffixPath="") {
		$folderSuffix = $area . '/' . date('Y') . '/' . date('m'). '/' . date('d');
		if($suffixPath!="") {
			$folderSuffix .= "/".$suffixPath;
		}

		self::createSubPaths(FILE_UPLOAD_FOLDER . '/' . $folderSuffix);

		return $folderSuffix;
	}

	static function createSubPaths($path) {
		if (!file_exists($path)) {
			mkdir($path, 0775, TRUE);
			chmod($path, 0775);
		}
	}

	/**
	 * @param string $path
	 *
	 * @return string
	 */
	static public function getUniquePathByFilePath($path) {
		$fileName = self::fixFileName(basename($path));
		$dirName = dirname($path);

		$fileEnding = '';
		if (strstr($fileName, '.')) {
			$fileEnding = substr($fileName, strrpos($fileName, '.') + 1);
			$fileName = substr($fileName, 0, strrpos($fileName, '.'));
		}
		$newFileName = $fileName . ($fileEnding != '' ? '.' . $fileEnding : '');

		$counter = 1;
		while (file_exists($dirName . '/' . $newFileName)) {
			$newFileName = $fileName . '(' . $counter . ')' . ($fileEnding != '' ? '.' . $fileEnding : '');
			$counter++;
		}
		return $dirName . '/' . $newFileName;
	}

	/**
	 * @param string $str
	 *
	 * @return string
	 */
	static public function fixFileName($str) {
		$str = trim($str);
		$str = str_replace(' ', '-', $str);
		
		/*
		$str = str_replace('ä', 'ae', $str);
		$str = str_replace('ö', 'oe', $str);
		$str = str_replace('ü', 'ue', $str);
		$str = str_replace('Ä', 'ae', $str);
		$str = str_replace('Ö', 'oe', $str);
		$str = str_replace('Ü', 'ue', $str);
		$str = str_replace('ß', 'ss', $str);
		*/
		$str = str_replace('&',$replaceChar,$str);
		$str = str_replace(',',$replaceChar,$str);
		$str = str_replace('/',$replaceChar,$str);
		$str = str_replace('?',$replaceChar,$str);
		$str = str_replace('!',$replaceChar,$str);
		$str = str_replace('#',$replaceChar,$str);
		$str = str_replace(';',$replaceChar,$str);
		$str = str_replace(':',$replaceChar,$str);
		
		//$str = preg_replace('/[^a-zA-Z0-9_\-.]/i', '_', $str);

		while (stristr($str, '__')) {
			$str = str_replace('__', '_', $str);
		}

		return $str;
	}

	/**
	 * @param string $fileName
	 *
	 * @return string
	 */
	static public function getFileExtension($fileName) {
		return strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
	}

	/**
	 * @param $fileName
	 *
	 * @return string
	 */
	static public function getFileDownloadLink($fileName) {
		#if(stristr($fileName, "cache/")) {
		#	$pre = "";
		#} else {
			$pre = "uploads/";
		#}
		if (file_exists(projectPath . '/' . $pre. $fileName)) {
			$md5 = md5($fileName);
			setS('fileDownloadLink'.$md5, $pre.$fileName);
			return getLink("Index/file/".$md5);
		}
	}

	static public function addBeforeExt($fn, $add) {
		$fn2 = substr($fn,0,strrpos($fn,".")).$add.substr($fn,strpos($fn,"."));
		return $fn2;
	}

	/**
	 * @param string $dir
	 *
	 * @return boolean
	 */
	static public function rmDirRecursive($dir) {
		$files = array_diff(scandir($dir), array('.', '..'));
		foreach ($files as $file) {
			if (is_dir($dir . '/' . $file)) {
				self::rmDirRecursive($dir . '/' . $file);
			} else {
				unlink($dir . '/' . $file);
			}
		}

		return rmdir($dir);
	}

	static public function deliverFile($a_file, $a_filename = NULL, $delete_file = FALSE) {

		if ($a_filename === NULL) {
			$a_filename = basename($a_file);
		}

		$disposition = "inline"; // "inline" to view file in browser or "attachment" to download to hard disk

		switch(self::getFileExtension($a_filename)=="pdf") {
			case 'pdf':
				$mime = "application/pdf";
				break;
			default:
				$mime = "application/octet-stream"; // or whatever the mime type is
		}


		if (isset($_SERVER["HTTPS"])) {
			header("Pragma: ");
			header("Cache-Control: ");
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
			header("Cache-Control: post-check=0, pre-check=0", FALSE);
		} else {
			if ($disposition == "attachment") {
				header("Cache-control: private");
			} else {
				header("Cache-Control: no-cache, must-revalidate");
				header("Pragma: no-cache");
			}
		}

		header("Content-Type: $mime");
		header("Content-Disposition:$disposition; filename=\"" . $a_filename . "\"");
		header("Content-Description: " . $a_filename);
		header("Content-Length: " . (string)(filesize($a_file)));
		header("Connection: close");

		readfile($a_file);

		if ($delete_file) {
			@unlink($a_file);
		}

		exit();
	}

	static public function getTempFolder() {
		$tmp = projectPath.'/cache/tmp_'.microtime(true);
		mkdir($tmp, 0775, true);
		chmod($tmp, 0775);
		return $tmp;
	}

}

?>