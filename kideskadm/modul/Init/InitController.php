<?php

class InitController extends \classes\AbstractCrudController {

	public function __construct() {
	}
	       
	public function testAction() {
		echo "tested\n";
		exit;
	}

	       		
	public function firstAction() {
		
		
		$data = array(
			"bu_username" => "admin",
			"bu_password" => md5(USER_SECRET."admin"),
			"bu_firstname" => "Kidesktop",
			"bu_lastname" => "Admin",
			"bu_admin" => 1,
			"bu_email" => "",
			"bu_hash" => createCode(10),
			);
		
		$GLOBALS["UserHash"] = $data["bu_hash"];
		
		#vd($data);exit;
		$backenduserRepository = new \Backenduser\Repository\BackenduserRepository();
		$bu_pk = $backenduserRepository->insert($data);
		#vd($bu_pk);
		
		$data = array(
			"re_kind" => "Rechner 1",
			"re_ort" => "Kinderzimmer",
			"re_beschreibung" => "",
			"re_letzteip" => "",
			"re_zuletztonline" => "",
			"re_offlineab" => "20:00",
			"re_offlinebis" => "06:00",
			"re_ausab" => "21:00",
			"re_ausbis" => "06:00",
			"re_nutzungsdauerinsgesamt" => "03:00",
			"re_bu_fk" => $bu_pk,
			"re_hash" => createCode(10),
			"re_bild" => "../resources/images/computer.png"
			);
		$GLOBALS["RechnerHash"] = $data["re_hash"];
		$rechnerRepository = new \Rechner\Repository\RechnerRepository();
		$re_pk = $rechnerRepository->insert($data);
		#vd($re_pk);
		$data = array(
			"be_name" => "Lernen",
			"be_icon" => "../resources/images/learn.png",
			"be_reihenfolge" => 1,
			"be_freigegeben" => 1,
			"be_bu_fk" => $bu_pk,
			);
		$bereichRepository = new \Bereiche\Repository\BereichRepository();
		$be_pk = $bereichRepository->insert($data);
		#vd($be_pk);
		$data = array(
			"ei_name" => "Antolin",
			"ei_icon" => "../resources/images/antolin.jpg",
			"ei_kategorie" => 0,
			"ei_bereich" => $be_pk,
			"ei_rechner" => $re_pk,
			"ei_typ" => "webseite",
			"ei_befehl" => "https://www.antolin.de/",		
			"ei_hosts" => "antolin.de",
			"ei_bu_fk" => $bu_pk,
			);
		$eintragRepository = new \Eintrag\Repository\EintragRepository();
		$ei_pk = $eintragRepository->insert($data);
		#vd($ei_pk);
		#exit;
	}
	
}

?>