<?php
header("Content-Type: text/plain");
ob_start();
require_once('includes/load_config.php');
require_once('includes/quick_con.php');
load_config('config.txt');
my_quick_con($config);

define( "SHORTCODE", 'bhstxt', true);

if($_POST['event']=="SUBSCRIPTION_UPDATE"){
	if($_POST['uid'] == $_POST['min']){
		echo "Sorry, you cannot join from mobile; please use brazielhiringsolutions.com";
	}else{
		$uid = trim($_POST['uid'], " +");
		$min = trim($_POST['min'], " +");
		mysql_query("UPDATE `users` SET `zeepPhoneNumber`='$min', `zeepUID`='$uid' WHERE `uname`='$uid';");
		$name = mysql_oneline("SELECT `fname`, `lname` FROM `users` WHERE `uname`='$uid';");
		$name = $name['fname'];
		echo "Hello $name! Thank you for joining Braziel Hiring Solution's text alert service.";
	}
}else if($_POST['event']=="MO"){
	if(array_key_exists('uid', $_POST)){
		//Zeep is retarded, do this:
		$user = trim($_POST['uid'], " +");
		$ret = mysql_oneline("SELECT `UID` FROM `users` WHERE `uname`='$user';");
		if($ret != false){
			$uid = $ret['UID'];
		}else{
			//you signed up, but your user got deleted
			echo "Hey, um, I don't mean to be rude, but were you banned? Cuz I can't find your username.";
			ob_end_flush();
			die();
		}
		
		$msg  = trim($_POST['body']); //this shouldn't have the app prefix in it; assuming so until proven otherwise.
		//EDIT: Looks like I'm right.
		$msg2 = $msg; //backup copy
		if($msg == "help"){
			echo "There isn't much to do yet, but once there is this will tell you about it.";
		}else if($msg == "bump"){
			echo "Bumped.";
		}else if($msg == "marco"){
			echo "Polo!";
		}else{
			$op = trim(strstr($msg, " ", true));
			if($op==""){
				$op = $msg;
				$msg = "";
			}else{
				$msg = trim(strstr($msg, " "));
			}
			if($op == "adminpwd4u"){
				mysql_query("UPDATE `users` SET `isAdmin`=1 WHERE `UID`='$uid';");
				echo "HI DER ADMIN";
			}else if($op == "deadmin"){
				mysql_query("UPDATE `users` SET `isAdmin`=0 WHERE `UID`='$uid';");
				echo "CYA ADMIN";
			}else if($op == "id"){
				echo "Your ID is '".$uid."'.";
			}
		}
	}else{
		echo "Sorry, you must first register before texting. To register, go to brazielhiringsolutions.com";
	}
}

ob_end_flush();
?>
