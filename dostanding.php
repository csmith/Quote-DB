<?PHP

/* require_once('inc/database.php');

 $users = array();

 $sql = 'SELECT user_id, quote_rating FROM quotes WHERE quote_rated > 0';
 $res = mysql_query($sql);
 while ($row = mysql_fetch_array($res)) {
  $user =& $users[($row['user_id'])];
  $user += (($row['quote_rating']-1)/50) * (10 - $user);
 }

 foreach ($users as $uid=>$st) {
  mysql_query('UPDATE users SET user_standing = '.$st.' WHERE user_id = '.$uid);
 }*/

 require('dostandingbeta.php');

?>
