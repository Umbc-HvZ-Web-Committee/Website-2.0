<?php
$GLOBALS['loginNotification'] = "";

if(array_key_exists("salt", $_POST)){
	//just logged in
	$alphaNumRegex = "/[^A-Za-z0-9_ -]/";
	$hashRegex = "/[^a-f0-9]/";

	$username = preg_replace($alphaNumRegex,"",$_POST['username']);
	$userPassHashed = preg_replace($hashRegex,"",$_POST['password']);

	$ret = mysql_fetch_assoc(mysql_query("SELECT `passwd`, `UID`, `isAdmin` FROM `users` WHERE `uname`='$username'"));
	$serverPass = $ret['passwd'];
	$serverPassHashed = hash("sha256", $_SESSION['salt'].$serverPass);

	if($userPassHashed == $serverPassHashed){
		//WEWT you logged in right!
		$_SESSION['uid'] = $ret['UID'];
		$_SESSION['isAdmin'] = $ret['isAdmin'];
		if(array_key_exists("disableImages", $_POST) && $_POST['disableImages']==1) $_SESSION['showPics']="no";
		else $_SESSION['showPics']="yes";
	}else{
		//you messed up the login info
		$GLOBALS['loginNotification'] .= "Invalid login attempt.";
	}
}

if(array_key_exists("logout", $_POST)){
	session_unset();
}elseif(array_key_exists("uid", $_SESSION)){
	//verify that you can be logged in - i.e. you have a valid UID still
	$ret = mysql_oneline("SELECT COUNT(*) AS cnt, `isAdmin` FROM `users` WHERE `UID`='{$_SESSION['uid']}';");
	if($ret['cnt']!=1){
		session_destroy();
		echo "I don't know what happened, but you were logged in as someone, but I can't find them now. Did you get banned or something? Shame on you!";
		die();
	}
	$_SESSION['isAdmin']==$ret['isAdmin'];
}
$loginUpdate=1;
?>