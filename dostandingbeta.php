<?PHP

 require_once('inc/database.php');

 $users = array();
 $quotes = array();

 $sql = 'SELECT COUNT(*), user_id FROM quotes GROUP BY user_id';
 $res = mysql_query($sql);
 $max = 0;
 while ($row = mysql_fetch_array($res)) {
  if ($row[0] > $max) { $max = $row[0]; }
  $quotes[($row[1])] = $row[0];
 }

 $sql = 'SELECT u.user_id, r.rating_change, r.quote_id, r.user_id AS rater FROM ratings AS r, quotes AS u WHERE u.quote_id = r.quote_id';

 $qr = array();

 $res = mysql_query($sql);
 while ($row = mysql_fetch_array($res)) {
  if (!isset($qr[($row['quote_id'])])) {
   $qr[($row['quote_id'])] = array('for'=>array(),'against'=>array());
  }
  $user =& $users[($row['user_id'])];
  if ($row['rating_change'] == 0) {
   $user += 0;
  } elseif ($row['rating_change'] > 0) {
   $user += 1;
   $qr[($row['quote_id'])]['for'][] = $row['rater'];
  } else {
   $user -= 1;
   $qr[($row['quote_id'])]['against'][] = $row['rater'];
  }
 }

 foreach ($qr as $quote => $rankings) {
  $for = count($rankings['for']);
  $against = count($rankings['against']);
  $total = $for + $against;
  if ($for == 0) {
   foreach ($rankings['against'] as $uid) {
    $users[$uid] += 0.5;
   }
  } elseif ($against == 0) {
   foreach ($rankings['for'] as $uid) {
    $users[$uid] += 0.5;
   }
  } else {
   $forscore = $for/$total - 0.5;
   $againstscore = $against/$total - 0.5;
   foreach ($rankings['for'] as $uid) {
    $users[$uid] += $forscore;
   }
   foreach ($rankings['against'] as $uid) {
    $users[$uid] -= $againstscore;
   }
  }
 }

 foreach ($users as $uid => $user) {
  #echo $uid.' ==&gt; '.$user.' == &gt; '.(10*$user*(1+$quotes[$uid])/pow($max,2)).'<br>';
 }

 $nusers = array();

 $sql = 'SELECT u.user_id, r.rating_change FROM ratings AS r, quotes AS u WHERE u.quote_id = r.quote_id';

 $res = mysql_query($sql);
 while ($row = mysql_fetch_array($res)) {
  $user =& $nusers[($row['user_id'])];
  if ($row['rating_change'] == 0) {
   $base = 0;
  } elseif ($row['rating_change'] > 0) {
   $base = 1;
  } else {
   $base = -1;
  }
  $base *= ($users[($row['user_id'])]+10)/10;
  $user += $base; 
 }

 $sql = 'SELECT COUNT(*) FROM users';
 $res = mysql_query($sql);
 $row = mysql_fetch_array($res);
 $cusers = $row[0];

 foreach ($qr as $quote => $rankings) {
  $for = count($rankings['for']);
  $against = count($rankings['against']);
  $total = $for + $against;
  if ($for == 0) {
   foreach ($rankings['against'] as $uid) {
    $nusers[$uid] += 0.5;
   }
  } elseif ($against == 0) {
   foreach ($rankings['for'] as $uid) {
    $nusers[$uid] += 0.5;
   }
  } else {
   $forscore = $for/$total - 0.5;
   $againstscore = $against/$total - 0.5;
   foreach ($rankings['for'] as $uid) {
    $nusers[$uid] += $forscore;
   }
   foreach ($rankings['against'] as $uid) {
    $nusers[$uid] -= $againstscore;
   }
  }
 }


 #echo '<hr>';
 foreach ($nusers as $uid => $user) {
  #echo $uid.' ==&gt; '.(10*$user*(1+$quotes[$uid])/(pow($max,2)*$cusers*2)).'<br>';
  $nusers[$uid] = (10*$user*(1+$quotes[$uid])/(pow($max,2)*$cusers*2));
  $sql = 'UPDATE users SET user_standing = '.$nusers[$uid].' WHERE user_id = '.$uid;
  mysql_query($sql);
 }

 #echo '<hr>';

 $quotes = array();
 
 $sql = 'SELECT quote_id, user_id, rating_change FROM ratings';

 $res = mysql_query($sql);
 while ($row = mysql_fetch_array($res)) {
  if (isset($nusers[($row['user_id'])])) {
   $user = $nusers[($row['user_id'])];
  } else {
   $user = 0;
  }
  if ($row['rating_change'] == 0) {
   $base = 0;
  } elseif ($row['rating_change'] > 0) {
   $base = 1;
  } else {
   $base = -1;
  }
  $base *= ($user+10)/10;
  $quotes[($row['quote_id'])] += $base;
 }

 foreach ($quotes as $qid => $score) {
  $sql = 'UPDATE quotes SET quote_rating = '.$score.' WHERE quote_id = '.$qid;
  mysql_query($sql);
  #echo "$qid ==&gt; $score<br>";
 }

?>
