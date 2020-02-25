<?php
require_once('../includes/load_config.php');
require_once('../includes/quick_con.php');
load_config('../config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) include $_SERVER['DOCUMENT_ROOT'].$config['folder']."includes/loginUpdate.php";
?>
<html>
<head><?php placeTabIcon(); ?><?php include_once '../includes/htmlHeader.php';?></head>
<body><?php include_once '../includes/header.php';?>
<div>
	BHS Texting Service Commands:<br>
	<ul>
	<li>Not much yet.</li>
	</ul>
</div>
<?php
if(array_key_exists("inv_uid", $_SESSION)){
	$userData = mysql_oneline("SELECT `uname` FROM `users` WHERE `UID`='{$_SESSION['inv_uid']}';");
?>
<div>
	<iframe 
	  style="width: 100%; height: 190px; border: none;"
	  id="zeep_mobile_settings_panel" 
	  src="https://www.zeepmobile.com/subscription/settings?api_key=8acf9ea2-27d7-4ab5-a4ef-23341a18c70c&user_id=<?php echo $userData['uname'];?>"
	>
	</iframe>
</div>
<?php }?>
<?php include_once '../includes/footer.php';?></body>
</html>
