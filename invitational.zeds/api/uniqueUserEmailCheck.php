<?php
header("Access-Control-Allow-Origin: *");
require_once(dirname(__FILE__).'/../includes/load_config.php');
require_once(dirname(__FILE__).'/../includes/quick_con.php');
load_config(dirname(__FILE__).'/../config.txt');
my_quick_con($config);

$user = mysql_real_escape_string($_GET['user']);
$email = mysql_real_escape_string($_GET['email']);

$fail = false;

$ret = mysql_oneline("SELECT COUNT(*) AS cnt FROM `users` WHERE `uname`='$user'");
if($ret['cnt']!=0){
	echo "That username is already in use.";
	$fail = true;
}

$ret = mysql_oneline("SELECT COUNT(*) AS cnt FROM `users` WHERE `email`='$email'");
if($ret['cnt']!=0){
	if($fail) echo "<br>";
	echo "That email address is already in use.";
	$fail = true;
}

if(!$fail) echo "OK";
?>