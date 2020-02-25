<?php
require_once(dirname(__FILE__)."/color.php");
?>
<div style="height: 200px;">
<span style="float: left;">
<a href="<?php echo $config['folder'];?>"><img src="<?php echo $config['folder'];?>images/logo.png" style="width: 200px; height: 200px;"></img></a>
</span>
<?php
echo '<span style="float: right; margin-top: 20px;">';
echo '<div style="text-align: right; height: 52px;">';
//Login script.
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) include $_SERVER['DOCUMENT_ROOT'].$config['folder']."includes/loginUpdate.php";

if(array_key_exists("inv_uid", $_SESSION)){
	$uid = $_SESSION['inv_uid'];
	$ret = mysql_fetch_assoc(mysql_query("SELECT displayName, `uname`, clearanceLevel FROM `users` WHERE `UID`='$uid';"));
	$name = $ret['displayName'];
	$style = colorNameToCSS($ret['clearanceLevel']);
	echo "Hello <a href=\"".$config['folder']."myProfile.php\"><div class=\"clearanceBadge\" style=\"".$style."\"></div>".$name."!</a> ";
	if($_SESSION['inv_isAdmin']==1) echo "<a href=\"".$config['folder']."admin\">Admin Panel</a> ";
	echo"<a href=\"".$config['folder']."logout.php?loc=".$_SERVER["REQUEST_URI"]."\">Logout</a>";
}else{
	include_once $_SERVER['DOCUMENT_ROOT'].$config['folder'].'/includes/saltGen.php';
	$salt = $_SESSION['inv_salt'];?>
<form method="post" action="" name="loginForm" id="loginForm" onsubmit="submitLogin();">
	<?php if($GLOBALS['loginNotification']!="") echo $GLOBALS['loginNotification'];?>
	<input type="hidden" id="salt" name="salt" value="<?php echo $salt;?>">
	<input type="text" id="loginUsername" name="username" value="Username" onfocus="if(value=='Username') value=''; " onblur="if(value=='') value='Username'; ">
	<input type="password" id="loginPasswordTxt" value="password" onfocus="if(value=='password') value=''; " onblur="if(value=='') value='password'; ">
	<input type="hidden" id="loginPassword" name="password">
	<input type="submit" name="login" value="Login"><br>
	<button type="button" onclick="register();">Register</button>
</form>
	<?php
}
?>
</div>
<div style="text-align: center; width: 550px; height: 114px; border: solid black 1px; font-weight: 700;">
<div style="height: 15px;"></div>
<div style="height: 27px;">
Z.E.D.S. - Never heard of us? Good.
</div>
<div style="height: 30px;"></div>
<div style="height: 27px;">
<a href="<?php echo $config['folder'];?>" style="margin-right: 50px;">Home</a>
<a href="<?php echo $config['folder'];?>directory.php" style="margin-right: 50px;">Company Directory</a>
<a href="<?php echo $config['folder'];?>contact.php">Contact Us</a>
</div>
<div style="height: 15px;"></div>
</div>
<?php 
echo '</span></div>';
?>