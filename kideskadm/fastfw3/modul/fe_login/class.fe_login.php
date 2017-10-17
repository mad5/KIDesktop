<?php
/*
CREATE TABLE IF NOT EXISTS `fe_user` (
  `fe_pk` bigint(20) NOT NULL AUTO_INCREMENT,
  `fe_registered` datetime NOT NULL,
  `fe_confirmed` datetime NOT NULL,
  `fe_deleted` datetime NOT NULL,
  `fe_loginname` varchar(255) NOT NULL,
  `fe_password` varchar(255) NOT NULL,
  `fe_email` varchar(255) NOT NULL,
  `fe_lastactive` datetime NOT NULL,
  PRIMARY KEY (`fe_pk`)
) ENGINE=MyISAM CHARSET=utf8
*/
class fastfw_fe_login extends fastfw_modul{

	public $fe_login;

	public function  __construct() {
		if(!defined('disableRegistration')) define('disableRegistration', false);
		$this->fw = $GLOBALS["FastFW"];
		$this->fe_login = $this->fw->fw_useSingleClass('fe_login');
	}


	function view_index($QS) {
		// {{{		
		$uc = $this->fw->fw_useSingleClass('fe_login');
		if(isset($_POST["send_fe_login"]) && $_POST["send_fe_login"]==1) {
			// {{{
			
			$user = $uc->performLogin($_POST['fe_user'], $_POST['fe_pass']);
			
			if($user=='') $loginOk = false; else $loginOk = true;
			
			if($loginOk) {
				// {{{
				if(isset($_POST['fe_angemeldetbleiben']) && $_POST['fe_angemeldetbleiben']==1) {
					setS("fe_angemeldetbleiben", 1);
					setCookie('fe_login', md5($user['fe_pk'].$user['fe_registered'].$user['fe_loginname']),  time()+60*60*24*365);
				} else {
					setS("fe_angemeldetbleiben", 0);
				}
				
				setS('fe_user', $user);
				$page = getS('pageBeforeLogin');
				
                                $this->fw->callBinds('login');
                                
				$P = getS('requestBeforeLogin');
				if(stristr($P, '?')) {
					$P = hinter($P, '?');
				} else $P = $page;
				
				setS('requestBeforeLogin', '');
				setS('pageBeforeLogin', '');

                                if(isset($_POST['after_login'])) $P = $_POST['after_login'];
				$P = str_replace("fw_goto=", "", $P);

				jump2page($P);
				// }}}
			}
                        $this->setVariable('','failed', true);
			// }}}
		}
		
		if(isset($_COOKIE['fe_login']) && $_COOKIE['fe_login']!='') {
			$data = $this->fe_login->getUserByCookie($_COOKIE['fe_login']);
			if($data!='') {
				setS('fe_user', $data);
				$page = getS('pageBeforeLogin');
				if($page=='') $page = "Index";
				
				$P = getS('requestBeforeLogin');
				if(stristr($P, '?')) {
					$P = hinter($P, '?');
				} else $P = $page;
				
				setS('requestBeforeLogin', '');
				setS('pageBeforeLogin', '');
				
				$this->fw->callBinds('autologin');
				
				jump2page($page);
			}
		}
		
		$html = $this->tplGet('', 'tpl.fe_login.php');
		
		$this->fw->setVariable( 'CONTENT', $html);
		// }}}
	}
	
	function view_logout($QS) {
		// {{{
                $this->fw->callBinds('logout');
		setS('fe_user', '');
		setCookie('fe_login', '',  time()+60*60*24*365);		
                session_destroy();
		jump2page('fe_login');
		// }}}
	}
	
	function view_pwlost($QS) {
		// {{{
		#vd($_POST);
		$tpl = $this->newTpl();
		if(isset($_POST['send_fe_pwlost']) && $_POST['send_fe_pwlost']==1) {
			// {{{
			
			$data = $this->fe_login->getLostUserData($_POST["fe_pwlost"]);
			if($data=='') {
				$tpl->setVariable('pwlostsend', -1);
			} else {
				$np = createPasswort(4);
				$data2 = array('fe_password' => userPWHash(addslashes($np),FE_USER_SECRET));
				$this->fe_login->setNewPassword($data2['fe_password'], $data['fe_pk']);
				
				$tpl->setVariable('old', $data);
				$tpl->setVariable('new', $data2);
				$tpl->setVariable('pw', $np);
				
				$mail = $tpl->get('tpl.fe_pwlost_mail.php');
				mail($data['fe_email'], "[mailDiary] ".TRANS('Ihre neues Passwort'), $mail, "FROM:".MailSender."\nContent-Type:text/html");
				$tpl->setVariable('pwlostsend', 1);
			}
			// }}}
		}
		$tpl->setVariable('registered', 0);
		$html = $tpl->get('tpl.fe_pwlost.php');
		$this->fw->setVariable( 'CONTENT', $html);
		// }}}
	}
	
	function view_register($QS) {
		// {{{
		#vd($_POST);
		if(defined('disableRegistration') && disableRegistration==true) jump2page('fe_login');

		if(!defined('MailSender')) die('Konstante MailSender ist nicht definiert!');


		if(isset($_POST['send_fe_register']) && $_POST['send_fe_register']==1) {
			// {{{
			$data = array(	'fe_registered' => now(),
					'fe_loginname' => htmlspecialchars($_POST['fe_user']),
					'fe_password' => userPWHash(addslashes($_POST['fe_pass']), FE_USER_SECRET),
					'fe_email' => $_POST['fe_email']
					);
			
			$pk = $this->fe_login->createNewUser($data);
			
			$this->setVariable('', 'md5', md5($pk.FE_USER_SECRET.$data['fe_registered']));
			$this->setVariable('', 'site', $_SERVER['HTTP_HOST']);
			$this->setVariable('', 'username', $data['fe_loginname']);
			$mail = $this->tplGet('', 'tpl.fe_register_mail.php');
			
			$rp = dirname($_SERVER["PHP_SELF"]);
			if($rp=="/") $rp = "";
			$mail = str_replace("href='Index", "href='http://".$_SERVER["HTTP_HOST"].$rp."/Index", $mail);
			
			mail($_POST['fe_email'], 'Ihre Registrierung', $mail, "FROM:".MailSender."\nContent-Type:text/html");
			$this->setVariable('', 'registered', 1);
			// }}}
		} else {
		    $this->setVariable('', 'registered', 0);
		}
		$html = $this->tplGet('', 'tpl.fe_register.php');
		$this->fw->setVariable('contentbody', 'CONTENT', $html);
		// }}}
	}
	function view_confirm($QS) {
		// {{{
		$U = $this->fe_login->confirmUser($QS[0]);
		if($U==false) {
			$this->setVariable('', 'error', 1);
		} else {
			$this->setVariable('', 'error', 0);
		}
		$html = $this->tplGet('', 'tpl.fe_confirm.php');
		$this->fw->setVariable('contentbody', 'CONTENT', $html);
		// }}}
	}
	
}
?>