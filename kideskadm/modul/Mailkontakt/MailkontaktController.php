<?php
class MailkontaktController extends \classes\AbstractCrudController {

    public function __construct() {
        parent::__construct();
        #new fe_user(array('checklogin' => true)); // array('requirelogin' => true)

        
        $this->initCrud("Mailkontakt", "Mailkontakt", "mk");
        $this->addListColumn("mk_bild", "Bild");
		$this->addListColumn("mk_name", "Name");
		$this->addListColumn("mk_email", "Email");
		$this->addListColumn("mk_rechner", "Rechner");
		$this->setSortableColumns(array("mk_name","mk_email","mk_rechner"));
		$this->templates = array(
		    "head"          => "Mailkontakt/tpl.Head.php",
		    "list"          => "Mailkontakt/tpl.Index.php",
		    "form"          => "Mailkontakt/tpl.Form.php",
		    "deleteConfirm" => "Mailkontakt/tpl.DeleteConfirm.php",
		    "copyConfirm"   => "Mailkontakt/tpl.CopyConfirm.php",
		);

		
		
		
    }
    
    public function indexAction($QS) {

        $rechnerRepository = new \Rechner\Repository\RechnerRepository();
	    $rechnerRepository->addWhere("re_bu_fk='".me()->getPk()."' ");
	    if($rechnerRepository->countAll()==0) {
		    \classes\FlashMessage::add("Bitte zunächst einen Rechner anlegen.", "info");
		    return jump2page("Rechner");
	    }
    	
    	
			if(!loggedIn()) {jump2page("Backenduser/login");exit;}
			$this->repository->addWhere(" mk_bu_fk='".me()->getPk()."' ");
			$this->repository->addEntryWhere(" mk_bu_fk='".me()->getPk()."' ");
			parent::indexAction($QS);
    }
   

    public function index2Action() {
        $tpl = $this->newTpl();



        $this->fw->setVariable('CONTENT', $tpl->get('Mailkontakt/tpl.Index.php'));
    }

    protected function prepareRelData($data) {
	if (is_Array($data["mk_rechner"])) {
		$data["mk_rechner"] = implode(",", $data["mk_rechner"]);
	} else {
		$data["mk_rechner"] = "";
	}
return $data;
}
protected function preInsert($data) {
	$data["mk_hash"] = createCode(10);    	    
	$data = $this->prepareRelData($data);
	$data["mk_bu_fk"] = me()->getPk();
	return $data;
}
protected function preUpdate($pk, $data) {
	//$data["mk_hash"] = createCode(10);
	$data = $this->prepareRelData($data);
	$data["mk_bu_fk"] = me()->getPk();
	return $data;
}
function newAction() {
				if(!loggedIn()) {jump2page("Backenduser/login");exit;}

	parent::newAction();
}

function editAction(array $queryArray) {
	
				if(!loggedIn()) {jump2page("Backenduser/login");exit;}
$this->repository->addEntryWhere(" mk_bu_fk='".me()->getPk()."' ");
$this->repository->addWhere(" mk_bu_fk='".me()->getPk()."' ");
	parent::editAction($queryArray);
}


public function sendAction($QS) {
	
	$rechnerRepository = new \Rechner\Repository\RechnerRepository();
	$rechnerRepository->addWhere("re_hash='".addslashes($QS[0])."' ");
	$rechner = $rechnerRepository->findOne();
	
	$mailkontaktRepository = new \Mailkontakt\Repository\MailkontaktRepository();
	$mailkontaktRepository->addWhere("mk_hash='".addslashes($QS[1])."' ");
	$mailkontakt = $mailkontaktRepository->findOne();
	
	define("hideBar", 1);
	
	$tpl = $this->newTpl();
	
	$tpl->setVariable("rechner", $rechner);
	$tpl->setVariable("mailkontakt", $mailkontakt);
	
	$this->fw->setVariable("CONTENT", $tpl->get('Kommunikation/tpl.send.php'));
}

public function dosendAction($QS) {
	$rechnerRepository = new \Rechner\Repository\RechnerRepository();
	$rechnerRepository->addWhere("re_hash='".addslashes($QS[0])."' ");
	$rechner = $rechnerRepository->findOne();
	
	$mailkontaktRepository = new \Mailkontakt\Repository\MailkontaktRepository();
	$mailkontaktRepository->addWhere("mk_hash='".addslashes($QS[1])."' ");
	$mailkontakt = $mailkontaktRepository->findOne();
	
	$nachrichtRepository = new \Nachrichten\Repository\NachrichtRepository();
	$data = array(
		"na_sender" => "rechner",
		"na_mailkontakt" => $mailkontakt->getPk(),
		"na_rechner" => $rechner->getPk(),
		"na_nachricht" => htmlspecialchars($_REQUEST["nachricht"]),
		"na_uebertragen" => _DATE0,
		"na_gelesen" => _DATE0,
		
		);
	if(trim($_REQUEST["nachricht"])!="") {
		$nachrichtRepository->insert($data);
		
		
		$tpl = $this->newTpl();
		$tpl->setVariable("rechner", $rechner);
		$tpl->setVariable("mailkontakt", $mailkontakt);
		$M = $tpl->get("Kommunikation/tpl.mailankontakt.php");
		mail($mailkontakt->getEmail(), "[Kidesktop] - Neue Nachricht von ".$rechner->getKind(), $M, "FROM:Kidesktop<kidesktop@flipflox.de>\nContent-Type: text/html;charset=utf-8");
	}
	
	echo json_encode(array("result" => 1));
	exit;
}


public function doantwortAction($QS) {
	$rechnerRepository = new \Rechner\Repository\RechnerRepository();
	$rechnerRepository->addWhere("re_hash='".addslashes($QS[0])."' ");
	$rechner = $rechnerRepository->findOne();
	
	$mailkontaktRepository = new \Mailkontakt\Repository\MailkontaktRepository();
	$mailkontaktRepository->addWhere("mk_hash='".addslashes($QS[1])."' ");
	$mailkontakt = $mailkontaktRepository->findOne();
	
	$nachrichtRepository = new \Nachrichten\Repository\NachrichtRepository();
	$data = array(
		"na_sender" => $_REQUEST["wer"],
		"na_mailkontakt" => $mailkontakt->getPk(),
		"na_rechner" => $rechner->getPk(),
		"na_nachricht" => htmlspecialchars($_REQUEST["nachricht"]),
		"na_uebertragen" => now(),
		"na_gelesen" => _DATE0,
		
		);
	$nachrichtRepository->insert($data);
	
	if(!stristr($mailkontakt->getEmail(), "@")) {
		$rechnerRepository2 = new \Rechner\Repository\RechnerRepository();
		$rechnerRepository2->addWhere("re_hash='".addslashes($mailkontakt->getEmail())."' ");
		$rechner2 = $rechnerRepository2->findOne();
		
		$mailkontaktRepository2 = new \Mailkontakt\Repository\MailkontaktRepository();
		$mailkontaktRepository2->addWhere("mk_email='".addslashes($QS[0])."' ");
		$mailkontakt2 = $mailkontaktRepository2->findOne();
		
		$nachrichtRepository = new \Nachrichten\Repository\NachrichtRepository();
		$data = array(
			"na_sender" => "kontakt",
			"na_mailkontakt" => $mailkontakt2->getPk(),
			"na_rechner" => $rechner2->getPk(),
			"na_nachricht" => htmlspecialchars($_REQUEST["nachricht"]),
			"na_uebertragen" => now(),
			"na_gelesen" => _DATE0,
			
			);
		$nachrichtRepository->insert($data);
		
	}
	
		
	if($_REQUEST["wer"]=="rechner" && stristr($mailkontakt->getEmail(), "@")) {
		
		$cid = md5(microtime());
		
		$tpl = $this->newTpl();
		$tpl->setVariable("rechner", $rechner);
		$tpl->setVariable("mailkontakt", $mailkontakt);
		$tpl->setVariable("cid", $cid);
		$M = $tpl->get("Kommunikation/tpl.mailankontakt.php");
		#mail($mailkontakt->getEmail(), "[Kidesktop] - Neue Nachricht von ".$rechner->getKind(), $M, "FROM:Kidesktop<ay@piluter.de>\nContent-Type: text/html;charset=utf-8");
		
		
		$im = imageCreateFromJpeg(projectPath.'/resources/postcard.jpg');
		$fontfile = projectPath.'/resources/5yearsoldfont.ttf';
		$col = imageColorAllocate($im, 10,10,10);
		
		$text = "Hallo ".$mailkontakt->getName().",";
		imagettftext ( $im , 20 , 0 , 40 , 90 , $col , $fontfile , $text );
	
		$text = "An ".$mailkontakt->getName()."";
		imagettftext ( $im , 20 , 0 , 470 , 290 , $col , $fontfile , $text );
		
		
		$text = "Gruß, ".$rechner->getKind();
		imagettftext ( $im , 20 , 0 , 200 , 470 , $col , $fontfile , $text );
		
		
		$text = htmlspecialchars($_REQUEST["nachricht"]);
		$text = str_Replace("\n", " ", $text);
		
		$this->write_multiline_text($im, 20, $col, $fontfile, $text, 40, 140, 380);
		
		header("content-type:image/jpeg");
		imageJpeg($im, projectPath.'/cache/postcard.jpg');		
		
		
		
		
		include projectPath."/classes/PHPMailer_v5.1/class.phpmailer.php";
		$mail = new PHPMailer();  // create a new object
		$mail->IsSMTP();		
		$mail->SMTPAuth = false;  // authentication enabled
		$mail->SMTPSecure = SMTP_SECURE; // secure transfer enabled REQUIRED for GMail
		$mail->Host = SMTP_HOST;
		$mail->Port = SMTP_PORT; 
		$mail->CharSet = "utf-8";
		$mail->Username = SMTP_USERNAME;  
		$mail->Password = SMTP_PASSWORD;           
		$mail->SetFrom(SMTP_FROM, "Kidesktop");
		$mail->Subject = "[Kidesktop] - Neue Nachricht von ".$rechner->getKind();
		$mail->Body = $M;
		$mail->Sender = SMTP_FROM;
		$mail->IsHTML();
		$mail->AddAddress($mailkontakt->getEmail());
		
		
		
		$mail->AddEmbeddedImage(projectPath.'/cache/postcard.jpg', $cid);
		
		$mail->Send();
						
	}
	
	echo json_encode(array("result" => 1));
	exit;
}

public function lesenAction($QS) {
	define("hideBar", 1);
	
	$rechnerRepository = new \Rechner\Repository\RechnerRepository();
	$rechnerRepository->addWhere("re_hash='".addslashes($QS[0])."' ");
	$rechner = $rechnerRepository->findOne();
	
	$mailkontaktRepository = new \Mailkontakt\Repository\MailkontaktRepository();
	$mailkontaktRepository->addWhere("mk_hash='".addslashes($QS[1])."' ");
	$mailkontakt = $mailkontaktRepository->findOne();
	
	$nachrichtRepository = new \Nachrichten\Repository\NachrichtRepository();
	$nachrichtRepository->addWhere("na_mailkontakt='".$mailkontakt->getPk()."' ");
	$nachrichtRepository->addWhere("na_rechner='".$rechner->getPk()."' ");
	$nachrichtRepository->setOrderBy("na_pk");
	$nachrichten = $nachrichtRepository->findAll();
	
	$Q ="UPDATE nachricht SET na_gelesen='".now()."' WHERE na_mailkontakt='".$mailkontakt->getPk()."' AND na_rechner='".$rechner->getPk()."' AND na_sender='rechner' AND na_gelesen='"._DATE0."' ";
	$this->fw->DC->sendQuery($Q);
	
	$tpl = $this->newTpl();
	$tpl->setVariable("rechner", $rechner);
	$tpl->setVariable("mailkontakt", $mailkontakt);
	$tpl->setVariable("nachrichten", $nachrichten);
	$this->fw->setVariable("CONTENT", $tpl->get('Kommunikation/tpl.lesen.php'));
}


public function schreibenAction($QS) {
	define("hideBar", 1);
	
	$rechnerRepository = new \Rechner\Repository\RechnerRepository();
	$rechnerRepository->addWhere("re_hash='".addslashes($QS[0])."' ");
	$rechner = $rechnerRepository->findOne();
	
	$mailkontaktRepository = new \Mailkontakt\Repository\MailkontaktRepository();
	$mailkontaktRepository->addWhere("mk_hash='".addslashes($QS[1])."' ");
	$mailkontakt = $mailkontaktRepository->findOne();
	
	$nachrichtRepository = new \Nachrichten\Repository\NachrichtRepository();
	$nachrichtRepository->addWhere("na_mailkontakt='".$mailkontakt->getPk()."' ");
	$nachrichtRepository->addWhere("na_rechner='".$rechner->getPk()."' ");
	$nachrichtRepository->setOrderBy("na_pk");
	$nachrichten = $nachrichtRepository->findAll();
	
	$Q ="UPDATE nachricht SET na_gelesen='".now()."' WHERE na_mailkontakt='".$mailkontakt->getPk()."' AND na_rechner='".$rechner->getPk()."' AND na_sender='kontakt' AND na_gelesen='"._DATE0."' ";
	$this->fw->DC->sendQuery($Q);
	
	$tpl = $this->newTpl();
	$tpl->setVariable("rechner", $rechner);
	$tpl->setVariable("mailkontakt", $mailkontakt);
	$tpl->setVariable("nachrichten", $nachrichten);
	$this->fw->setVariable("CONTENT", $tpl->get('Kommunikation/tpl.schreiben.php'));
}

public function kontaktlisteAction($QS) {
	define("hideBar", 1);
	#echo "X";exit;
	
	$mailkontaktRepository = new \Mailkontakt\Repository\MailkontaktRepository();
	$mailkontaktRepository->addWhere("mk_hash=''");
	$mailkontakte = $mailkontaktRepository->findAll();
	foreach($mailkontakte as $K) {
		$data["mk_hash"] = createCode(10);
		$mailkontaktRepository->update($data, $K->getPk());
	}
	
	
	
	$rechnerRepository = new \Rechner\Repository\RechnerRepository();
	$rechnerRepository->addWhere("re_hash='".addslashes($QS[0])."' ");
	$rechner = $rechnerRepository->findOne();
	
	
	$mailkontaktRepository = new \Mailkontakt\Repository\MailkontaktRepository();
	$mailkontaktRepository->addWhere("','||mk_rechner||',' like '%,".$rechner->getPk().",%' ");
	$mailkontakte = $mailkontaktRepository->findAll();
	

	$tpl = $this->newTpl();
	$tpl->setVariable("mailkontakte", $mailkontakte);
		$tpl->setVariable("rechner", $rechner);


	$this->fw->setVariable("CONTENT", $tpl->get('Kommunikation/tpl.kontaktliste.php'));
}

public function testPostcardAction() {
	$im = imageCreateFromJpeg(projectPath.'/resources/postcard.jpg');
	$fontfile = projectPath.'/resources/5yearsoldfont.ttf';
	$col = imageColorAllocate($im, 10,10,10);
	
	$text = "Hallo Opa+Oma,";
	imagettftext ( $im , 20 , 0 , 40 , 90 , $col , $fontfile , $text );

	$text = "An Opa+Oma,";
	imagettftext ( $im , 20 , 0 , 470 , 290 , $col , $fontfile , $text );
	
	
	$text = "Gruß, Anna";
	imagettftext ( $im , 20 , 0 , 200 , 470 , $col , $fontfile , $text );
	
	
	$text = "Test\nqwje qlksdk fsdk fhwke fhskd flskd fskl fhslkd fsldk fhslkf wp rhwperh wejrh wlkrhw lkerh wlkerh wlrhwlek rhwelrwvjvj   d d s q  qw qw q qw db ws q weq e re e r";
	$text = str_Replace("\n", " ", $text);
	
	$this->write_multiline_text($im, 20, $col, $fontfile, $text, 40, 140, 380);
	
	header("content-type:image/jpeg");
	imageJpeg($im, projectPath.'/cache/postcard.jpg');
	#exit;
}

protected function write_multiline_text($image, $font_size, $color, $font, $text, $start_x, $start_y, $max_width) { 
    //split the string 
    //build new string word for word 
    //check everytime you add a word if string still fits 
    //otherwise, remove last word, post current string and start fresh on a new line 
    $words = explode(" ", $text); 
    $string = ""; 
    $tmp_string = ""; 

    for($i = 0; $i < count($words); $i++) { 
        $tmp_string .= $words[$i]." "; 

        //check size of string 
        $dim = imagettfbbox($font_size, 0, $font, $tmp_string); 

        if($dim[4] < ($max_width - $start_x)) { 
            $string = $tmp_string; 
            $curr_width = $dim[4];
        } else { 
            $i--; 
            $tmp_string = ""; 
            $start_xx = $start_x + round(($max_width - $curr_width - $start_x) / 2);        
            imagettftext($image, $font_size, 0, $start_x, $start_y, $color, $font, $string); 

            $string = ""; 
            $start_y += abs($dim[5]) * 1.5; 
            $curr_width = 0;
        } 
    } 

    $start_xx = $start_x + round(($max_width - $dim[4] - $start_x) / 2);        
    imagettftext($image, $font_size, 0, $start_x, $start_y, $color, $font, $string);
}

public function newmsgsAction($QS) {
	$rechnerRepository = new \Rechner\Repository\RechnerRepository();
	$rechnerRepository->addWhere("re_hash='".addslashes($QS[0])."' ");
	$rechner = $rechnerRepository->findOne();
	
	$mailkontaktRepository = new \Mailkontakt\Repository\MailkontaktRepository();
	$mailkontaktRepository->addWhere("mk_hash='".addslashes($QS[1])."' ");
	$mailkontakt = $mailkontaktRepository->findOne();
	
	$nachrichtRepository = new \Nachrichten\Repository\NachrichtRepository();
	$nachrichtRepository->addWhere("na_mailkontakt='".$mailkontakt->getPk()."' ");
	$nachrichtRepository->addWhere("na_rechner='".$rechner->getPk()."' ");
	$nachrichtRepository->addWhere("na_pk>".$_REQUEST["lastNPk"]);
	$nachrichtRepository->setOrderBy("na_pk");
	$nachrichten = $nachrichtRepository->findAll();
	if(count($nachrichten)==0) {
		die(json_encode(array("found" => 0)));
	} 
	$tpl = $this->newTpl();
	$tpl->setVariable("rechner", $rechner);
	$tpl->setVariable("mailkontakt", $mailkontakt);
	$tpl->setVariable("nachrichten", $nachrichten);
	$html = $tpl->get('Kommunikation/tpl.nurnachrichten.php');
	
	foreach($nachrichten as $nachricht) {
		$lastNPk = $nachricht->getPk();
	}
	
	die(json_encode(array("found" => count($nachrichten), "html" => $html, "lastNPk" => $lastNPk)));
	
}


} // fastfwController
?>