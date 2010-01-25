<?PHP

 require_once('inc/settings.php');

 mysql_connect(MYSQL_SERVER, MYSQL_USER, MYSQL_PASS);
 mysql_select_db(MYSQL_DB);

 function m ($t) { return mysql_real_escape_string($t); }


?>
