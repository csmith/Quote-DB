<?PHP

 require_once('inc/database.php');
 require_once('inc/account.php');
 require_once('inc/settings.php');

 if (!isset($_SESSION['uid'])) { header('Location: '.BASE.'latest'); exit; }

 define('TITLE', 'Unrated');

 require_once('inc/header.php');

 $sql = 'SELECT quote_id FROM ratings WHERE user_id = '.$_SESSION['uid'];
 $res = mysql_query($sql);
 $s = '(1';
 while ($row = mysql_fetch_array($res)) {
  $s .= ' AND quote_id <> '.$row[0];
 }
 $s .= ')';

 $sql = 'SELECT quote_id, quote_quote, quote_rating FROM quotes WHERE '.$s.' ORDER BY quote_id DESC LIMIT 0,25';
 $res = mysql_query($sql)

?>
<div>
 <h2>Unrated quotes</h2>
<?PHP

 if (mysql_num_rows($res) == 0) {
  echo '<p>You\'ve rated every quote in the database! Why not <a href="'.BASE.'"submit">add a new one?</a></p>';
 }

 $i = 0;
 while ($row = mysql_fetch_array($res)) {
  $i = 1 - $i;
  if ($i == 1) { $e = 'even'; } else { $e = 'odd'; }
?>
 <div class="quote <?PHP echo $e; ?>">
<?PHP
 if (isset($_SESSION['uid'])) {
  doRate($row['quote_id']);
 }
?>
  <p>
   Quote <a href="<?PHP echo BASE; ?>browse?q=<?PHP echo $row['quote_id']; ?>">#<?PHP echo $row['quote_id']; ?></a>.
   Rating <?PHP echo round($row['quote_rating'],2); ?>.
<?PHP

 if (!isset($_SESSION['uid'])) {
  echo ' <a href="'.BASE.'login">Login to rate</a>.';
 }

?>
  </p>
  <div class="quotebody">
   <?PHP echo nl2br(htmlentities($row['quote_quote'])); ?>
  </div>
 </div>
<?PHP
 }

?>
</div>
<?PHP

 require_once('inc/footer.php');

?>
