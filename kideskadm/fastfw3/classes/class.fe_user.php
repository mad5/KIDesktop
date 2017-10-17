<?php
namespace classes;

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
class fe_user {
	public $fw;
	public $userData;
	public function __construct($params=array()) {
        $this->fw = $GLOBALS["FastFW"];
		if(getS('fe_user')!='') {
			$this->userData = $this->fw->DC->getByQuery('SELECT * FROM fe_user WHERE fe_pk='.(int)getS('fe_user', 'fe_pk'));
                        unset($this->userData['fe_password']);
                        if(getS("aygonetMegaMaster")!=1) {
                        	$this->fw->DC->sendQuery('UPDATE fe_user SET fe_lastactive=now() WHERE fe_pk='.(int)getS('fe_user', 'fe_pk'));
                        }
		} else {
			
			if(!stristr($_SERVER['REQUEST_URI'], "fw_ajax")) {
				$page = $this->fw->routedLocation;
				if($page=='') $page = "Index";
				$P = $_SERVER['REQUEST_URI'];
				if(stristr($P, '?')) {
					$P = hinter($P, '?');
				} else $P = $page;
				setS("lastFullPage", $P);
			}
			
			
			$this->userData = false;
			if(isset($params['requirelogin']) && $params['requirelogin']) {
				if(stristr($_SERVER['REQUEST_URI'], "fw_ajax")) {
					die("not logged in.");
				}
				setS('requestBeforeLogin', $_SERVER['REQUEST_URI']);
				setS('pageBeforeLogin', $this->fw->routedLocation);
				jump2page('fe_login');
			}
			if(isset($params['checklogin']) && $params['checklogin']) {

				#$this->fw->fw_useSingleClass('fe_login');
				$this->fw->fe_login = new \fe_login\fe_login();
				if(isset($_COOKIE['fe_login'])) $data = $this->fw->fe_login->getUserByCookie($_COOKIE['fe_login']);
                                else $data = '';
				if($data!='') {
					setS('fe_user', $data);

					$page = $this->fw->routedLocation;
					if($page=='') $page = "Index";

					$P = $_SERVER['REQUEST_URI'];
					if(stristr($P, '?')) {
						$P = hinter($P, '?');
					} else $P = $page;

					setS('requestBeforeLogin', '');
					setS('pageBeforeLogin', '');

					jump2page($page);
				}
			}
		}
	}
	
	public function getUserData($fe_pk, $field='') {
		$data = $this->fw->DC->getByQuery('SELECT * FROM fe_user WHERE fe_pk='.(int)$fe_pk);
                unset($data['fe_password']);
		if($data!='') {
			if($field!='') return($data[$field]);
			else return($data);
		}
		return(false);
	}

	public function login($fe_pk) {
		$user = $this->getUserData($fe_pk);
		if($user==false) return false;
		setS('fe_user', $user);
		return true;
	}



}
?>
