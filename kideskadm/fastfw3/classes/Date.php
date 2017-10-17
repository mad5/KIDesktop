<?php
namespace classes;

class Date extends \DateTime {
	protected $mydate = "";

	public function __construct($date) {
		$this->mydate = $date;
		parent::__construct($date);
	}

	public function getTimestamp() {
		return strtotime($this->mydate);
	}

	public function getDateplain() {
		return $this->mydate;
	}

	public function isUnset() {
		if(substr($this->mydate,0,10)=="1900-01-01") return true;
		if(substr($this->mydate,0,10)=="0000-00-00") return true;
		if(isNullObj($this->mydate)) return true;
		if($this->mydate=="") return true;
		return false;
	}

	public function getIso() {
		if($this->mydate=="1900-01-01") return "";
		if($this->mydate=="0000-00-00") return "";
		return \classes\Utils::changeIsoDate($this->mydate);
	}

	public function getTime() {
		return \classes\Utils::formatTimeByDateTime($this->mydate);
	}

	public function getDateTime() {
		return $this->getIso()." ".$this->getTime();
	}

	static public function deDateTimeFromTimestamp($ts) {
		$Y = substr($ts,0,4);
		$M = substr($ts,4,2);
		$D = substr($ts,6,2);
		
		$hour = substr($ts,8,2);
		$min = substr($ts,10,2);
		$sec = substr($ts,12,2);
		
		return $D.".".$M.".".$Y.", ".$hour.":".$min;
	}
	
	static public function microtimeFromTimestamp($ts) {
		$Y = substr($ts,0,4);
		$M = substr($ts,4,2);
		$D = substr($ts,6,2);
		
		$hour = substr($ts,8,2);
		$min = substr($ts,10,2);
		$sec = substr($ts,12,2);
		
		$dt = mktime($hour,$min,$sec, $M, $D, $Y);
		
		return $dt*1000;
	}
	
	static public function deDateForDb($date) {
		$D = explode(".", $date);
		$res = sprintf("%04s", $D[2])."-".sprintf("%02s", $D[1])."-".sprintf("%02s", $D[0]);
		return $res;
	}

	public function __toString() {
		return substr($this->mydate,0,19).'';
	}

	public static function nextWochentag($wt) {
		if(date("w")==$wt) return date("d.m.Y");
		$diff = date("w")-$wt;
		if($diff<0) $diff += 7;
		if($diff>6) $diff -= 7;
		return date("d.m.Y", time()-60*60*24*$diff+60*60*24*7);
	}
	public function humanReadable() {
		return formatDateHuman($this->mydate);
	}
}

?>