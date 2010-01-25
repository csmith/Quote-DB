<?PHP

 require_once('inc/database.php');

 class quote {
  public $id;
  public $owner;
  public $good = array();
  public $neutral = array();
  public $bad = array();
 }

 $quotes = array();
 $users = array();

 // Read the quotes into an array

 $sql = 'SELECT quote_id, user_id FROM quotes';
 $res = mysql_query($sql);
 while ($row = mysql_fetch_assoc($res)) {
  if (!isset($users[($row['user_id'])])) {
   $users[($row['user_id'])] = 0;
  }

  $quotes[($row['quote_id'])] = new quote;
  $quotes[($row['quote_id'])]->id = $row['quote_id'];
  $quotes[($row['quote_id'])]->owner = $row['user_id'];
 }

 // And read the ratings in

 $sql = 'SELECT user_id, quote_id, rating_change FROM ratings';
 $res = mysql_query($sql);
 while ($row = mysql_fetch_assoc($res)) {
  $q =& $quotes[($row['quote_id'])];
  $u =  $row['user_id'];

  if (!isset($users[$u])) { $users[$u] = 0; }
  
  if ($row['rating_change'] > 0) {
   $q->good[] = $u;
  } elseif ($row['rating_change'] < 0) {
   $q->bad[] = $u;
  } else {
   $q->neutral[] = $u;
  }
 }

 define('USERS', count($users));
 define('QUOTES', count($quotes));

 // First pass: standings based on rating agreement

 foreach ($quotes as $quote) {
  $num = count($quote->good) + count($quote->bad);

  if ($num == 0) { continue; }

  $off = (1/QUOTES) * ($num/USERS);
  $bad = $bad / $num;

  foreach ($quote->bad as $uid) {
   $users[$uid] += $off * $bad;
  }
  foreach ($quote->good as $uid) {
   $users[$uid] += $off * (1 - $bad);
  }
  foreach ($quote->neutral as $uid) {
   $users[$uid] += $off * 0.5;
  } 
 }

 $fstanding = 0;
 foreach ($users as $stand) { $fstanding += $stand; }
 define('FSTANDING', $fstanding);

 // Second pass: quote ratings

 foreach ($quotes as $quote) {
  $score = 0;

  $off = 10/FSTANDING;

  foreach ($quote->bad as $uid) {
   $score -= $off * $users[$uid];
  }
  foreach ($quote->good as $uid) {
   $score += $off * $users[$uid]; 
  }
  echo "Quote: ".$quote->id.' scores '.$score.'<br>';
 }

 echo "<hr>";

 // Third pass: user standings
 
?>
