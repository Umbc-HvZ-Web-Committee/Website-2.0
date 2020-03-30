<?php
//Login script.
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) include $_SERVER['DOCUMENT_ROOT'].$config['folder']."includes/loginUpdate.php";

if(array_key_exists("uid", $_SESSION)){
	$uid = $_SESSION['uid'];
	$ret = mysql_fetch_assoc(mysql_query("SELECT `fname`, `uname` FROM `users` WHERE `UID`='$uid';"));
	$fname = $ret['fname'];
	/*echo "Hello ".$fname."! <a href=\"".$config['folder']."\">Home</a> <a href=\"".$config['folder']."userProfile.php?uname={$ret['uname']}\">My Profile</a> ";
	echo "<a href=\"".$config['folder']."listPlayers.php\">List players</a> ";
	if($_SESSION['isAdmin']==1) echo "<a href=\"".$config['folder']."admin\">Admin Panel</a> ";
	echo "<a href=\"".$config['folder']."logout.php?loc=".$_SERVER["REQUEST_URI"]."\">Logout</a>";*/
	?>
		<h2>Hello <?php echo $fname;?>!</h2>
	<?php
}else{
	include_once $_SERVER['DOCUMENT_ROOT'].'/includes/saltGen.php';
	$salt = $_SESSION['salt'];
	if($GLOBALS['loginNotification']!="") echo $GLOBALS['loginNotification']?>
		<h2>Login</h2>
		<form method="post" action="" name="loginForm" id="loginForm">
			<input type="hidden" id="salt" name="salt" value="<?php echo $salt;?>" />
			<input type="hidden" id="loginPassword" name="password" />
			HvZ Handle:	<br />
			<input type="text" id="loginUsername" name="username" value="Username" onclick="if(value=='Username') value=''; " onblur="if(value=='') value='Username'; " /><br /><br />
			Password:	<br />
			<input type="password" id="loginPasswordTxt" value="password" onclick="if(value=='password') value=''; " onblur="if(value=='') value='password'; " /><br /><br />
			<input type="submit" name="login" value="Login" onclick="submitLogin()" />
		</form>
	<?php
}
?>
<!-- This is hooked into the JS code in the header, and gets its value set there. -->
<div id="timeTxt" style="text-align: center;"></div>
<h2>Problem? <a href="mailto:cbad1@umbc.edu?subject=[HvZPROBLEM] -=description goes here=-">Email the admin</a> or text him at 410-818-7393.</h2>
</div>
