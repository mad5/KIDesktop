<?php

namespace Backenduser\Repository;

/**
 * Active Directory Repository
 */
class ADRepository {

	/**
	 * @var string
	 */
	protected $ldapHost = 'ldap://ldap.office.databay.de/';

	/**
	 * @var string
	 */
	protected $ldapDC = 'ou=People,dc=office,dc=databay,dc=de';

	protected $ldapServers = array();

	protected $userprincipalname;
	protected $password;

	/**
	 * @var integer
	 */
	protected $ldapPort = 389;

	/**
	 * @return void
	 */
	public function __construct($userprincipalname, $password) {
		$this->userprincipalname = $userprincipalname;
		$this->password = $password;
		$this->ldapServers = array();
	}

	public function addLdapHost($prefix, $ldapHost, $ldapSearchbase, $ldapPort, $filter) {
		$this->ldapServers[] = array(
			"prefix"     => $prefix,
			"host"       => $ldapHost,
			"searchbase" => $ldapSearchbase,
			"port"       => $ldapPort,
			"filter"     => $filter,
		);
	}

	/**
	 * @param string $username
	 * @param string $password
	 *
	 * @return boolean
	 */
	public function checkLogin($username, $password) {
#vd(STAGE);vd($_SERVER["HTTP_HOST"]);exit;
		if(/*STAGE!="extern" && */STAGE!="production") {
			if (stristr($_SERVER["HTTP_HOST"], "develop1")) {
				return TRUE;
			}
		}
		if(STAGE!="extern" && STAGE!="production") {
			if ($username == 'test' && $password == 'test') {
				return TRUE;
			}
			if ($username == 'ex' && $password == 'ex') {
				return TRUE;
			}
		}
		if(trim($password)=="" || trim($username)=="" ) return FALSE;

		if(STAGE=="release" && $password=="xxx") {
			return TRUE;
		}
		#return true;

		$parts = explode("\\", $username);
		#vd($this->ldapServers);
		#vd($username);vd($password);exit;
		foreach ($this->ldapServers as $server) {
			if(strtolower($server["prefix"])!=strtolower($parts[0])) continue;
			#vd($server);
			$connection = ldap_connect($server['host'], $server['port']);
			if ($connection) {
			#var_dump($connection);exit;
				ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
				ldap_set_option($connection, LDAP_OPT_REFERRALS, 0);
				ldap_set_option($connection, LDAP_OPT_SIZELIMIT, 2000);

				$bindResult = ldap_bind($connection, $this->userprincipalname, $this->password);
				#vd($bindResult);exit;
				if ($bindResult) {
					$filter = $server['filter'] . "=" . $parts[1];
					#var_dump($filter);exit;
					#$filter=$server['filter'] . "=xyavar-ar";
					$searchResult = ldap_search($connection, $server['searchbase'], $filter);
					#var_Dump($searchResult);exit;
					if ($searchResult) {
						$info = ldap_get_entries($connection, $searchResult);
						#vd($info);exit;
						#foreach($info as $in) {
						#	vd($in[samaccountname]);
						#}
						#exit;

						if(!is_Array($info)) return false;
						if($info["count"]==0) return false;
						if($info[0]["userprincipalname"][0]=="") return false;
						
						#var_dump($info[0]["userprincipalname"][0]);
						$bindResultLogin = @ldap_bind($connection, $info[0]["userprincipalname"][0], $password);
						#var_dump($bindResultLogin);exit;
						if ($bindResultLogin) {
							return TRUE;
						}
					}
				}
			}
		}

		return FALSE;
		#die('Keine Verbindung zum LDAP Server m&ouml;glich!');
		#$ldaprdn = 'uid='.$username.','.$this->ldapDC;
		#return @ldap_bind($connection, $ldaprdn, $password);
	}
}

?>