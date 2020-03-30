<?php
//require_once('../includes/load_config.php');
//require_once('../includes/quick_con.php');
require_once('../../includes/util.php');
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
if(array_key_exists("id", $_GET)){do{
	$id = preg_replace("/[^A-Za-z0-9-]/", "", $_GET['id']);
	mysql_query("UPDATE `users` SET `state`=`state`*-1 WHERE `UID`='$id';");
	$ret = mysql_oneline("SELECT `fname`, `lname`, IF(`state`=-1,'zombie','human') AS state FROM `users` WHERE `UID`='$id';");
	$name = $ret['fname']." ".$ret['lname'];
	$state = $ret['state'];
	$msg = $name." is now a ".$state.".<br>";
}while(false);}

$ret = mysql_oneline("SELECT COUNT(*) AS cnt FROM `users` WHERE `state`=1");
$humans = $ret['cnt'];
$ret = mysql_oneline("SELECT COUNT(*) AS cnt FROM `users` WHERE `state`=-1");
$zombies = $ret['cnt'];

?>
<html>
<head><?php placeTabIcon(); ?><?php include_once '../includes/htmlHeader.php';?></head>
<body><?php include_once '../includes/header.php';
echo "<div class='content'>";
if(isset($msg)) echo $msg;?>
<h2>Change Player State</h2>
There are currently <?php echo "$humans humans and $zombies zombies."?><br>
<!-- <button onclick="window.location='<?php
if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') || strpos($_SERVER, 'iPhone'))
	echo 'zxing://scan/?ret='.urlencode($_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?").'id={CODE}';
else
	echo 'http://zxing.appspot.com/scan?ret='.urlencode($_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?").'id={CODE}';
?>'" style="font-size: 500%">Scan</button>-->
<form action="" method="get">
	<input type="text" name="id">
	<input type="submit" value="Submit">
</form>
<?php include_once '../includes/footer.php';?></div></body>
</html><?php }?>