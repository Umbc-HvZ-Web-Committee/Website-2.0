<?php
require_once('../includes/load_config.php');
require_once('../includes/quick_con.php');
load_config('../config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) include $_SERVER['DOCUMENT_ROOT'].$config['folder']."includes/loginUpdate.php";

if(!array_key_exists("inv_isAdmin", $_SESSION) || $_SESSION['inv_isAdmin']==0){
	?>
<html><body>You're not an admin, get out of here!<br><a href=".."><img alt="" src="../images/okay.jpg"></a></body></html>
<?php
}else{
$msg = "";
if(array_key_exists("name", $_GET)){do{
	$name = preg_replace("/[^A-Za-z0-9-]/", "", $_GET['name']);
	$ret = mysql_oneline("SELECT CONCAT(`fname`,' ',`lname`) name, `UID`, clearanceLevel, state FROM `users` WHERE UPPER(CONCAT(`fname`,' ',`lname`)) LIKE UPPER('%$name%');");
	$name = $ret['name'];
	$uid = $ret['UID'];
	$state = ($state==0?"not signed in":"signed in");
	$msg = $name." has UID ".$uid." and is ".$state.".<br>Clearance level: ".$ret['clearanceLevel'];
}while(false);}

?>
<html>
<head><?php placeTabIcon(); ?><?php include_once '../includes/htmlHeader.php';?></head>
<body><?php include_once '../includes/header.php';
if(isset($msg)) echo $msg;?>
<h2>Find Player ID</h2>
<form action="" method="get">
	Enter part or all of the player's name:<br>
	<input type="text" name="name">
	<input type="submit" value="Submit">
</form>
<?php include_once '../includes/footer.php';?></body>
</html><?php }?>