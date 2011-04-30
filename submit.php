<?PHP

 require_once('inc/account.php');
 require_once('inc/database.php');
 require_once('inc/settings.php');
 require_once('inc/tags.php');

 if (!isset($_SESSION['uid'])) {
  header('Location: '.BASE);
  exit('Must be logged in');
 }

 if (isset($_POST['quote']) && $_SESSION['standing'] > -2) {
  if (get_magic_quotes_gpc()) {
   $_POST['quote'] = stripslashes($_POST['quote']);
  }
  $sql = 'INSERT INTO quotes (quote_quote, quote_time, user_id) VALUES (\''.m($_POST['quote']).'\', '.time().', '.$_SESSION['uid'].')';
  mysql_query($sql);

  doAutoTags(mysql_insert_id());

  header('Location: '.BASE.'latest');
  exit;
 }

 define('TITLE', 'Add quote');

 require_once('inc/header.php');


?>
<div class="oneThird right">
 <h2>Quote guidelines</h2>
 <p>
  The usual quote-site rules apply: don't include anything that's not 
  neccessary, such as timestamps, hostmasks, twenty 'lol's after the funny
  part, etc. Try to avoid injokes if possible.
 </p>
 <p>
  <em>Quotes go live as soon as you submit them</em>. There is no moderation.
  If you submit a rubbish quote, people will rate it down, and your standing
  will fall.
 </p>
 <p>
  Try to stick to standard notation. Enclose nicks in angle brackets
  (&lt;nick&gt; hi!), and prefix actions with an asterisk (* nick waves).
  Remove mode prefixes (@, +, etc) that don't directly add to the humour.
 </p>
</div>
<div>
<?PHP if ($_SESSION['standing'] > -2) { ?>
 <h2>Add a quote</h2>
 <p>Enter your quote in the text area below. Please read the guidelines to
 the right if you haven't done so before.</p>
 <form action="submit" method="post">
  <textarea name="quote" cols="80" rows="10"></textarea>
  <br>
  <input type="submit" value="Add">
 </form>
<?PHP } else { ?>
 <h2>Error</h2>
 <p>You do not have sufficient standing to submit a new quote. Please try
 <a href="unrated">rating some quotes</a> to increase your standings. </p>
<?PHP } ?>
</div>
<?PHP

 require_once('inc/footer.php');

?>
