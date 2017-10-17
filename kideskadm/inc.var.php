<?php
define('DB_TYPE', 'sqlite');
define('DB_NAME', 'sqlite');
define('DB_FILE', dirname(__FILE__).'/sqlitedb/sqlite.db');

define('USER_SECRET', '9823shdfsdfsdfg'); // Change this MD5-Salt for every project!

define('FILE_UPLOAD_FOLDER', dirname(__FILE__).'/uploads');

//define("rewriteLinks", true);
// to use rewriteLinks you need this rewrite-rules:
/*
  RewriteEngine On
  rewriteCond %{REQUEST_URI} !resources/
  RewriteRule ^(.*)$ index.php?fw_goto=$1 [QSA,L]
*/
// Then also set base-tag to docroot
//define("baseHref", "http://localhost/test/fw/");

define("SMTP_HOST", "localhost");
define("SMTP_PORT", 25);
define("SMTP_USERNAME", "");
define("SMTP_SECURE", "tls");
define("SMTP_PASSWORD", "");
define("SMTP_FROM", "");
?>