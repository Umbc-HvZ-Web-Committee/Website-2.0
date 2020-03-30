<?php
require_once('includes/util.php');
load_config('config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('includes/loginUpdate.php');
$settings = get_settings();

$result = "";

function resetPassword($email, $uname) {
	global $killChars;
	$reset = generateRandomID("users", "pwResetCode", "PW", $killChars);
	mysql_query("UPDATE users SET pwResetCode='$reset', pwResetTime=CURRENT_TIMESTAMP WHERE uname='$uname'");
	mail($email, "Password reset for UMBC HvZ", "Hello $uname,\n\n\tThe secret code to reset your account is $reset. You can click here to reset your password:\n\n\t https://umbchvz.com/passwordRecovery.php\n\nHave fun!\n~Hiccup");
}

if(array_key_exists("reset", $_REQUEST)){
	$reset = requestVar("reset");
	if($reset=="Reset password"){
		$uname = requestVar("username");
		$ret = mysql_oneline("SELECT email FROM users WHERE uname='$uname'");
		if($ret){
			$email = $ret['email'];
			resetPassword($email, $uname);
			$result = "A password reset email has been sent to $email.";
		}else{
			$result = "Sorry, I couldn't find that username in the database.  Use the other option if you don't know your username.";
		}
	}else if($reset=="Recover account"){
		$email = requestVar("email");
		$ret = mysql_oneline("SELECT email, uname FROM users WHERE email='$email'");
		if($ret){
			$email = $ret['email'];
			$uname = $ret['uname'];
			resetPassword($email, $uname);
			$result = "A password reset email has been sent to $email.";
		}else{
			$result = "Sorry, I couldn't find that email address in the database.  Please contact an admin if you can't remember your email address.";
		}
	}
}else if(array_key_exists("password", $_POST)){
	$code = requestVar("code");
	$password = hash("sha256", $_REQUEST["password"]);
	$ret = mysql_oneline("SELECT uname, TIMESTAMPDIFF(MINUTE,pwResetTime,NOW()) timediff FROM users WHERE pwResetCode='$code'");
	if($ret){
		$uname = $ret['uname'];
		$timediff = $ret['timediff'];
		if($timediff>15){
			$result = "Sorry, it's been over 15 minutes since that code was issued. Please request a new one.";
		}else{
			mysql_query("UPDATE users SET passwd='$password', pwResetCode=NULL, pwResetTime=NULL WHERE uname='$uname'");
			$result = "Password updated for $uname - you should be able to log in now.";
		}
	}else{
		$result = "Sorry, I couldn't find that password reset code.";
	}
}
?>