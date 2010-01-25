<?PHP

 require_once('inc/database.php');

 session_name('qdb');
 session_start();

 if (!isset($_SESSION['uid']) && isset($_COOKIE['quotedbperm'])) {
  $sql = 'SELECT user_id, user_standing, user_name FROM users WHERE user_hash = \''.m($_COOKIE['quotedbperm']).'\'';
  $res = mysql_query($sql);
  if (mysql_num_rows($res) == 1) {
   $row = mysql_fetch_array($res);
   $_SESSION['uid'] = $row['user_id'];
   $_SESSION['uname'] = $row['user_name'];
   $_SESSION['standing'] = $row['user_standing'];
  }
 }

 if (isset($_SESSION['standing'])) {
  $sql = 'SELECT user_standing FROM users WHERE user_id = '.$_SESSION['uid'];
  $res = mysql_query($sql);
  $row = mysql_fetch_array($res);
  $_SESSION['standing'] = $row[0];
 }

 function doRate($id, $div = true) {
  $sql = 'SELECT rating_change FROM ratings WHERE user_id = '.$_SESSION['uid'].' AND quote_id = '.$id;
  $res = mysql_query($sql);
  if (mysql_num_rows($res) == 0) {
   if ($div) {
    echo '<div class="rate" id="rate'.$id.'">';
   }
   echo '<form action="'.BASE.'rate" method="post">';
   echo ' <input type="hidden" name="ref" value="'.$_SERVER['REQUEST_URI'].'">';
   echo ' <input type="hidden" name="quote" value="'.$id.'">';
   echo ' <input type="image" name="rateup" src="'.BASE.'res/plus.png" value="up" alt="Good" title="This is a good quote" onClick="return doRate(\'rateup\', '.$id.');">';
   echo ' <input type="image" name="rateneutral" src="'.BASE.'res/neutral.png" value="neutral" alt="Neutral" title="This is an average quote" onClick="return doRate(\'rateneutral\', '.$id.');">';
   echo ' <input type="image" name="ratedown" src="'.BASE.'res/minus.png" value="down" alt="Bad" title="This is a bad quote" onClick="return doRate(\'ratedown\', '.$id.');">';
   echo '</form>';
   if ($div) {
    echo '</div>';
   }
  } else {
   $row = mysql_fetch_array($res);
   if ($div) {
    echo '<div class="rate">';
   }
   if ($row['rating_change'] > 0) {
    echo 'You rated this quote as good.';
   } elseif ($row['rating_change'] == 0) {
    echo 'You rated this quote as average.';
   } else {
    echo 'You rated this quote as bad.';
   }
   if ($div) {
    echo '</div>';
   }
  }
 }

?>
