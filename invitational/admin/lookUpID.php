<?php
require_once('../../includes/util.php');
//require_once('../includes/load_config.php');
//require_once('../includes/quick_con.php');
load_config('../config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) include $_SERVER['DOCUMENT_ROOT'].$config['folder']."includes/loginUpdate.php";

if(!array_key_exists("inv_isAdmin", $_SESSION) || $_SESSION['inv_isAdmin']==0){
	?>
<html>
<head><?php placeTabIcon(); ?><?php include_once '../includes/htmlHeader.php';?></head>
<body><?php include_once '../includes/header.php';?><div class='content'>
You're not an admin, get out of here!<br><a href="..">Return to home</a></div></body></html>
<?php
}else{
$msg = "";
if(array_key_exists("name", $_GET)){do{
	$name = preg_replace("/[^A-Za-z0-9-]/", "", $_GET['name']);
	$ret = mysql_oneline("SELECT CONCAT(`fname`,' ',`lname`) name, `UID`, team, state FROM `users` WHERE UPPER(CONCAT(`fname`,' ',`lname`)) LIKE UPPER('%$name%');");
	$name = $ret['name'];
	$uid = $ret['UID'];
	
	$team = $ret['team'];
	$teamInfo = mysql_oneline("SELECT * FROM `teams` WHERE `code` = '$team'");
	$teamName = $teamInfo['name'];
	$state = ($ret['state']==0?"not signed in":"signed in");
	$msg = $name." has UID ".$uid." and is ".$state.".<br>Clearance level: ".$teamName;
}while(false);}

?>
<html>
<head><?php placeTabIcon(); ?><?php include_once '../includes/htmlHeader.php';?></head>
<body><?php include_once '../includes/header.php';
echo "<div class='content'>";
if(isset($msg)) echo $msg;?>
<h2>Find Player ID</h2>
<form action="" method="get">
	Enter part or all of the player's name:<br>
	<input type="text" name="name">
	<input type="submit" value="Submit">
</form></div>
<?php include_once '../includes/footer.php';?></body>
</html><?php }?>