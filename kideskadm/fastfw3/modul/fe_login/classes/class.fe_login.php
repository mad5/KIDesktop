<?php
namespace fe_login;
class fe_login {
	public $fw;
	public $id;
	public $params = array();
	public function __construct($params=array()) {
		// {{{
		$this->params = $params;
		$this->fw = $GLOBALS["FastFW"];
		// }}}
	}


	public function getUserByCookie($cookie) {
		$data = $this->fw->DC->getByQuery("SELECT * FROM fe_user WHERE md5(concat(fe_pk,fe_registered,fe_loginname))='".addslashes($cookie)."' AND fe_deleted='"._DATE0."' ");
		return $data;
	}

	public function getLostUserData($lost) {
                $lost = strtolower(trim(addslashes($lost)));
                if(!defined('DISABLE_LOST_BY_EMAIL') || DISABLE_LOST_BY_EMAIL!=true) {
                    $Q = "SELECT * FROM fe_user WHERE (lcase(fe_loginname)=lcase('".$lost."') OR fe_email='".addslashes($lost)."') AND fe_deleted='"._DATE0."' ";
                } else {
                    $Q = "SELECT * FROM fe_user WHERE (lcase(fe_loginname)=lcase('".$lost."')) AND fe_deleted='"._DATE0."' ";
                }
		$data = $this->fw->DC->getByQuery($Q);
		return $data;
	}

	public function setNewPassword($pw, $fe_pk) {
		$data = array('fe_password' => $pw);
		$this->fw->DC->update($data, 'fe_user', $fe_pk, 'fe_pk');
		return true;
	}

	public function createNewUser($data) {
		$pk = $this->fw->DC->insert($data, 'fe_user');
		return $pk;
	}

	public function confirmUser($key) {
		$Q = "SELECT * FROM fe_user WHERE md5(concat(fe_pk,'".FE_USER_SECRET."',fe_registered)) = '".addslashes($key)."' ";
		$U = $this->fw->DC->getByQuery($Q);
		if($U!='') {
			$this->fw->DC->sendQuery("UPDATE fe_user SET fe_confirmed=now() WHERE fe_pk='".$U['fe_pk']."' ");
			return true;
		}
		return false;
	}

	public function performLogin($username, $password) {
		// {{{
		$Q = "SELECT * FROM fe_user WHERE fe_loginname='".addslashes($username)."' AND fe_password='".userPWHash(addslashes($password),FE_USER_SECRET)."' AND fe_deleted='"._DATE0."' ";
		#echo $Q;exit;
		$user = $this->fw->DC->getByQuery($Q);
#vd($Q);
		$loginOk = false;
		if($user!='') {
			// {{{
			$this->fw->DC->sendQuery("UPDATE fe_user SET fe_lastactive=now() WHERE fe_pk='".$user['fe_pk']."' ");
			$loginOk = true;
			// }}}
		} else {
			// {{{
			if(defined('enableEmailAccountLogin') && enableEmailAccountLogin && defined('emailServerForLoginCheck')) {
				// Imap-Login. Besser einen Hook auslagern
				
				if(!isset($GLOBALS["allowedUserNames"]) || in_array($username, $GLOBALS["allowedUserNames"])) {
				
				$Q = "SELECT * FROM fe_user WHERE fe_email like '".addslashes($username)."@%' AND fe_deleted='"._DATE0."' ";
				$u = $this->fw->DC->getByQuery($Q);
				
				if($u=='') {
 				      $u = array(
 				         "fe_registered" => now(),
 				         "fe_loginname" => $username,
 				         "fe_email" => $username."@".enableServerDomain
 				      );
 				      $u["fe_pk"] = $this->fw->DC->insert($u, "fe_user");
				      #die($Q);
				 }
				 
				if($u!='') {
					$mn = "{".emailServerForLoginCheck."}INBOX";
					imap_timeout(1,1);
					$mbox = @imap_open($mn, $username, $password, OP_HALFOPEN, 1);
					if($mbox!=false) {
						$loginOk = true;
						$user = $u;
						imap_close($mbox);
					}
				}
				}
			}
			// }}}
		}

		return($user);
		// }}}
	}

	public function getUserKey($user=NULL) {
		// {{{
		#if($user==NULL) $user =
		$key = md5($user['fe_pk'].'-'.$user['fe_registered'].'-'.$user['fe_password']);
		return $key;
		// }}}
	}

	public function findUserByKey($key) {
		// {{{
		$Q = "SELECT * FROM fe_user WHERE md5(concat(fe_pk,'-',fe_registered,'-',fe_password))='".addslashes($key)."' AND fe_deleted='".date0()."' ";
		$user = $this->fw->DC->getByQuery($Q);

		return($user);
		// }}}
	}

	public function getUserByKey($key) {
		// {{{
		$user = $this->findUserByKey($key);
		if($user!='') {
			$this->fw->DC->sendQuery("UPDATE fe_user SET fe_lastactive=now() WHERE fe_pk='".$user['fe_pk']."' ");
		}
		setS('fe_user', $user);
		return($user);
		// }}}
	}
}
?>