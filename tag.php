<?PHP

 require_once('inc/database.php');

 if (isset($_GET['tag'])) {
  define('TAG', $_GET['tag']);
 } else {
  header('Location: ' . BASE . 'latest');
  exit;
 }

 define('TITLE', 'Browse quotes tagged ' . htmlentities(TAG, ENT_QUOTES, 'UTF-8'));

 require_once('inc/header.php');
 require_once('inc/tags.php');

 $offset = 0;
 if (isset($_GET['o']) && ctype_digit($_GET['o'])) {
  $offset = $_GET['o'];
 }

 $quotes = array();
 $sql = 'SELECT DISTINCT(quote_id) FROM tags WHERE tag_text = \'' . m(TAG) . '\'';
 $res = mysql_query($sql);
 while ($row = mysql_fetch_assoc($res)) {
  $quotes[] = $row['quote_id']; 
 }

 $where = 'WHERE quote_id IN (' . implode(', ', $quotes) . ')';

 $sql = 'SELECT COUNT(*) FROM quotes ' . $where;
 $res = mysql_query($sql);
 $row = mysql_fetch_array($res);
 define('QUOTES', $row[0]);

 $sql = 'SELECT quote_id, quote_quote, quote_rating FROM quotes ' . $where . ' ORDER BY quote_id LIMIT '.$offset.',25';
 $res = mysql_query($sql)

?>
<div>
 <h2>Browse quotes tagged <?PHP echo htmlentities(TAG, ENT_QUOTES, 'UTF-8'); ?></h2>
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
<?PHP showTags($row['quote_id']); ?>
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

 require_once('inc/footer.php');

?>
