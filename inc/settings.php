<?PHP

 /* This is the settings file for the quotes database.
  *
  * You can either alter the constants below to configure the db,
  * or you can copy them to settings.private.php (which is excluded
  * from the VCS, if you're interested in developing the db further)
  */

 if (file_exists(dirname(__FILE__) . '/settings.private.php')) {
  require_once(dirname(__FILE__) . '/settings.private.php');
 } else {
  define('BASE', '/quotes/'); // Absolute path to the quotes db

  define('MYSQL_SERVER', 'localhost'); // MySQL server
  define('MYSQL_USER', '');            // MySQL user
  define('MYSQL_PASS', '');            // MySQL password
  define('MYSQL_DB', '');              // MySQL db name
 }

?>
