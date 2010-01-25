<?PHP

require_once('inc/database.php');

require_once('inc/header.php');

?>
<div class="oneThird right stats">
 <h2>Statistics</h2>
<?PHP

 $sql = 'SELECT COUNT(*), AVG(quote_rating) FROM quotes';
 $res = mysql_query($sql) or print(mysql_error());
 $row = mysql_fetch_array($res); $quotes = $row[0];

 echo '<p>We have <em>'.$row[0].'</em> quotes, with an average rating of <em>';
 echo round($row[1],2).'</em>. These quotes were contributed by some of our ';

 $sql = 'SELECT COUNT(*) FROM users';
 $res = mysql_query($sql) or print(mysql_error());
 $row = mysql_fetch_array($res); $users = $row[0];

 echo '<em>'.$row[0].'</em> users, who have made a total of <em>';

 $sql = 'SELECT COUNT(*) FROM ratings';
 $res = mysql_query($sql) or print(mysql_error());
 $row = mysql_fetch_array($res);

 echo $row[0].'</em> individual ratings, an average of <em>';
 echo round($row[0]/$users,1).'</em> quotes rated per user.</p>';

 if (isset($_SESSION['uid'])) { 
 
  echo '<h2>Your stats</h2>';
  echo '<p>';
 
  $sql = 'SELECT COUNT(*), AVG(quote_rating) FROM quotes WHERE user_id = '.$_SESSION['uid'];
  $res = mysql_query($sql) or print(mysql_error());
  $row = mysql_fetch_array($res);

  echo 'You have submitted <em>'.$row[0].'</em> quote';
  echo ($row[0] != 1 ? 's' : '').' that have an average rating of <em>';
  echo round($row[1],2).'</em>.';

  $sql = 'SELECT COUNT(*) FROM ratings WHERE user_id = '.$_SESSION['uid'];
  $res = mysql_query($sql) or print(mysql_error());
  $row = mysql_fetch_array($res);

  echo ' You have rated <em>'.$row[0].'</em> quote';
  if ($row[0] != 1) { echo 's'; }
  echo '.';

  if ($row[0] < $quotes) {
   echo ' Why not rate <a href="'.BASE.'unrated">some more</a>?.';
  } else {
   echo ' Wow, that\'s all of them. Why not <a href="'.BASE.'submit">add a new quote</a>?';
  }
 }
?>
</div>
<div>
 <h2>Welcome</h2>
 <p>Welcome to the quote db. You might think that this is just another bash
 clone, and you'd be partly right. But the quote db has some key differences.
 First off, we don't have any moderators. Every person starts with the same
 access to the site. When you submit a quote, it appears instantly on the 
 <a href="<?PHP echo BASE; ?>latest">latest quotes</a> page, where other users
 can rate it as good, bad or neutral. 
 </p>
 <p>
  If the quotes you submit get a poor rating, this will reflect on you and your
  <em>standing</em> will decrease. Your standing is displayed on the right of
  the menu bar when you're logged in, and operates on a scale of -10 to +10.
  If your standing drops too low, your ability to add quotes will be suspended.
  Conversely, if your quotes are met with a standing ovation (and good ratings),
  your standing will increase. This, in turn, affects how much your opinion is
  taken into account when you rate quotes. The higher standing you have, the
  more of an impact your ratings will have.
 </p>
 <p>
  The Quote DB now supports OpenID. Simply enter your
  OpenID identifier on the <a href="<?PHP echo BASE; ?>login">login</a>
  page.
 </p>
 <p>
  <strong>New!</strong> The Quote DB now fully supports Unicode (UTF-8) quotes
 </p>
</div>

<?PHP

require_once('inc/footer.php');

?>
