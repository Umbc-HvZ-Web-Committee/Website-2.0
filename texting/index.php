<?php
require_once('../includes/load_config.php');
require_once('../includes/util.php');
load_config('../config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) include $_SERVER['DOCUMENT_ROOT'].$config['folder']."includes/loginUpdate.php";
$settings = get_settings();
?>
<html>
<head><?php placeTabIcon(); ?><?php include_once '../includes/htmlHeader.php';?></head>
<body><?php include_once '../includes/header.php';?>
<div>
	HvZ Texting Service Commands:<br>
	<ul>
	<li><b>umbchvz [h|z|a|m] [msg]</b> - Sends a message to all the humans, all the zombies, all the players, or all the moderators.</li>
	<li><b>umbchvz [marco|bump]</b> - Sends back a response of 'Polo!' or 'Bumped.' Useful for either checking if the server is running, or if you got any messages while you were out of coverage.</li>
	<li><b>umbchvz [info|stats]</b> - Sends back to you the current human-zombie counts, along with time until death (if applicable).</li>
	<li><b>umbchvz id</b> - Sends you your ID codes. DO NOT DEPEND ON THIS, ALWAYS KEEP YOUR CODE WRITTEN DOWN. This is for emergencies and illegible handwriting only.</li>
	<li><b>umbchvz kill [MK_____|FK_____|self]</b> - <font color="red" size="24">EXPERIMENTAL</font> - Kills the indicated person.  'self' kills yourself, and has the same rules applied to it as the iDied button.  
	If you use this, please contact an admin letting them know you did so we can confirm this is working correctly!!</li>
	</ul>
</div>
<?php
if(array_key_exists("uid", $_SESSION)){
	$userData = mysql_oneline("SELECT `uname` FROM `users` WHERE `UID`='{$_SESSION['uid']}';");
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
