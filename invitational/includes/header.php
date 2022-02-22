<?php
require_once(dirname(__FILE__)."/color.php");
?>
<div style="height: 250px;">
<span style="float: left;">
<a href="<?php echo $config['folder'];?>"><img src="<?php echo $config['folder'];?>images/Scooby Doo Transp.png" style="border-style: solid; border-radius:25px; border-width: 3px; border-color: #474747; width: 200px; height: 167px; margin-top: 75px;"></img></a>
</span>
<?php
echo '<span style="float: right; margin-top: 20px;">';
echo '<div style="text-align: right; height: 52px;">';
//Login script.
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) include $_SERVER['DOCUMENT_ROOT'].$config['folder']."includes/loginUpdate.php";

if(array_key_exists("inv_uid", $_SESSION)){
	$uid = $_SESSION['inv_uid'];
	$ret = mysql_fetch_assoc(mysql_query("SELECT fname displayName, `uname`, team FROM `users` WHERE `UID`='$uid';"));
	$name = $ret['displayName'];
	$style = colorNameToCSS($ret['team']);
	echo "<p style=\"color:black;\">Hello <a class='profile' href=\"".$config['folder']."myProfile.php\">".$name."!</a> ";
	if($_SESSION['inv_isAdmin']==1) echo "<a class='profile' href=\"".$config['folder']."admin\">Admin Panel</a> ";
	echo"<a class='profile' href=\"".$config['folder']."logout.php?loc=".$_SERVER["REQUEST_URI"]."\">Logout</a></p>";
}else{
	include_once $_SERVER['DOCUMENT_ROOT'].$config['folder'].'/includes/saltGen.php';
	$salt = $_SESSION['inv_salt'];?>
<form method="post" action="" name="loginForm" id="loginForm" onsubmit="submitLogin();" style="width:570px; height:24px; border:solid black 3px; background-color:#474747; float:right; margin-top:28px;">
	<?php if($GLOBALS['loginNotification']!="") echo $GLOBALS['loginNotification'];?>
	<input type="hidden" id="salt" name="salt" value="<?php echo $salt;?>">
	<input type="text" style="width:130px;" id="loginUsername" name="username" value="Username" onfocus="if(value=='Username') value=''; " onblur="if(value=='') value='Username'; ">
	<input type="password" style="width:130px;" id="loginPasswordTxt" value="password" onfocus="if(value=='password') value=''; " onblur="if(value=='') value='password'; ">
	<input type="hidden" id="loginPassword" name="password">
	<input type="submit" name="login" value="Login">
	<button type="button" onclick="register();">Register</button>
</form>
	<?php
}
?>
</div>
<div style="background-color: #474747; color: #000000; text-align: center; width: 580px; vertical-align: middle; height: 127px; border: solid black 3px; font-weight: 700; margin-top: 20px;">
<br/>UMBC Spring 2022 Invitational<br/>
<br/><br/>
<div style="height: 27px;">
<a href="<?php echo $config['folder'];?>index.php" style="margin-right: 50px;" class="redLink">Home</a>
<a href="<?php echo $config['folder'];?>rules.php" style="margin-right: 50px;" class="purpleLink">Info</a>
<a href="<?php echo $config['folder'];?>directory.php" style="margin-right: 50px;" class="blueLink">Attendees</a>
<a href="<?php echo $config['folder'];?>contact.php" class="greenLink">Contact Us</a>
</div>
<div style="height: 15px;"></div>
</div>
<?php 
echo '</span></div>';
?>
