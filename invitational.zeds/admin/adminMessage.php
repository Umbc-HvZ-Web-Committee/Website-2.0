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
if(array_key_exists("message", $_POST)){do{
	$msg = $_POST['message'];
	$numbers = array();
	
	$ret=mysql_query("SELECT `phoneNumber` FROM `users` "
	."WHERE `phoneNumber`!='' "
	."GROUP BY `phoneNumber`;");
	//The 'GROUP BY' in the above SQL prevents the OZ from getting duplicate messages directed both at him and his dummy.
	//This also joins with the list table to make sure the mass SMS list is permitted for this user.
	
	$data = mysql_fetch_array($ret);
	while($data != false){
		$numbers[]=$data['phoneNumber'];
		$data = mysql_fetch_array($ret);
	}
	
	require '../includes/messaging.php';
	sendMessage($numbers, $msg);
}while(false);}
?>
<html>
<head><?php placeTabIcon(); ?><?php include_once '../includes/htmlHeader.php';?></head>
<body><?php include_once '../includes/header.php';?>
<form action="" method="post">
	Message to send (please keep it short):<br>
	<textarea rows="10" cols="20" name="message"></textarea><br>
	<input type="submit" value="Submit">
</form>
<?php include_once '../includes/footer.php';?></body>
</html><?php }?>