<?php
class IndexController extends \classes\AbstractController {

	public function __construct() {
		parent::__construct();
	}

	public function indexAction($QS) {
		$tpl = $this->newTpl();
		$tpl->setVariable('test', date('H:i:s'));
	
		if(loggedIn()) jump2page("Eintrag");
		else  jump2page("Backenduser/login");
	
		$this->fw->setVariable('CONTENT', $tpl->get('tpl.Index.php'));
	}
	
	public function apiAction() {
		
		$rechnerRepository = new \Rechner\Repository\RechnerRepository();
		$rechnerRepository->addWhere("re_hash='".addslashes($_REQUEST["key"])."' ");
		$rechner = $rechnerRepository->findOne();
		
		if($_REQUEST["action"]=="openmail") {
			header("location: index.php?fw_goto=Mailkontakt/kontaktliste/".$_REQUEST["key"]);
			exit;
		}
		
		if($_REQUEST["action"]=="gethosts") {
			$eintraegeRepository = new \Eintrag\Repository\EintragRepository();
			$eintraegeRepository->addWhere("ei_rechner like '%'||'".$rechner->getPk()."'||'%'  ");
			$eintraege = $eintraegeRepository->findAll();
			
			$data = array();
			foreach($eintraege as $eintrag) {
				if(trim($eintrag->getHosts())!="") {
					$H = str_replace(",", "\n", $eintrag->getHosts());
					$H = explode("\n", $H);
					for($i=0;$i<count($H);$i++) {
						if(trim($H[$i])!="") $data[] = trim($H[$i]);
					}
				}
			}

		}
		
		if($_REQUEST["action"]=="listbereiche") {
			$bereicheRepository = new \Bereiche\Repository\BereichRepository();
			$bereicheRepository->addJoin("INNER JOIN eintrag ON ei_bereich=be_pk");
			$bereicheRepository->addJoin("INNER JOIN rechner ON ','||ei_rechner||',' like '%,'||re_pk||',%' ");
			$bereicheRepository->addWhere("re_pk='".$rechner->getPk()."' ");
			$Q = $bereicheRepository->createQuery();
			$bereiche = $bereicheRepository->findAll();
			#vd($bereiche);
			
			$bereicheData = array();
			foreach($bereiche as $bereich) {
				$bereicheData[] = array(
					"id" => $bereich->getPk(),
					"titel" => $bereich->getName(),
					"icon" => $bereich->getIcon()->getBase64()
					);
			}
			
			$mailkontaktRepository = new \Mailkontakt\Repository\MailkontaktRepository();
			$mailkontaktRepository->addWhere("','||mk_rechner||',' like '%,'||'".$rechner->getPk()."'||',%'  ");
			$mailkontakt = $mailkontaktRepository->findOne();
			
			$F = array(
				"mailbox" => !isNullObj($mailkontakt)
				);
			
			$data = array(
				"bereiche" => $bereicheData,
				"features" => $F,
				//"query" => $Q,
				);
		}
		
		if($_REQUEST["action"]=="listeintraege") {
			$eintraegeRepository = new \Eintrag\Repository\EintragRepository();
			$eintraegeRepository->addWhere("ei_bereich='".$_REQUEST["bereich"]."' ");
			$eintraegeRepository->addWhere("','||ei_rechner||',' like '%,'||'".$rechner->getPk()."'||',%'  ");
			$eintraege = $eintraegeRepository->findAll();
			
			$data = array();
			foreach($eintraege as $eintrag) {
				$data[] = array(
					"id" => $eintrag->getPk(),
					"titel" => $eintrag->getName(),
					"typ" => $eintrag->getTyp(),
					"befehl" => $eintrag->getBefehl(),
					"icon" => $eintrag->getIcon()->getBase64()
					);
			}
			
		}
		
		if($_REQUEST["action"]=="ungelesennachrichten") {
			$nachrichtRepository = new \Nachrichten\Repository\NachrichtRepository();
			$nachrichtRepository->addWhere("na_sender='kontakt'");
			//$nachrichtRepository->addWhere("na_mailkontakt='".$this->getPk()."'");
			$nachrichtRepository->addWhere("na_rechner='".$rechner->getPk()."'");
			$nachrichtRepository->addWhere("na_gelesen='"._DATE0."'");
			$c = $nachrichtRepository->countAll();
			$data["ungelesen"] = $c;
		}
		
		echo json_encode($data);
		exit;
	}
	
	public function infosAction($QS) {
		$tpl = $this->newTpl();
		
		$this->fw->setVariable('CONTENT', $tpl->get('tpl.Infos.php'));
	}
	
} // fastfwController
?>