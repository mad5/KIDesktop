<?php

function isNullObj($obj) {
	if($obj===null) return true;
	if($obj instanceof \classes\NullObj) return true;
	return false;
}

function glob2( $pattern, $flags = 0) {
	$G = glob($pattern, $flags);
	if($G=="") $G = array();
	return $G;
}

function writeJson($filename, $json) {
	file_put_contents($filename, json_encode($json));
	chmod($filename, 0664);
}
function readJson($filename, $default=array()) {
	if(file_exists($filename)) {
		$default = json_decode(file_get_contents($filename), true);
	}
	return $default;
}



function readContent($fn, $default="") {
	if(file_exists($fn) && is_readable($fn)) {
		return file_get_contents($fn);
	} else {
		return $default;
	}
}

function loggedIn() {
	return \Backenduser\Service\ActiveBackenduserService::isLoggedIn();
}

function hsc($X) {
	return htmlspecialchars($X);
}


function now($add=0) {
	// {{{

	#return "2014-11-11 11:11:11";
	return date('Y-m-d H:i:s', _TIME+$add);
	// }}}
}

function formatFileSize($size) {
	$p = "B";
	if($size>1024) {
		$size /= 1024;
		$p = "KB";
		if($size>1024) {
			$size /= 1024;
			$p = "MB";
			if($size>1024) {
				$size /= 1024;
				$p = "GB";
			}
		}
	}
	$size = number_format($size,2,",",".");
	return $size."&nbsp;".$p;
}

function formatDate($D) {
        if(!isset($D) || $D=='' || substr($D,0,10)=='0000-00-00') return '';
	$d = explode("-", substr($D,0,10));
	$dx = $d[2].'.'.$d[1].'.'.$d[0];
	return $dx;
}
function formatDateTime($D) {
	$t = substr($D,11);
	$d = explode("-", substr($D,0,10));
	$dx = $d[2].'.'.$d[1].'.'.$d[0]." ".$t;
	return $dx;
}
function formatTime($D) {
	if($D=='') return '';
	$t = substr($D,0,5);
        if($t=='00:00') return '';
	return $t;
}

function getWochentag($date, $short=true) {
	$T = strtotime($date);
	$W = date("w", $T);
	return formatWochentag($W, $short);
}

function formatWochentag($nr, $short=true) {
    if($nr>6) $nr -= 7;
    if(defined('DateLanguage') && DateLanguage=='en') {
	$_DAYS_short = array('Su','Mo','Tu','We','Th','Fr','Sa');
	$_DAYS_long = array('Sunday', 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
    } else {
	$_DAYS_short = array('So','Mo','Di','Mi','Do','Fr','Sa');
	$_DAYS_long = array('Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag');
    }
    if($short==true) return $_DAYS_short[$nr];
    
    return $_DAYS_long[$nr];
}

function getMonthName($m, $short=false) {
    $m = (int)$m;
    while($m<1) $m+=12;
    while($m>12) $m-=12;
    if($short) {
	    $MN["de"] = array("Jan", "Feb", "Mär", "Apr", "Mai", "Juni", "Juli", "Aug", "Sept", "Okt", "Nov", "Dez");
	    $MN["en"] = array("Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec");
    } else {
	    $MN["de"] = array("Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember");
	    $MN["en"] = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    }
	    
    if(defined('DateLanguage') && DateLanguage=='en') {
        $M = $MN["en"];
    } else {
        $M = $MN["de"];
    }
    
    return $M[(int)$m-1];
}

define('_TIME', time());
define('_DATE0', '0000-00-00 00:00:00');
define('_DATE_today', date('Y-m-d'));
define('_DATE_yesterday', date('Y-m-d', _TIME-60*60*24));
define('_DATE_beforeyesterday', date('Y-m-d', _TIME-60*60*24*2));
define('_DATE_7days', _TIME-60*60*24*7);
define('_DATE_4days', _TIME-60*60*24*4);
define('_DATE_14days', _TIME-60*60*24*14);
if(defined('DateLanguage') && DateLanguage=='en') {
    $_DAYS_short = array('Su','Mo','Tu','We','Th','Fr','Sa');
    $_DAYS_long = array('Sunday', 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
} else {
    $_DAYS_short = array('So','Mo','Di','Mi','Do','Fr','Sa');
    $_DAYS_long = array('Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag');
}
function formatDateHuman($D) {
	// {{{
	global $_DAYS_short, $_DAYS_long;

        if($D==_DATE0)  return '-';

    if(defined('DateLanguage') && DateLanguage=='en') {
	$_DAYS_short = array('Su','Mo','Tu','We','Th','Fr','Sa');
	$_DAYS_long = array('Sunday', 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
    } else {
	$_DAYS_short = array('So','Mo','Di','Mi','Do','Fr','Sa');
	$_DAYS_long = array('Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag');
    }
        
	$t = substr($D,11,5);
	$day = substr($D,0,10);
	$d = explode("-", $day);
	
	$stt = strtotime($D);
	
        $tag = 'vor wenigen Sekunden ';
	/*if($stt>_TIME) {
		$tag = 'IN DER ZUKUNFT! ';
	} else */if($stt>=_TIME-60) {
		$tag = 'vor weniger als einer Minute ';
	} else if($stt>_TIME-60*60*6) {
		$diffMin = round((_TIME-$stt)/60);
		if($diffMin>=60) {
			$h = floor($diffMin/60);
			if($h==1) {
				$m = $diffMin - $h*60;
				$tag = ' vor einer Stunde ';
				if($m!=0) $tag .= 'und '.$m.' Minuten';
			} else $tag = ' vor '.$h.' Stunden ';
		} else {
			if($diffMin==1) $tag = ' vor einer Minute '; 
			else $tag = ' vor '.$diffMin.' Minuten ';
		}
	} else if($day==_DATE_today) $tag = 'Heute '.($t!='' ? ' um '.$t.' Uhr' : '');
	else if($day==_DATE_yesterday) $tag = 'Gestern '.($t!='' ? ' um '.$t.' Uhr' : '');
	else if($day==_DATE_beforeyesterday) $tag = 'Vorgestern '.($t!='' ? ' um '.$t.' Uhr' : '');
	else if($stt>_DATE_4days && $stt<=_DATE_7days) $tag = ' am '.$_DAYS_long[date('w',$stt)].($t!='' ? ' um '.$t.' Uhr' : '');
	else if($stt>_DATE_7days) $tag = ' am letzten '.$_DAYS_long[date('w',$stt)].($t!='' ? ' um '.$t.' Uhr' : '');
	else if($stt>_DATE_14days) $tag = ' am '.$d[2].'.'.$d[1].'. ('.$_DAYS_short[date('w',$stt)].')'.($t!='' ? ' um '.$t.' Uhr' : '');
	else $tag = ' am '.$d[2].'.'.$d[1].'.'.$d[0].' ('.$_DAYS_short[date('w',$stt)].')';
	return($tag);
	// }}}
}

function formatDateSimple($D, $withTime=false) {
    if($D==_DATE0)  return '-';
    $D2 = explode('-', substr($D,0,10));
    $date = $D2[2].'.'.$D2[1].'.'.$D2[0];
    if(substr($D,0,10)==_DATE_today) return 'Heute'.($withTime ? ' ('.substr($D,11,5).')' : '');
    if(substr($D,0,10)==_DATE_yesterday) return 'Gestern'.($withTime ? ' ('.substr($D,11,5).')' : '');
    if(substr($D,0,10)==_DATE_beforeyesterday) return 'Vorgestern'.($withTime ? ' ('.substr($D,11,5).')' : '');
    return $date;
}

function formatDateExact($D) {
    if($D==_DATE0)  return '-';
    $D2 = explode('-', substr($D,0,10));
    $date = $D2[2].'.'.$D2[1].'.'.$D2[0];
    $t = substr($D,11,5);
    return $date." ".$t;
}

function fullLink($path='', $useGotoParam=true, $basefile = '') {
	$L = getLink($path, $useGotoParam, $basefile);

	$DPS = dirname($_SERVER['PHP_SELF']);
	if($DPS=='/') $DPS = '';
	$L = ( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']!='') ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . (defined('addPrefixPath') ? '/'.addPrefixPath : '' ) . $DPS .'/' .$L;
	return $L;
}


$getLinkCache = array();

function getAjaxLink($path, $useGotoParam=true, $basefile = '') {
    $L = getLink($path, $useGotoParam, $basefile, false);
    $L .= 'fw_ajax=1&';
    return $L;
}

function getLink($path='', $useGotoParam=true, $basefile = '', $amp=false) {
	// {{{
	global $getLinkCache;
	$ckey = $path."_".($useGotoParam ? 1 :0).$basefile;
	if(isset($getLinkCache[$ckey])) return($getLinkCache[$ckey]);

	$path = getGoto($path);

	if(stristr($path,"&")) {
		$useGotoParam = true;
	}
	
	if($useGotoParam) {
		if(rewriteLinks===true) {
			$path = '' . $path . '&';
		} else {
			$path = 'fw_goto=' . $path . '&';
		}
	}
	 
	$PS = dirname($_SERVER['PHP_SELF']);
	if($PS=='/') $PS='';

	if(rewriteLinks===true) {
		$L = $path;
	} else {
		if ($basefile == '') {
			$basefile = basename($_SERVER['PHP_SELF']);
		}
		$L = $basefile . '?' . $path;
	}

    if($GLOBALS['helperFastFW']->clientProxy!='') $L = $GLOBALS['helperFastFW']->clientProxy.'?'.$path;

    if($amp) {
       	$L = str_replace("&", "&amp;", $L);
       	$L = str_replace("&amp;amp;", "&amp;", $L);
    }
        
	$getLinkCache[$ckey] = $L;
	return($L);
	// }}}
}

function getGoto($path) {
	$P = explode('/', $path);

	for($i=0;$i<count($P);$i++) {
		if(isset($P[$i]) && $P[$i]=='*') $P[$i] = $GLOBALS['helperFastFW']->QS[$i];
	}

	$path = implode($P,'/');

	return $path;
}


function jump2page($path, $useGotoParam=true) {
	// {{{
	
	$L = getLink($path, $useGotoParam);
	#vd($L);exit;
	if(defined("baseHref") && baseHref!="") {
		header('location: ' . baseHref.$L);
	} else {
		header('location: ' . $L);
	}
	
	$GLOBALS["FastFW"]->redirectAfterRun = $path;
	
	$L = getLink($path, $useGotoParam);
	#vd($L);exit;
	if(defined("baseHref") && baseHref!="") {
		header('location: ' . baseHref.$L);
	} else {
		header('location: ' . $L);
	}
	
	return;

	$L = getLink($path, $useGotoParam);
	#vd($L);exit;
	if(defined("baseHref") && baseHref!="") {
		header('location: ' . baseHref.$L);
	} else {
		header('location: ' . $L);
	}
	exit;
	// }}}
}

function setS($name, $value, $until=-1) {
	// {{{
	$_SESSION['fastfw'][$name] = $value;
	if($until==-1) $until = 60*60*24*365;
	$_SESSION['fastfw_until'][$name] = _TIME+$until;
	// }}}
}
function getS($name, $field='') {
	// {{{
	if(isset($_SESSION['fastfw_until'][$name]) && $_SESSION['fastfw_until'][$name]>0 && $_SESSION['fastfw_until'][$name]<_TIME) return('');
	$value = '';
	if(isset($_SESSION['fastfw'][$name])) $value = $_SESSION['fastfw'][$name];
	if($field=='') return($value);
	else {
		if(isset($value[$field])) return($value[$field]);
		return '';
	}
	// }}}
}

function checkForArray($X) {
	// {{{
	if(!is_array($X)) $X = array();
	return($X);
	// }}}
}

function getExt($fn) {
	$ext = strtolower(substr($fn,strrpos($fn,".")+1));
	return $ext;
}

function array_sort($array, $key, $direction="asc") {
	if(!is_array($array)) return;
        $sort_values = array();
	for ($i = 0; $i < sizeof($array); $i++) {
		$sort_values[$i] = $array[$i][$key];
	}
	#$sort_values = ($sort_values);
	if(!is_array($sort_values)) return;
	if($direction=="asc") asort ($sort_values); else arsort($sort_values);
        $sorted_arr = array();
	if(is_array($sort_values)) {
		reset ($sort_values);
		while (list ($arr_key, $arr_val) = each ($sort_values)) {
			$sorted_arr[] = $array[$arr_key];
		}
	}
	return $sorted_arr;
}

function getFiles($dir, $type="dir,file", $ext="", $excludes=array(), $sort="name", $sortdir = "asc") {
	// {{{
	if ($ext != "" && !is_array($ext)) {
		$ext = array($ext);
	}

	$F = array();
	$handle=opendir($dir);
	while (false !== ($file = readdir ($handle))) {
		
		if($file!="." && $file!=".." && !in_array($file, $excludes)) {
			
			$ext2 = strtolower(substr($file,strrpos($file,".")+1));
			if(	(stristr($type,"file") && is_file($dir."/".$file)) ||
				(stristr($type,"dir") && is_dir($dir."/".$file)) 
				) {
					if(!is_array($ext) || in_array($ext2, $ext) || count($ext)==0) {
						if(is_file($dir."/".$file)) $fs = filesize($dir."/".$file);
						if(is_file($dir."/".$file)) $type2 = "file"; else {$type2="dir";$ext2="";}
						$F[] = array(	"name"=>$file,
								"path" =>$dir,
								"file" =>$dir."/".$file,
								"size" =>$fs,
								"date" =>filemtime($dir."/".$file),
								"ext"  =>$ext2,
								"type" =>$type2
								);
					}
			}
		}
	}
	closedir($handle);
	//$F2 = $F;
	
	if(is_Array($F) && count($F)>0) {
		//$F2 = cmpsort($F,"name");
		$F2 = array_sort($F,$sort);

		return($sortdir == 'asc' ? $F2 : array_reverse($F2));
	}
	// }}}
}

function setFile($fn, $content) {
	// {{{
	$fp = fopen($fn, 'w');
	fwrite($fp, $content);
	fclose($fp);
	// }}}
}
function addFile($fn, $content) {
	// {{{
	$fp = fopen($fn, 'a');
	fwrite($fp, $content."\n");
	fclose($fp);
	// }}}
}

function getFile($fn) {
	// {{{
	$html = implode(file($fn),'');
	return($html);
	// }}}
}

function fixname($n) {
	// {{{
	$replaceChar = "_"; 
	$n = strtolower($n);
	$n = str_replace(" ",$replaceChar,$n);
	
	/*
	$n = str_replace('ä','ae',$n);
	$n = str_replace('ö','oe',$n);
	$n = str_replace('ü','ue',$n);
	$n = str_replace('ß','ss',$n);
	
	$n = str_replace('�','ae',$n);
	$n = str_replace('�','oe',$n);
	$n = str_replace('�','ue',$n);
	$n = str_replace('�','ss',$n);
	if(function_exists("utf8_encode")) {
		$n = str_replace(utf8_encode('�'), 'ae', $n);
		$n = str_replace(utf8_encode('�'), 'oe', $n);
		$n = str_replace(utf8_encode('�'), 'ue', $n);
		$n = str_replace(utf8_encode('�'), 'ss', $n);
	}
	$n = str_replace(utf8_encode('�'),'ae',$n);
	$n = str_replace(utf8_encode('�'),'oe',$n);
	$n = str_replace(utf8_encode('�'),'ue',$n);
	$n = str_replace(utf8_encode('�'),'ss',$n);
	*/
	$n = str_replace('&',$replaceChar,$n);
	$n = str_replace(',',$replaceChar,$n);
	//$n = str_replace('-',$replaceChar,$n);
	$n = str_replace('/',$replaceChar,$n);
	$n = str_replace('?',$replaceChar,$n);
	$n = str_replace('!',$replaceChar,$n);
	$n = str_replace('#',$replaceChar,$n);
	$n = str_replace(';',$replaceChar,$n);
	$n = str_replace(':',$replaceChar,$n);
	
	#$n = preg_replace('/[^a-z0-9_\-.]/i', $replaceChar, $n);
	
	while (stristr($n,$replaceChar.$replaceChar)) {
		$n = str_replace($replaceChar.$replaceChar,$replaceChar,$n);
	}
	return($n);
	// }}}
}
function fixFilename($filename, $path)
{
	$filename = fixname($filename);
	if (file_exists($path."/".$filename))
	{
		$ext = substr($filename,strrpos($filename,"."));
		$name = substr($filename,0,strrpos($filename,"."));

		$i=0;
		$newFilename = $name.$ext;
		while(file_exists($path."/".$newFilename) && $i<100)
		{
			$i++;
			$newFilename = $name."_".$i.$ext; 
		}

		if ($i==100 && file_exists($path."/".$newFilename))
		{
			$newFilename = substr(md5(uniqid(rand())), 0, 4) . "_" . $filename;
		}

		$filename = $newFilename;
	}
	return $filename;
}

function cms_move_uploaded_file($src, $dest)
{
	$destPath = dirname($dest);
	$destFile = basename($dest);

	$destFile = fixFilename($destFile, $destPath);

	$res = @move_uploaded_file($src, $destPath."/".$destFile);
	if(file_Exists($destPath."/".$destFile) && is_file($destPath."/".$destFile)) @chmod($destPath."/".$destFile, 0666);
	if ($res) return $destFile;
	else return false;
}

function fw_unserialize($s, $field='') { return(fw_uns($s, $field='')); }
function fw_uns($s, $field='') {
	// {{{
	if($s!='') $s2 = unserialize($s);
	else $s2 = array();
	if($field!='') return($s2[$field]);
	return($s2);
	// }}}
}

function createPasswort($len=5) {
	// {{{
	$p = strtolower(substr(md5(uniqid(rand().time()).rand()),0,$len));
	$p = str_replace("1", "p", $p);
	$p = str_replace("l", "k", $p);
	$p = str_replace("o", "w", $p);
	$p = str_replace("0", "s", $p);
	return($p);
	// }}}
}
function createCode($len=5) {
	// {{{
	$C = "ABCDEFGHKLMPRSTWXYZ23456789";
	$C .= $C;
	$res = "";
	for($i=0;$i<$len;$i++) {
		$res .= substr($C,rand(0,strlen($C)-1), 1);
	}
	return($res);
	// }}}
}
function makeClickableLinks_old($text) {
	$text = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&;//=]+)', '<a href="\\1" target=_blank>\\1</a>', $text);
	$text = eregi_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '\\1<a href="http://\\2" target=_blank>\\2</a>', $text);
	$text = eregi_replace('([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})', '<a href="mailto:\\1">\\1</a>', $text);
	return $text;
}

function makeClickableLinks($text) {
    $text = preg_replace('#(script|about|applet|activex|chrome):#is', "\\1:", $text);
    $ret = ' ' . $text;
    $ret = preg_replace("#(^|[\n ])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);
    $ret = preg_replace("#(^|[\n ])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
    $ret = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $ret);
    $ret = substr($ret, 1);
    return $ret;
}

function deliverFile($a_file, $a_filename, $delete_file = false) {
	// {{{
	$disposition = "attachment"; // "inline" to view file in browser or "attachment" to download to hard disk
	$mime = "application/octet-stream"; // or whatever the mime type is
	if (isset($_SERVER["HTTPS"]))
	{
		/**
		* We need to set the following headers to make downloads work using IE in HTTPS mode.
		*/
		header("Pragma: ");
		header("Cache-Control: ");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
		header("Cache-Control: post-check=0, pre-check=0", false);
	}
	else if ($disposition == "attachment")
	{
		header("Cache-control: private");
	}
	else
	{
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");
	}

	

	header("Content-Type: $mime");
	header("Content-Disposition:$disposition; filename=\"".$a_filename."\"");
	header("Content-Description: ".$a_filename);
	header("Content-Length: ".(string)(filesize($a_file)));
	header("Connection: close");
	
	readfile( $a_file );
	
	if ($delete_file) @unlink($a_file);
	
	exit();
	// }}}
}

function deliverData($a_data, $a_filename) {
	// {{{
	$disposition = "attachment"; // "inline" to view file in browser or "attachment" to download to hard disk
	$mime = "application/octet-stream"; // or whatever the mime type is
	if (isset($_SERVER["HTTPS"]))
	{
		/**
		* We need to set the following headers to make downloads work using IE in HTTPS mode.
		*/
		header("Pragma: ");
		header("Cache-Control: ");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
		header("Cache-Control: post-check=0, pre-check=0", false);
	}
	else if ($disposition == "attachment")
	{
		header("Cache-control: private");
	}
	else
	{
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");
	}



	header("Content-Type: $mime");
	header("Content-Disposition:$disposition; filename=\"".$a_filename."\"");
	header("Content-Description: ".$a_filename);
	header("Content-Length: ".(string)(strlen($a_data)));
	header("Connection: close");

	echo $a_data;

	exit();
	// }}}
}

function stripSlashesInArray($A) {
	// {{{
	if(!is_array($A)) return($A);
	foreach($A as $key => $value) {
		$A[$key] = stripslashes($value);
	}
	return($A);
	// }}}
}
function bis($haystack, $needle) {
	// {{{
	return(substr($haystack,0,strpos($haystack,$needle)));
	// }}}
}
function str_bis($haystack, $needle) {
	return(bis($haystack, $needle));
}

function hinter($haystack, $needle) {
	// {{{
	return(substr($haystack,strpos($haystack,$needle)+strlen($needle)));
	// }}}
}
function str_nach($haystack, $needle) {
	return(hinter($haystack, $needle));
}
function str_zwischen($s, $von,$bis) {
	// {{{
	$s = hinter($s,$von);
	$s = bis($s,$bis);
	return($s);
	// }}}
}
function str_beginn($s, $beginn) {
	if(substr(strtolower($s),0,strlen($beginn))==strtolower($beginn)) return true;
	return false;
}

function str_ende($s, $ende) {
	if(substr(strtolower($s),-strlen($ende))==strtolower($ende)) return true;
	return false;
}

function purify($html) {
	$text = htmlspecialchars($html);
	$text = trim($text);
	return($text);
}
/*
 * found at <a href="http://php.net/manual/en/function.stripslashes.php" target=_blank>http://php.net/manual/en/function.stripslashes.php</a>
 * comment of michal at roszka dot pl (01-Sep-2009 03:00)
 */

define('_MAGIC_QUOTES', (TRUE == function_exists('get_magic_quotes_gpc') && 1 == get_magic_quotes_gpc()) );
if (_MAGIC_QUOTES) {
	$mqs = strtolower(ini_get('magic_quotes_sybase'));
	define('_MAGIC_QUOTES_SYBASE', ( (TRUE == empty($mqs) || 'off' == $mqs) ? true : false ) );
}
function prepareString($str) {
	if (_MAGIC_QUOTES) {
		if (_MAGIC_QUOTES_SYBASE) return stripslashes($str);
		else return str_replace("''", "'", $str);
	}
	return $str;
}

function prepareArray($A) {
	// {{{
	foreach($A as $key => $value) {
		$A[$key] = prepareString($value);
	}
	return($A);
	// }}}
}

function file_exists_cached($fn) {
	// {{{
	if(isset($GLOBALS['file_exists_cached'][$fn])) return($GLOBALS['file_exists_cached'][$fn]);
	$GLOBALS['file_exists_cached'][$fn] = file_exists($fn);
	return($GLOBALS['file_exists_cached'][$fn]);
	// }}}
}

function displayLinks($links, $shorten=false) {
	// {{{
	$L = explode("\n", $links);
	for($i=0;$i<count($L);$i++) {
		$L[$i] = trim($L[$i]);
		if($L[$i]!='') {
			if(substr($L[$i],0,8)=="intern::") {
				$L[$i] = getLink(substr($L[$i],8));
				$LI = "interner Link";
				$html .= '<div><a href="'.$L[$i].'"><img src="images/external_link.png" align=absmiddle border=0>&nbsp;'.$LI.'</a></div>';
			} else {
				if(strtolower(substr($L[$i],0,4))!='http') $L[$i] = 'http://'.$L[$i];
				if($shorten) $LI = shortenLink($L[$i]); else $LI = $L[$i];
				#$html .= '<div style="clear:both;"><a href="'.$L[$i].'" target="_blank" class="linkimage"><img src="./images/external_link.png" align=absmiddle border=0>&nbsp;'.$LI.'</a></div>';
				$html .= '<div style="clear:both;" >';
				$html .= '<embed style="float: left;margin-top: 3px" width="12" height="12" name="plugin" src="./js/cb.swf?imageurl=../images/external_link.png&cbdata='.urlencode($L[$i]).'" type="application/x-shockwave-flash" />';
				$html .= '<a href="'.$L[$i].'" target="_blank" class="linkimage">&nbsp;'.$LI.'</a></div>';
				
			}
			
		}
	}
	return($html);
	// }}}
}

function shortenLink($link) {
	// {{{
	if(substr(strtolower($link),0,4)!='http') $link = 'http://'.$link;
	$L = parse_url($link);
	
	$link2 = $L['scheme'].'://'.$L['host'];
	$path = $L['path'];
	if(strlen($path)>30) $path = substr($path,0,10).'...'.substr($path,-20);
	$link2 .= $path;
	return($link2);
	// }}}
}
function scaleFit($origWidth, $origHeight, $newWidth, $newHeight) {
	// {{{
	if($origWidth!=$newWidth) {
		$origHeight = $origHeight * ($newWidth/$origWidth);
		$origWidth = $newWidth;
	}
	if($origHeight>$newHeight) {
		$origWidth = $origWidth * ($newHeight/$origHeight);
		$origHeight = $newHeight;
	}
	return(array(floor($origWidth), floor($origHeight)));
	// }}}
}

function addLog($line) {
	// {{{
	addFile(projectPath.'/debug.log', date("d.m.Y H:i:s")."\t".$line."\n");

	// }}}
}
function getFileExt($filename) {
	// {{{
	$ext = strtolower(substr($filename,strrpos($filename,".")+1));
	return($ext);
	// }}}
}

if(!defined("ownDefinedFunction_me")) {
	function me() {
		// {{{
		if(!loggedIn()) return new \classes\NullObj();
		return \Backenduser\Service\ActiveBackenduserService::getUser();
		// }}}
	}
}

function ifne($davor, $text, $dahinter='') {
    if($dahinter=='') {
        $dahinter = $text;
        $text = $davor;
        $davor = '';
    }
    if(trim($text)!='') return $davor.$text.$dahinter;
    return '';
}

function correctWeblink($url) {
    if(strtolower(substr(trim($url),0,4))!='http') {
	$url = 'http://'.trim($url);
    }
    return $url;
}

function fw_mail($to, $subject, $body, $from, $attachment=array()) {
    //mail($to, $subject, $body, $header);
    
    include_once projectPath.'/classes/phpmailer/class.phpmailer.php';
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->CharSet = "utf-8";
    #$mail->AddReplyTo(MailSender,MailSenderName);
    if(is_array($from)) {
        $mail->SetFrom($from[0], $from[1]);
    } else {
        $mail->SetFrom($from);
    }
    $to = str_replace(",", ";", $to);
    $tos = explode(";", $to);
    for($i=0;$i<count($tos);$i++) {
	#print_r(trim($tos[$i]));
	$mail->AddAddress(trim($tos[$i]), "");
    }
    for($i=0;$i<count($attachment);$i++) {
	$mail->AddAttachment($attachment[$i]);
    }
    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $res = $mail->Send();
    return $res;
}

function userPWHash($pw, $hash="") {
	$M = md5($hash.$pw);
	return $M;
}

function getMimetypeByExtension($ext) {
	$mimes = 'acx	application/internet-property-stream
ai	application/postscript
aif	audio/x-aiff
aifc	audio/x-aiff
aiff	audio/x-aiff
asf	video/x-ms-asf
asr	video/x-ms-asf
asx	video/x-ms-asf
au	audio/basic
avi	video/x-msvideo
axs	application/olescript
bas	text/plain
bcpio	application/x-bcpio
bin	application/octet-stream
bmp	image/bmp
c	text/plain
cat	application/vnd.ms-pkiseccat
cdf	application/x-cdf
cdf	application/x-netcdf
cer	application/x-x509-ca-cert
class	application/octet-stream
clp	application/x-msclip
cmx	image/x-cmx
cod	image/cis-cod
cpio	application/x-cpio
crd	application/x-mscardfile
crl	application/pkix-crl
crt	application/x-x509-ca-cert
csh	application/x-csh
css	text/css
dcr	application/x-director
der	application/x-x509-ca-cert
dir	application/x-director
dll	application/x-msdownload
dms	application/octet-stream
doc	application/msword
dot	application/msword
dvi	application/x-dvi
dxr	application/x-director
eps	application/postscript
etx	text/x-setext
evy	application/envoy
exe	application/octet-stream
fif	application/fractals
flr	x-world/x-vrml
gif	image/gif
gtar	application/x-gtar
gz	application/x-gzip
h	text/plain
hdf	application/x-hdf
hlp	application/winhlp
hqx	application/mac-binhex40
hta	application/hta
htc	text/x-component
htm	text/html
html	text/html
htt	text/webviewhtml
ico	image/x-icon
ief	image/ief
iii	application/x-iphone
ins	application/x-internet-signup
isp	application/x-internet-signup
jfif	image/pipeg
jpe	image/jpeg
jpeg	image/jpeg
jpg	image/jpeg
js	application/x-javascript
latex	application/x-latex
lha	application/octet-stream
lsf	video/x-la-asf
lsx	video/x-la-asf
lzh	application/octet-stream
m13	application/x-msmediaview
m14	application/x-msmediaview
m3u	audio/x-mpegurl
man	application/x-troff-man
mdb	application/x-msaccess
me	application/x-troff-me
mht	message/rfc822
mhtml	message/rfc822
mid	audio/mid
mny	application/x-msmoney
mov	video/quicktime
movie	video/x-sgi-movie
mp2	video/mpeg
mp3	audio/mpeg
mpa	video/mpeg
mpe	video/mpeg
mpeg	video/mpeg
mpg	video/mpeg
mpp	application/vnd.ms-project
mpv2	video/mpeg
ms	application/x-troff-ms
msg	application/vnd.ms-outlook
mvb	application/x-msmediaview
nc	application/x-netcdf
nws	message/rfc822
oda	application/oda
p10	application/pkcs10
p12	application/x-pkcs12
p7b	application/x-pkcs7-certificates
p7c	application/x-pkcs7-mime
p7m	application/x-pkcs7-mime
p7r	application/x-pkcs7-certreqresp
p7s	application/x-pkcs7-signature
pbm	image/x-portable-bitmap
pdf	application/pdf
pfx	application/x-pkcs12
pgm	image/x-portable-graymap
pko	application/ynd.ms-pkipko
pma	application/x-perfmon
pmc	application/x-perfmon
pml	application/x-perfmon
pmr	application/x-perfmon
pmw	application/x-perfmon
pnm	image/x-portable-anymap
pot	application/vnd.ms-powerpoint
ppm	image/x-portable-pixmap
pps	application/vnd.ms-powerpoint
ppt	application/vnd.ms-powerpoint
prf	application/pics-rules
ps	application/postscript
pub	application/x-mspublisher
qt	video/quicktime
ra	audio/x-pn-realaudio
ram	audio/x-pn-realaudio
ras	image/x-cmu-raster
rgb	image/x-rgb
rmi	audio/mid
roff	application/x-troff
rtf	application/rtf
rtx	text/richtext
scd	application/x-msschedule
sct	text/scriptlet
setpay	application/set-payment-initiation
setreg	application/set-registration-initiation
sh	application/x-sh
shar	application/x-shar
sit	application/x-stuffit
snd	audio/basic
spc	application/x-pkcs7-certificates
spl	application/futuresplash
src	application/x-wais-source
sst	application/vnd.ms-pkicertstore
stl	application/vnd.ms-pkistl
stm	text/html
sv4cpio	application/x-sv4cpio
sv4crc	application/x-sv4crc
svg	image/svg+xml
swf	application/x-shockwave-flash
t	application/x-troff
tar	application/x-tar
tcl	application/x-tcl
tex	application/x-tex
texi	application/x-texinfo
texinfo	application/x-texinfo
tgz	application/x-compressed
tif	image/tiff
tiff	image/tiff
tr	application/x-troff
trm	application/x-msterminal
tsv	text/tab-separated-values
txt	text/plain
uls	text/iuls
ustar	application/x-ustar
vcf	text/x-vcard
vrml	x-world/x-vrml
wav	audio/x-wav
wcm	application/vnd.ms-works
wdb	application/vnd.ms-works
wks	application/vnd.ms-works
wmf	application/x-msmetafile
wps	application/vnd.ms-works
wri	application/x-mswrite
wrl	x-world/x-vrml
wrz	x-world/x-vrml
xaf	x-world/x-vrml
xbm	image/x-xbitmap
xla	application/vnd.ms-excel
xlc	application/vnd.ms-excel
xlm	application/vnd.ms-excel
xls	application/vnd.ms-excel
xlt	application/vnd.ms-excel
xlw	application/vnd.ms-excel
xof	x-world/x-vrml
xpm	image/x-xpixmap
xwd	image/x-xwindowdump
z	application/x-compress
zip	application/zip';

	$M = explode("\n", $mimes);
	for($i=0;$i<count($M);$i++) {
		$line = explode("\t", trim($M[$i]));
		if($line[0]==$ext) return $line[1];
	}
	return "application/octet-stream";
}

?>