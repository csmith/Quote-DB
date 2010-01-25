<?PHP

 require_once('inc/database.php');

 define('TITLE', 'Worst');

 require_once('inc/header.php');

 $sql = 'SELECT quote_id, quote_quote, quote_rating FROM quotes ORDER BY quote_rating LIMIT 0,25';
 $res = mysql_query($sql)

?>
<div>
 <h2>Worst quotes</h2>
<?PHP

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

?>
</div>
<?PHP

 require_once('inc/footer.php');

?>
