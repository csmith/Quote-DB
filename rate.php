<?PHP

 require_once('inc/account.php');
 require_once('inc/settings.php');
 require_once('inc/database.php');

 if (!isset($_SESSION['uid'])) {
  header('Location: '.BASE.'login');
  exit;
 }

 if (isset($_POST['quote']) && ctype_digit($_POST['quote'])) {
  if (isset($_POST['rateup'])) { $base = 1; } elseif (isset($_POST['ratedown'])) { $base = -1; } else { $base = 0; }
  $base *= (($_SESSION['standing'] + 10) / 10);
  $sql = 'SELECT rating_change FROM ratings WHERE user_id = '.$_SESSION['uid'].' AND quote_id = '.m($_POST['quote']);
  $res = mysql_query($sql);
  if (mysql_num_rows($res) == 0) {
   if ($_POST['quote'] != 62) {
    mysql_query('INSERT INTO ratings (user_id, quote_id, rating_change) VALUES ('.$_SESSION['uid'].', '.m($_POST['quote']).', '.$base.')');
    mysql_query('UPDATE quotes SET quote_rating = quote_rating + '.$base.', quote_rated = quote_rated + 1 WHERE quote_id = '.m($_POST['quote']));
    require('dostanding.php');
   }
   header('Location: '.$_POST['ref']);
   exit;
  } else {
   header('Location: '.$_POST['ref']);
   exit;  
  }
 } else {
  die('Invalid quote');
 }

?>
