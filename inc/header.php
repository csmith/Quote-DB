<?PHP

require_once('inc/settings.php');
require_once('inc/account.php');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
 <head>
  <title>Quote db<?PHP if (defined('TITLE')) { echo ' : ' .TITLE; } ?></title>
  <link rel="stylesheet" href="res/style.css" type="text/css">
  <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?PHP echo BASE; ?>rss">
  <script type="text/javascript" src="<?PHP echo BASE; ?>res/ajax.js">
  </script>
  <script type="text/javascript">
   function stateChange (id) {
    if (xmlhttp.readyState == 4) {
     document.getElementById('rate'+id).innerHTML = xmlhttp.responseText;
    }
   }

   function doRate (value, id) {
    if (!xmlhttp) { return true; }

    xmlhttp.open("GET", '<?PHP echo BASE; ?>rateajax?quote='+id+'&'+value, true);
    xmlhttp.onreadystatechange = function () { stateChange(id); };
    xmlhttp.send(null);

    document.getElementById('rate'+id).innerHTML = 'Submitting...';

    return false;
   }
  </script>
 </head>
 <body>
  <h1>Quote db</h1>
  <div id="menu">
   <img src="<?PHP echo BASE; ?>res/bl.png" alt="Corner">
   <span id="links">
    <a href="<?PHP echo BASE; ?>">Overview</a> |
    <a href="<?PHP echo BASE; ?>latest">Latest</a> |
    <a href="<?PHP echo BASE; ?>best">Best</a> |
    <a href="<?PHP echo BASE; ?>browse">Browse</a> |
    <a href="<?PHP echo BASE; ?>random">Random</a>
   </span>
   <span id="rlinks">
<?PHP

 if (!isset($_SESSION['uid'])) {

?>
    <a href="<?PHP echo BASE; ?>login">Login</a> |
    <a href="<?PHP echo BASE; ?>register">Register</a>
<?PHP
 } else {
?>
    <!--<a href="<?PHP echo BASE; ?>account">My account</a> |-->
    <!--<a href="<?PHP echo BASE; ?>standing">-->
    <a href="<?PHP echo BASE; ?>login">Logged in as <?PHP echo htmlentities($_SESSION['uname']); ?></a>
    <!--</a>--> |
    <a href="<?PHP echo BASE; ?>unrated">Unrated quotes</a> |
    <a href="<?PHP echo BASE; ?>submit">Add quote</a>
<?PHP
 }
?>    
   </span>
  </div>
  <div id="content">
