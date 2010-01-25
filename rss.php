<?PHP

 require_once('inc/database.php');

 header('Content-type: text/xml');


 $sql  = 'SELECT quote_id, quote_quote, quote_rating FROM quotes ';

 if (isset($_GET['id'])) {
  $sql .= 'WHERE quote_id = ' . ((int) $_GET['id']) . ' ';
 }

 $sql .= 'ORDER BY quote_id DESC LIMIT 0,25';
 $res = mysql_query($sql)

?>
<?PHP echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0">
<channel>
 <title>Quote db</title>
 <link>http://apps.MD87.co.uk/quotes/</link>
 <description>Latest quotes from the quote database</description>
<?PHP
 while ($row = mysql_fetch_array($res)) {
   echo '<item><title>Quote '.$row['quote_id'].'</title>';
   echo '<guid isPermaLink="true">http://apps.MD87.co.uk/quotes/browse?q='.$row['quote_id'].'</guid><description><![CDATA[';
   echo nl2br(htmlentities($row['quote_quote'], ENT_QUOTES, 'UTF-8')); 
   echo ']]></description></item>';
 }

?>
</channel>
</rss>
