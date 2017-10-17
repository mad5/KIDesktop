CREATE TABLE IF NOT EXISTS `fe_user` (
  `fe_pk` bigint(20) NOT NULL AUTO_INCREMENT,
  `fe_registered` datetime NOT NULL,
  `fe_confirmed` datetime NOT NULL,
  `fe_deleted` datetime NOT NULL,
  `fe_loginname` varchar(255) NOT NULL,
  `fe_password` varchar(32) NOT NULL,
  `fe_email` varchar(255) NOT NULL,
  `fe_lastactive` datetime NOT NULL,
  `fe_lastlogin` datetime NOT NULL,
  `fe_thislogin` datetime NOT NULL,
  `fe_anrede` varchar(50) NOT NULL,
  `fe_vorname` varchar(255) NOT NULL,
  `fe_nachname` varchar(255) NOT NULL,
  PRIMARY KEY (`fe_pk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;