<?php
require_once('includes/util.php');
load_config('config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('includes/loginUpdate.php');
$settings = get_settings();

$notification = "";
$alphaRegex = "/[^A-Za-z- ]/";
$alphaNumRegex = "/[^A-Za-z0-9_-]/";
$emailRegex = "/[^A-Za-z0-9@.]/";

$ua = strtolower($_SERVER['HTTP_USER_AGENT']);

/*if(array_key_exists("qr", $_GET)){
	$qrCode = preg_replace($alphaNumRegex,"",$_GET['qr']);
	if((substr($qrCode, 0, 2)!="PU" && substr($qrCode, 0, 6)!="ADMIN_" && substr($qrCode, 0, 5)!="LGCY_")||(strlen($qrCode)!=7)){
		$notification = "That's not a valid QR code. Valid codes start with 'PU', yours started with '".substr($qrCode, 0, 2)."'.<br>";
		$qrCode = "";
	}
}else $qrCode = "";*/

$fname = "";
$lname = "";
$username = "";
$email = "";

if(array_key_exists("submit", $_POST)){
	$fname = preg_replace($alphaRegex,"",$_POST['fname']);
	$lname = preg_replace($alphaRegex,"",$_POST['lname']);
	$username = preg_replace($alphaNumRegex,"",$_POST['username']);
	$password = preg_replace($alphaNumRegex,"",$_POST['password']);
	$email = preg_replace($emailRegex,"",$_POST['email']);
	//$qrCode = preg_replace($alphaNumRegex,"",$_POST['qrCode']);

	$ret = mysql_query("SELECT COUNT(*) FROM `users` WHERE `uname`='$username';");
	$ret = mysql_fetch_assoc($ret);
	if($ret['COUNT(*)']!=0){
		$notification .= "Username already in use, please pick another.<br>";
	}

	$ret = mysql_query("SELECT COUNT(*) FROM `users` WHERE email='$email';");
	$ret = mysql_fetch_assoc($ret);
	if($ret['COUNT(*)']!=0){
		$notification .= "Email address has already been registered; to recover an account please contact an admin.<br>";
	}

// 	$ret = mysql_query("SELECT COUNT(*) FROM `users` WHERE publicQR='$qrCode';");
// 	$ret = mysql_fetch_assoc($ret);
// 	if($ret['COUNT(*)']!=0){
// 		$notification .= "This QR code has already been registered. To recover an account, please contact an admin.<br>";
// 	}
	
	if(!array_key_exists("tosAgree", $_POST) || $_POST['tosAgree']!=1){
		$notification .= "You must accept the TOS to register.<br>";
	}

	if($notification==""){
		//Looks good to me.  Generate UID.
		//YES THE FOLLOWING LINES ARE LONG AND MESSY DEAL WITH IT UNLESS YOU KNOW HOW TO FIX IT.
		$chars = array();
		array_push($chars, "-");
		for($i=0; $i<10; $i++){
			array_push($chars, "$i");
		}
		for($i=0, $c='a'; $i<26; $i++, $c++){
			array_push($chars, "$c");
		}

		$ret = mysql_query("SELECT MAX(`UID`) AS uid FROM `users`;");
		$ret = mysql_fetch_assoc($ret);
		$ouid = $ret['uid'];
		$uid = "";
		if(is_null($ouid)){
			//No users.
			$uid = "US0000-";
		}else{
			//Take the highest UID, add one, then make a new ID from that.
			$id = str_split(substr($ouid, 2));
			$curCharPos = 4;
			while($curCharPos>-1){
				$curChar = $id[$curCharPos];
				if($curChar != $chars[36]){
					$charIndex = array_search($curChar, $chars);
					$charIndex += 1;
					$id[$curCharPos] = $chars[$charIndex];
					break;
				}else{
					$id[$curCharPos] = $chars[0];
					//and continue to the next one
					$curCharPos -= 1;
				}
			}
			$uid = "US".implode($id);
			//This creates over a billion possibilities.  If you have more people than that, I congratulate you, as you have more people playing than live in the USA.
		}

		//Hash the password for security
		$password = hash("sha256", $password);

		include "includes/update.php";
		$sql = "INSERT INTO  `users` (`UID`, `fname`, `lname`, `uname`, `passwd`, `email`, `publicQR`) VALUES ('$uid',  '$fname',  '$lname',  '$username',  '$password', '$email', '');";
		updateAchieves($uid, null, "registered");

		if(!mysql_query($sql)){
			$notification = "Some weird error happened with the query.  Contact an admin, and give them the below:<br>".mysql_error()."<br>".$sql."<br>";
		}else{
			//It's a hit!  All ships jump to lightspeed!
			echo "<html><head><?php placeTabIcon(); ?><meta http-equiv=\"refresh\" content=\"1;url=/\"><script type=\"text/javascript\">alert(\"Registration successful.\");</script></head><body>If you are not redirected, <a href='/'>click here.</a></body></html>";
			die();
		}
	}
}
?>