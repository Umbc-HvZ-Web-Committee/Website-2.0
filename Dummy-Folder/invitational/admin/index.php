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
//real code
?>
<html>
<head><?php placeTabIcon(); ?><?php include_once '../includes/htmlHeader.php';?></head>
<body><?php include_once '../includes/header.php';?><div class="content">
<a href="signInPlayer.php">Sign players into invitational</a><br>
<a href="reportKill.php">Change state of players</a><br>
<a href="lookUpID.php">Look up player ID</a><br>
<a href="teamDisplay.php">Display members of a team</a></div>
<!-- a href="adminMessage.php">Send message to all players</a>  -->
<?php include_once '../includes/footer.php';?></body>
</html><?php }?>