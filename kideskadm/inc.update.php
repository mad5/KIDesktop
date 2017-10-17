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

// ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

$UPDATE_SQL[] = array("type" => "newtable", "table" => "translations", "query" => "
CREATE TABLE translations (
tr_pk BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tr_createdate datetime NOT NULL,
  tr_changedate datetime NOT NULL,
  tr_deleted tinyint(4) NOT NULL,
  tr_hidden tinyint(4) NOT NULL,
  tr_area varchar(100) NOT NULL,
  tr_label varchar(510) NOT NULL,
  tr_language varchar(10) NOT NULL,
  tr_translation text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ");

$UPDATE_SQL[] = array("type" => "newtable", "table" => "rechner", "query" => "
CREATE TABLE IF NOT EXISTS rechner (
			re_pk BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
			re_createdate datetime NOT NULL,
			re_changedate datetime NOT NULL,
			re_deleted tinyint NOT NULL,
			re_hidden tinyint NOT NULL,
			re_bu_fk bigint NOT NULL,
			re_kind varchar(255) NOT NULL,
			re_ort varchar(255) NOT NULL,
			re_beschreibung text NOT NULL,
			re_letzteip varchar(255) NOT NULL,
			re_zuletztonline datetime NOT NULL,
			re_offlineab tinyint NOT NULL,
			re_offlinebis tinyint NOT NULL,
			re_ausab tinyint NOT NULL,
			re_ausbis tinyint NOT NULL,
			re_nutzungsdauerinsgesamt tinyint NOT NULL,
			re_hash varchar(255) NOT NULL,
			re_bild varchar(255) NOT NULL
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ");

$UPDATE_SQL[] = array("type" => "newtable", "table" => "bereich", "query" => "
CREATE TABLE IF NOT EXISTS bereich (
			be_pk BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
			be_createdate datetime NOT NULL,
			be_changedate datetime NOT NULL,
			be_deleted tinyint NOT NULL,
			be_hidden tinyint NOT NULL,
			be_bu_fk bigint NOT NULL,
			be_name varchar(255) NOT NULL,
			be_icon varchar(255) ,
			be_reihenfolge varchar(255) NOT NULL,
			be_freigegeben varchar(255) NOT NULL
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ");

$UPDATE_SQL[] = array("type" => "newtable", "table" => "kategorie", "query" => "
CREATE TABLE IF NOT EXISTS kategorie (
			ka_pk BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
			ka_createdate datetime NOT NULL,
			ka_changedate datetime NOT NULL,
			ka_deleted tinyint NOT NULL,
			ka_hidden tinyint NOT NULL,
			ka_bu_fk bigint NOT NULL,
			ka_name varchar(255) NOT NULL,
			ka_bereich bigint NOT NULL,
			ka_rechner bigint NOT NULL
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ");

$UPDATE_SQL[] = array("type" => "newtable", "table" => "eintrag", "query" => "
CREATE TABLE IF NOT EXISTS eintrag (
			ei_pk BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
			ei_createdate datetime NOT NULL,
			ei_changedate datetime NOT NULL,
			ei_deleted tinyint NOT NULL,
			ei_hidden tinyint NOT NULL,
			ei_bu_fk bigint NOT NULL,
			ei_name varchar(255) NOT NULL,
			ei_icon varchar(255),
			ei_kategorie bigint NOT NULL,
			ei_bereich bigint NOT NULL,
			ei_rechner varchar(255) NOT NULL,
			ei_typ varchar(255) NOT NULL,
			ei_befehl varchar(255) NOT NULL,
			ei_hosts text NOT NULL
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ");

/* 26.01.2017 20:58 */ $UPDATE_SQL[] = array("type" => "newfield", "table" => "eintrag", "field" => "ei_hosts", 
"query" => array( 'ALTER TABLE  `eintrag` ADD  `ei_hosts` text NOT NULL DEFAULT ""', 
) );

/* 27.01.2017 16:44 */ $UPDATE_SQL[] = array("type" => "newfield", "table" => "rechner", "field" => "re_hash", 
"query" => array( 'ALTER TABLE  `rechner` ADD  `re_hash` varchar(255) NOT NULL DEFAULT ""', 
) );

$UPDATE_SQL[] = array("type" => "newtable", "table" => "mailkontakt", "query" => "
CREATE TABLE IF NOT EXISTS mailkontakt (
			mk_pk BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
			mk_createdate datetime NOT NULL,
			mk_changedate datetime NOT NULL,
			mk_deleted tinyint NOT NULL,
			mk_hidden tinyint NOT NULL,
			mk_name varchar(255) NOT NULL,
			mk_email varchar(255) NOT NULL,
			mk_rechner bigint NOT NULL,
			mk_bild varchar(255) NOT NULL
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ");

$UPDATE_SQL[] = array("type" => "newtable", "table" => "nachricht", "query" => "
CREATE TABLE IF NOT EXISTS nachricht (
			na_pk BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
			na_createdate datetime NOT NULL,
			na_changedate datetime NOT NULL,
			na_deleted tinyint NOT NULL,
			na_hidden tinyint NOT NULL,
			na_sender varchar(255) NOT NULL,
			na_mailkontakt bigint NOT NULL,
			na_rechner bigint NOT NULL,
			na_nachricht text NOT NULL,
			na_uebertragen datetime NOT NULL,
			na_gelesen datetime NOT NULL
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ");

/* 31.01.2017 07:46 */ $UPDATE_SQL[] = array("type" => "newfield", "table" => "rechner", "field" => "re_bild", 
"query" => array( 'ALTER TABLE  `rechner` ADD  `re_bild` varchar(255) NOT NULL DEFAULT ""', 
) );

/* 31.01.2017 07:47 */ $UPDATE_SQL[] = array("type" => "newfield", "table" => "mailkontakt", "field" => "mk_bild", 
"query" => array( 'ALTER TABLE  `mailkontakt` ADD  `mk_bild` varchar(255) NOT NULL DEFAULT ""', 
) );

/* 15.02.2017 11:00 */ $UPDATE_SQL[] = array("type" => "newfield", "table" => "mailkontakt", "field" => "mk_bu_fk", 
"query" => array( 'ALTER TABLE  `mailkontakt` ADD  `mk_bu_fk` bigint NOT NULL DEFAULT 0', 
) );

/* 15.02.2017 16:36 */ $UPDATE_SQL[] = array("type" => "newfield", "table" => "backenduser", "field" => "bu_hash", 
"query" => array( 'ALTER TABLE  `backenduser` ADD  `bu_hash` varchar(255) NOT NULL default ""', 
) );


/* 16.02.2017 16:36 */ $UPDATE_SQL[] = array("type" => "newfield", "table" => "eintrag", "field" => "ei_ips", 
"query" => array( 'ALTER TABLE  `eintrag` ADD  `ei_ips` textarea NOT NULL default ""', 
) );

// **AUTOAPPEND**
?>