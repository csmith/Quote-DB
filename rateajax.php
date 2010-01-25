<?PHP

 require_once('inc/account.php');
 require_once('inc/settings.php');
 require_once('inc/database.php');

 if (!isset($_SESSION['uid'])) {
  header('Location: '.BASE.'login');
  exit;
 }

 if (isset($_GET['quote']) && ctype_digit($_GET['quote'])) {
  if (isset($_GET['rateup'])) { $base = 1; } elseif (isset($_GET['ratedown'])) { $base = -1; } else { $base = 0; }
  $base *= (($_SESSION['standing'] + 10) / 10);
  $sql = 'SELECT rating_change FROM ratings WHERE user_id = '.$_SESSION['uid'].' AND quote_id = '.m($_GET['quote']);
  $res = mysql_query($sql);
  if (mysql_num_rows($res) == 0) {
   if ($_GET['quote'] != 62) {
    mysql_query('INSERT INTO ratings (user_id, quote_id, rating_change) VALUES ('.$_SESSION['uid'].', '.m($_GET['quote']).', '.$base.')');
    mysql_query('UPDATE quotes SET quote_rating = quote_rating + '.$base.', quote_rated = quote_rated + 1 WHERE quote_id = '.m($_GET['quote']));
    require('dostanding.php');
   }
  }
  doRate($_GET['quote'], false);
 } else {
  die('Invalid quote');
 }

?>
