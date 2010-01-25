<?PHP

 require_once('inc/database.php');
 require_once('inc/account.php');
 require_once('inc/settings.php');



 if (isset($_POST['openid_url']) || isset($_REQUEST['openid_mode'])) {
  // OpenID login in progress

  require_once('openid/processor.php');
 } else if (isset($_SESSION['openid']) && $_SESSION['openid']['validated']) {
  // OpenID login succeeded

  $sql  = 'SELECT user_id, user_name, user_standing FROM users WHERE user_name = ';
  $sql .= '\'' . m($_SESSION['openid']['identity']) . '\'';
  $res  = mysql_query($sql);

  if (mysql_num_rows($res) == 0) {
   $sql  = 'INSERT INTO users (user_name, user_pass) VALUES (\'' . m($_SESSION['openid']['identity']);
   $sql .= '\', \'openid user\')';
   $res  = mysql_query($sql);

   $_SESSION['uid'] = mysql_insert_id();
   $_SESSION['uname'] = $_SESSION['openid']['identity'];
   $_SESSION['standing'] = 0;
  } else {
   $row = mysql_fetch_assoc($res);
 
   $_SESSION['uid'] = $row['user_id'];
   $_SESSION['uname'] = $row['user_name'];
   $_SESSION['standing'] = $row['user_standing'];
  }

  unset($_SESSION['openid']);
  if (isset($_POST['remember'])) {
   $row = mysql_fetch_array(mysql_query('SELECT user_hash FROM users WHERE user_id = '.$_SESSION['uid']));
   if (strlen($row[0]) != 32) {
    $row[0] = md5(uniqid($_SESSION['uid']).time());
    mysql_query('UPDATE users SET user_hash = \''.$row[0].'\' WHERE user_id = '.$_SESSION['uid']);
   }
   setcookie('quotedbperm', $row[0], time()+60*24*24*365.24);
  }
  header('Location: '.BASE);
  exit;
 } else if (isset($_SESSION['openid']['error'])) {
  // OpenID login failed

  define('MESSAGE', $_SESSION['openid']['error']);
  unset($_SESSION['openid']['error']);
 } else if (isset($_POST['user']) && isset($_POST['pass'])) {
  // Normal login

  $sql  = 'SELECT user_id, user_name, user_standing FROM users ';
  $sql .= 'WHERE user_name = \''.m($_POST['user']).'\' AND user_pass = \''.m(md5($_POST['user'].$_POST['pass'])).'\'';
  $res = mysql_query($sql);
  if (mysql_num_rows($res) == 0) {
   define('MESSAGE', 'Login failed. Please check your username and password.');
  } else {
   $row = mysql_fetch_array($res);
   $_SESSION['uid'] = $row['user_id'];
   $_SESSION['uname'] = $row['user_name'];
   $_SESSION['standing'] = $row['user_standing'];
   if (isset($_POST['remember'])) {
    $row = mysql_fetch_array(mysql_query('SELECT user_hash FROM users WHERE user_id = '.$_SESSION['uid']));
    if (strlen($row[0]) != 32) {
     $row[0] = md5(uniqid($row['user_id']).time());
     mysql_query('UPDATE users SET user_hash = \''.$row[0].'\' WHERE user_id = '.$_SESSION['uid']);
    }
    setcookie('quotedbperm', $row[0], time()+60*24*24*365.24);
   }
   header('Location: '.BASE);
   exit;
  }
 }

 define('TITLE', 'Login');

 require_once('inc/header.php');

?>
 <div class="oneThird right">
  <h2>Why login?</h2>
  <p>
   Because of the public nature of this quotes database (there are no
   moderators or admins, just users), and the way we reward good users
   and punish bad ones (standings), we require that you be logged in in
   order to rate a quote or add a new one.
  </p>
  <p>
   You can still browse quotes without being logged in, but to contribute
   to the site at all you'll have to login.
  </p>
 </div>
 <div>
  <h2>Login</h2>
<?PHP

 if (defined('MESSAGE')) {
  echo '<div id="message">'.MESSAGE.'</div>';
 }


?>
  <p>
   If you don't have an account, <a href="<?PHP echo BASE; ?>register">
   register one</a> in a few seconds.
  </p>
  <form action="<?PHP echo BASE; ?>login" method="post">
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
     <th>Remember?</th>
     <td><input type="checkbox" name="remember" style="width: 20px;"> (Requires cookies)</td>
    </tr>
   </table>
   <input type="submit" value="Login">
  </form>
  <p>Alternatively, you can log in using OpenID:</p>
  <form action="<?PHP echo BASE; ?>login" method="post">
   <table class="form">
    <tr><th>Identifier</th>
        <td>
    <input type="text" name="openid_url" id="openid_url" style="background: url('openid/openid.gif') no-repeat; padding-left: 20px;">
    </td></tr>
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
