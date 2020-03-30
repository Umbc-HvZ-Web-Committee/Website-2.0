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

if(array_key_exists("id", $_GET)){do{
	$id = preg_replace("/[^A-Za-z0-9-]/", "", $_GET['id']);
	mysql_query("UPDATE `users` SET `state`=IF(`state`=0,1,0) WHERE `UID`='$id';");
	$ret = mysql_oneline("SELECT `fname`, `lname`, name, clearanceLevel, IF(`state`=0,'signed out','signed in') AS state FROM `users` NATURAL JOIN schools WHERE `UID`='$id';");
	$name = $ret['fname']." ".$ret['lname'];
	$state = $ret['state'];
	$msg = $name." is now ".$state.".<br>School: ".$ret['name']."<br>Clearance level: ".$ret['clearanceLevel'];
}while(false);}

$ret = mysql_oneline("SELECT COUNT(*) AS cnt FROM `users` WHERE `state`=1");
$humans = $ret['cnt'];
$ret = mysql_oneline("SELECT COUNT(*) AS cnt FROM `users` WHERE `state`=-1");
$zombies = $ret['cnt'];

?>
<html>
<head><?php placeTabIcon(); ?><?php include_once '../includes/htmlHeader.php';?></head>
<body><?php include_once '../includes/header.php';
echo $msg;
?>
<h2>Sign In Players</h2>
There are currently <?php echo "$humans humans and $zombies zombies."?><br>
<form action="" method="get">
	<input type="text" name="id" value="PUIV">
	<input type="submit" value="Submit">
</form>
<?php include_once '../includes/footer.php';?></body>
</html><?php }?>