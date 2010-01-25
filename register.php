<?PHP

 require_once('inc/database.php');
 require_once('inc/settings.php');
 require_once('inc/account.php');

 function oink() {
  if (isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['pass2'])) {
   if ($_POST['pass2'] != $_POST['pass']) {
    define('MESSAGE', 'Your passwords do not match.');
    return;
   }

   if (strlen($_POST['pass']) < 5) {
    define('MESSAGE', 'Your password must be at least 5 characters.');
    return;
   }

   if (strlen($_POST['user']) < 3) {
    define('MESSAGE', 'Your username must be at least 3 characters.');
    return;
   }

   if (strlen($_POST['user']) > 20) {
    define('MESSAGE', 'Your username must be at most 20 characters.');
    return;
   }

   if (!preg_match('/^[a-zA-Z0-9\-]+$/', $_POST['user'])) {
    define('MESSAGE', 'Your username may only contain letters, numbers and hyphens.');
    return;
   }

   $sql = 'SELECT user_id FROM users WHERE user_name LIKE \''.m($_POST['user']).'\'';
   $res = mysql_query($sql);
   if (mysql_num_rows($res) > 0) {
    define('MESSAGE', 'That username is in use. Please try another.');
    return;
   }

   $sql = 'INSERT INTO users (user_name, user_pass) VALUES (\''.m($_POST['user']).'\', \''.m(md5($_POST['user'].$_POST['pass'])).'\')';
   $res = mysql_query($sql);
   $id = mysql_insert_id();
   
   $_SESSION['uid'] = $id;
   $_SESSION['uname'] = $_POST['user'];
   $_SESSION['standing'] = 0;

   if (isset($_POST['remember'])) {
    $row = mysql_fetch_array(mysql_query('SELECT user_hash FROM users WHERE user_id = '.$_SESSION['uid']));
    if (strlen($row[0]) != 32) {
     $row[0] = md5(uniqid($row['user_id']).time());
     mysql_query('UPDATE users SET user_hash = \''.$row[0].'\' WHERE user_id = '
.$_SESSION['uid']);
    }
    setcookie('quotedbperm', $row[0], time()+60*24*24*365.24);
   }
  
   header('Location: '.BASE);
   exit;
  }
 }

 oink();

 define('TITLE', 'Register');

 require_once('inc/header.php');

?>
 <div class="oneThird right">
  <h2>Why register?</h2>
  <p>
   Because of the public nature of this quotes database (there are no
   moderators or admins, just users), and the way we reward good users
   and punish bad ones (standings), we require that you be logged in in
   order to rate a quote or add a new one.
  </p>
  <p>
   In order to log in to the site, you first need a user account. To obtain
   an account, simply fill out the form to the left. 
  </p>
 </div>
 <div>
  <h2>Register</h2>
<?PHP
 if (defined('MESSAGE')) { echo '<div id="message">'.MESSAGE.'</div>'; }
?>
  <p>
   If you already have an account, you should 
   <a href="<?PHP echo BASE; ?>login">login</a> instead.
  </p>
  <form action="<?PHP echo BASE; ?>register" method="post">
   <table class="form">
    <tr>
     <th>Username</th>
     <td><input type="text" name="user"></td>
    </tr>
    <tr>
     <th>Password</th>
     <td><input type="password" name="pass"></td>
    </tr>
    <tr>
     <th>Confirm password</th>
     <td><input type="password" name="pass2"></td>
    </tr>
    <tr>
     <th>Remember?</th>
     <td><input type="checkbox" name="remember" style="width: 20px;"> (Requires cookies)</td>
    </tr>
   </table>
   <input type="submit" value="Login">
  </form>
 </div>
<?PHP

 require_once('inc/footer.php');

?>
