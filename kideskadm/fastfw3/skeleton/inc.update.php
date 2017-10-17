<?php
$UPDATE_SQL = array();
/*
$UPDATE_SQL[0] = array('type' => 'newfield', 'table' => 'co_groups', 'field' => 'cg_closed', 'query' => 'ALTER TABLE  `co_groups` ADD  `cg_closed` TINYINT NOT NULL');
$UPDATE_SQL[2] = array('type' => 'newtable', 'table' => 'co_groups_apps', 'query' => 'CREATE TABLE `co_groups_apps` (`ga_pk` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,`ga_cg_fk` BIGINT NOT NULL ,`ga_ca_fk` BIGINT NOT NULL) ENGINE = MYISAM');
$UPDATE_SQL[21] = array('type' => 'newentry', 'countquery' => "SELECT count(*) FROM co_apps WHERE ca_id='zuweisen'", 'query'=>"INSERT INTO `co_apps` (`ca_fe_fk`, `ca_id`, `ca_title`, `ca_url`, `ca_url_groupapp`, `ca_publisher_url`, `ca_head`, `ca_type`, `ca_key`, `ca_info`, `ca_masterapp`) VALUES ( 1, 'zuweisen', 'Zuweisen', 'apps/app.zuweisen.php?action=anzeigealle', 'apps/app.zuweisen.php?action=anzeigegruppe', 'apps/app.zuweisen.php?action=zuweisen', '', 'display', '02fba780f8d1d6f8ec1ff76027d88d5e', 'Einen Artikel einer oder mehreren Personen zuweisen.', 0);");
$UPDATE_SQL[22] = array('type' => 'alter', 'query' => "ALTER TABLE `fe_user` CHANGE `fe_type` `fe_type` ENUM( 'user', 'app', 'group', 'cron' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'user'");
*/

$UPDATE_SQL[] = array(
	'type'  => 'newtable',
	'table' => 'backenduser',
	'query' => "CREATE TABLE IF NOT EXISTS backenduser (
				  bu_pk bigint NOT NULL AUTO_INCREMENT PRIMARY KEY,
				  bu_createdate datetime NOT NULL,
				  bu_changedate datetime NOT NULL,
				  bu_deleted tinyint NOT NULL,
				  bu_hidden tinyint NOT NULL,
				  bu_username varchar(50) NOT NULL,
				  bu_password varchar(50) NOT NULL,
				  bu_firstname varchar(50) NOT NULL,
				  bu_lastname varchar(50) NOT NULL,
				  bu_email varchar(100) NOT NULL,
				  bu_admin tinyint NOT NULL
				)"
);

$UPDATE_SQL[] = array(
	'type'  => 'newtable',
	'table' => 'backendrole',
	'query' => "CREATE TABLE IF NOT EXISTS backendrole (
					  br_pk bigint NOT NULL AUTO_INCREMENT PRIMARY KEY,
					  br_createdate datetime NOT NULL,
					  br_changedate datetime NOT NULL,
					  br_deleted tinyint NOT NULL,
					  br_hidden tinyint NOT NULL,
					  br_name varchar(255) NOT NULL,
					  br_description text NOT NULL
					)"
);

$UPDATE_SQL[] = array(
	'type'  => 'newtable',
	'table' => 'backendrolearea',
	'query' => array(
		"CREATE TABLE IF NOT EXISTS backendrolearea (
				  bra_pk bigint NOT NULL AUTO_INCREMENT PRIMARY KEY,
				  bra_createdate datetime NOT NULL,
				  bra_changedate datetime NOT NULL,
				  bra_deleted tinyint NOT NULL,
				  bra_hidden tinyint NOT NULL,
				  bra_id varchar(50) NOT NULL,
				  bra_name varchar(255) NOT NULL,
				  bra_description text NOT NULL,
				  bra_bra_fk int(11) NOT NULL,
				  bra_brag_fk int(11) NOT NULL,
				  bra_sort int(11) NOT NULL
				)"
	)
);
$UPDATE_SQL[] = array(
	'type'  => 'newtable',
	'table' => 'backendroleareagroup',
	'query' => "CREATE TABLE IF NOT EXISTS `backendroleareagroup` (
				  `brag_pk` bigint(20) NOT NULL AUTO_INCREMENT,
				  `brag_createdate` datetime NOT NULL,
				  `brag_changedate` datetime NOT NULL,
				  `brag_deleted` tinyint(4) NOT NULL DEFAULT '0',
				  `brag_hidden` tinyint(4) NOT NULL DEFAULT '0',
				  `brag_name` varchar(255) NOT NULL,
				  `brag_sort` int(11) NOT NULL DEFAULT '0',
				  PRIMARY KEY (`brag_pk`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8"
);

$UPDATE_SQL[] = array(
	'type'  => 'newtable',
	'table' => 'backendroleright',
	'query' => "CREATE TABLE IF NOT EXISTS backendroleright (
					  brr_pk int NOT NULL AUTO_INCREMENT PRIMARY KEY,
					  brr_createdate datetime NOT NULL,
					  brr_changedate datetime NOT NULL,
					  brr_deleted tinyint NOT NULL,
					  brr_hidden tinyint NOT NULL,
					  brr_br_fk bigint NOT NULL,
					  brr_id varchar(50) NOT NULL,
					  brr_own varchar(1) NOT NULL,
					  brr_other varchar(1) NOT NULL
					)"
);

$UPDATE_SQL[] = array(
	'type'  => 'newfield',
	'table' => 'backendrole',
	"field" => "br_roletype_fk",
	'query' => array("ALTER TABLE backendrole ADD br_roletype_fk int NOT NULL DEFAULT '0'")
);

$UPDATE_SQL[] = array(
	'type'  => 'newtable',
	'table' => 'backenduser_backendrole_nm',
	'query' => array(
		"CREATE TABLE backenduser_backendrole_nm (
				  bu_fk bigint NOT NULL,
				  br_fk bigint NOT NULL
				)",
		"ALTER TABLE backenduser_backendrole_nm ADD PRIMARY KEY (bu_fk, br_fk)"
	)
);

$UPDATE_SQL[] = array(
	"type"  => "newtable",
	"table" => "translations",
	"query" => "
		CREATE TABLE IF NOT EXISTS translations (
		tr_pk BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		  tr_createdate datetime NOT NULL,
		  tr_changedate datetime NOT NULL,
		  tr_deleted tinyint(4) NOT NULL,
		  tr_hidden tinyint(4) NOT NULL,
		  tr_area varchar(100) NOT NULL,
		  tr_label varchar(510) NOT NULL,
		  tr_language varchar(10) NOT NULL,
		  tr_translation text NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 "
);

// ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

// **AUTOAPPEND**
?>