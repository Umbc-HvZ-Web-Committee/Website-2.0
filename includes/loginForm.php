<?php
global $config;
//Login script.
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) include $_SERVER['DOCUMENT_ROOT'].$config['folder']."/includes/loginUpdate.php";

if(isLoggedIn()){
	$uid = $_SESSION['uid'];
	
	$playerData = mysql_oneline("SELECT * FROM users WHERE UID='$uid'");
	$curLongGame = getCurrentLongGame();
	if($playerData && $curLongGame){
		$longPlayerData = mysql_oneline("SELECT * FROM long_players WHERE gameID='{$curLongGame['gameID']}' AND playerID='$uid'");
	}else{
		$longPlayerData = false;
	}
	
	$ret = mysql_fetch_assoc(mysql_query("SELECT `fname`, `uname`, `isAdmin` FROM `users` WHERE `UID`='$uid';"));
	$fname = $ret['fname'];
	?>
	<h2>Hello <?php echo $fname;?>!</h2>
	<a href="/myProfile.php">My Profile</a><br/><br/>
	<?php
	if($longPlayerData){
	?>
	<a href="/kill.php">Log a kill</a><br/><br/>
	<?php 
	/*
	 * <?php
	$ptsQuery = mysql_oneline("SELECT SUM(pointsGiven) points FROM long_points WHERE gameID='{$curLongGame['gameID']}' AND playerID='$uid' GROUP BY playerID");
	$points = $ptsQuery['points'];
	if($points=="") $points = "0";
	?>
	You currently have <?php echo $points; ?> points.<br/>
	 */
	?>
	<?php } ?>
	
	<?php if($_SESSION['isAdmin'] >= 1 || $playerData['isLongGameAuthed'] >= 1){ ?>
		<a href="/admin/">Admin Panel</a><br/><br/>
	<?php } ?>
	
	<form method="post" action="" name="logoutForm">
		<input type="submit" name="logout" value="Log out" />
	</form><br/>
	<?php
}else{
	include_once $_SERVER['DOCUMENT_ROOT'].'/includes/saltGen.php';
	$salt = $_SESSION['salt'];
	if($GLOBALS['loginNotification']!="") echo $GLOBALS['loginNotification']?>
		<h2>Login</h2>
		<form method="post" action="" name="loginForm" id="loginForm">
			<input type="hidden" id="salt" name="salt" value="<?php echo $salt;?>" />
			<input type="hidden" id="loginPassword" name="password" />
			<table><tr><td>
			HvZ Handle: <br />
			<input type="text" id="loginUsername" name="username" value="Username" onclick="if(value=='Username') value=''; " onblur="if(value=='') value='Username'; " />
			</td><td>
			Password: <br />
			<input type="password" id="loginPasswordTxt" value="password" onclick="if(value=='password') value=''; " onblur="if(value=='') value='password'; " />
			</td></tr></table><br/>
			<input type="submit" name="login" value="Login" onclick="submitLogin()" /> 
			<button type="button" onclick="window.location = '/register.php'">Register</button><br/>
			<br/><a href="/passwordRecovery.php">Forgot username/password?</a><br/>
			<br/>If your login information doesn't work, try entering it a second time. Our website sometimes needs to see things twice to properly understand it. We apologize for any inconvenience.<br/>
		</form>
	<?php
}
?>
<!-- This is hooked into the JS code in the header, and gets its value set there. -->
<div id="timeTxt" style="text-align: center;"></div>