<?PHP

 require_once('inc/database.php');

 define('TITLE', 'Browse quotes');

 require_once('inc/header.php');

 if (isset($_GET['q']) && ctype_digit($_GET['q'])) {

  $sql = 'SELECT quote_id, quote_quote, quote_rating FROM quotes WHERE quote_id = '.m($_GET['q']);
  $res = mysql_query($sql);
  
  if (mysql_num_rows($res) == 0) {
   echo '<h2>Error</h2><p>That quote wasn\'t found. Try <a href="?">browsing</a> for it?</p>';
  } else {

 echo '<h2>Viewing quote</h2>';

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
   Quote #<?PHP echo $row['quote_id']; ?>.
   Rating <?PHP echo round($row['quote_rating'],2); ?>.
<?PHP

 if (!isset($_SESSION['uid'])) {
  echo ' <a href="'.BASE.'login">Login to rate</a>.';
 }

?>
  </p>
  <div class="quotebody">
   <?PHP echo nl2br(htmlentities($row['quote_quote'], ENT_QUOTES, 'UTF-8')); ?>
  </div>
 </div>
<?PHP
 }


  }


 } else {

  $offset = 0;
  if (isset($_GET['o']) && ctype_digit($_GET['o'])) {
   $offset = $_GET['o'];
  }

 $sql = 'SELECT COUNT(*) FROM quotes';
 $res = mysql_query($sql);
 $row = mysql_fetch_array($res);
 define('QUOTES', $row[0]);

 $sql = 'SELECT quote_id, quote_quote, quote_rating FROM quotes ORDER BY quote_id LIMIT '.$offset.',25';
 $res = mysql_query($sql)

?>
<div>
 <h2>Browse quotes</h2>
<?PHP

 echo '<div class="nav">';
 if ($offset > 0) {
  echo '<a href="browse?o='.($offset-25).'">&lt;&lt; Previous</a> |';
 }
 if ($offset + 25 > QUOTES) { $max = QUOTES; } else { $max = $offset + 25; }
 echo ' Viewing quotes '.(1+$offset).' to '.$max.' of '.QUOTES.'.';
 if ($max < QUOTES) {
  echo ' | <a href="browse?o='.$max.'">Next &gt;&gt;</a>';
 }
 echo '</div>';


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
   <?PHP echo nl2br(htmlentities($row['quote_quote'], ENT_QUOTES, 'UTF-8')); ?>
  </div>
 </div>
<?PHP
 }

 echo '<div class="nav">';
 if ($offset > 0) {
  echo '<a href="browse?o='.($offset-25).'">&lt;&lt; Previous</a> |';
 }
 if ($offset + 25 > QUOTES) { $max = QUOTES; } else { $max = $offset + 25; }
 echo ' Viewing quotes '.(1+$offset).' to '.$max.' of '.QUOTES.'.';
 if ($max < QUOTES) {
  echo ' | <a href="browse?o='.$max.'">Next &gt;&gt;</a>';
 }
 echo '</div>';

?>
</div>
<?PHP
}
 require_once('inc/footer.php');

?>
