<?php
ob_start();
require_once('includes/load_config.php');
require_once('includes/quick_con.php');
load_config('config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) include $_SERVER['DOCUMENT_ROOT'].$config['folder']."includes/loginUpdate.php";

$over18WaiverURL = "https://docs.google.com/document/d/1Ml6HEazua093wK9PcPeLBzOEDNlzqw01c08ZlLC3xdI/edit";
$under18WaiverURL = "https://docs.google.com/document/d/1oMNtvrKQW_trk79_YMiRdMZwWOKUnyh3TC97DLDu48A/edit";
?>
<html>
<head><?php placeTabIcon(); ?><?php include_once 'includes/htmlHeader.php';?>
<script type="text/javascript">
function housingUpdate(){
	var sel = document.getElementById("isHousing");
	var dur = document.getElementById("duration");
	var room = document.getElementById("roommatesSpan");
	if(sel.options[sel.selectedIndex].value=="yes"){
		dur.innerHTML = '<select name="durationSel" id="durationSel" style="width: auto;">\
<option value="short">the night of the 26th, leaving the night of the 27th</option>\
<option value="short2">the night of the 27th, leaving the morning of the 28th</option>\
<option value="long">the nights of the 26th and 27th, leaving the morning of the 28th</option>\
</select>';
		room.innerHTML = 'My requested roommates are:<br>\
<textarea name="roommates" id="rommates"></textarea><br>';
	}else{
		dur.innerHTML = '';
		room.innerHTML = '';
	}
}
</script>
</head>
<body><?php include_once 'includes/header.php';?><div class="content">
<?php
if(array_key_exists("inv_uid", $_SESSION)){
	$uid = $_SESSION['inv_uid'];
	$userData = mysql_oneline("SELECT * FROM `users` WHERE `UID`='$uid';");
	$schoolName = mysql_oneline("SELECT `name` FROM `schools` WHERE `SID`='{$userData['SID']}';");
	$schoolName = $schoolName['name'];
?>
<br><div style="text-align: center;">Hello, <?php echo $userData['fname']," ",$userData['lname'];?>!</div>
<h2>View/Print Waiver Information</h2>
<?php
if($userData['isOver18']){
	echo "You agreed to <a href=\"$over18WaiverURL\">this waiver</a> when you registered.  If you no longer agree, please <a href=\"contact.php\">contact us</a>.";
}else{
	echo "You agreed to print out and have a parent sign <a href=\"$under18WaiverURL\">this waiver</a> when you registered.  If you no longer agree, please <a href=\"contact.php\">contact us</a>.";
}
?>
<h2>Change Phone Number</h2>
<?php
if(array_key_exists("phoneNumber", $_POST)){
	$phoneNumber = preg_replace("/[^0-9]/", "", $_POST['phoneNumber']);
	mysql_query("UPDATE `users` SET `phoneNumber`='$phoneNumber' WHERE `UID`='$uid';");
	$userData = mysql_oneline("SELECT * FROM `users` WHERE `UID`='$uid';");
	echo "Phone number updated.<br>";
}
?>
<form action="" method="post">
Please enter your cell phone number below. We will use your cell phone number to text you updates throughout the event, including food locations and any safety issues that may arise.<br>
<input name="phoneNumber" value="<?php echo $userData['phoneNumber'];?>"><input type="submit" value="Update">
</form>
<h2>Edit Emergency Contact Information</h2>
<?php
if(array_key_exists("emergencyName", $_POST)){
	$name = mysql_real_escape_string($_POST['emergencyName']);
	$relation = mysql_real_escape_string($_POST['emergencyRelation']);
	$phone = mysql_real_escape_string($_POST['emergencyPhone']);
	mysql_query("UPDATE `users` SET `emergencyContactName`='$name', `emergencyContactRelation`='$relation', `emergencyContactPhone`='$phone' WHERE `UID`='$uid';");
	$userData = mysql_oneline("SELECT * FROM `users` WHERE `UID`='$uid';");
	echo "Emergency contact information updated.<br>";
}
?>
<form action="" method="post">
<table>
<tr><td>Name:</td><td><input name="emergencyName" value="<?php echo $userData['emergencyContactName'];?>"></td></tr>
<tr><td>Relation:</td><td><input name="emergencyRelation" value="<?php echo $userData['emergencyContactRelation'];?>"></td></tr>
<tr><td>Phone number:</td><td><input name="emergencyPhone" value="<?php echo $userData['emergencyContactPhone'];?>"></td></tr>
</table>
<input type="submit" value="Update">
</form>
<h2>Edit Housing Information</h2>
<?php if(array_key_exists("isHousing", $_POST)){
	$isHousing = $_POST['isHousing']=="yes";
	if($isHousing){
		$housing = ($_POST['durationSel']=="short"?"short":"long");
		$roommates = mysql_real_escape_string($_POST['roommates']);
	}else{
		$housing = "no";
		$roommates = "";
	}
	mysql_query("UPDATE `users` SET `housing`='$housing', `roommateRequest`='$roommates' WHERE `UID`='$uid';");
	$userData = mysql_oneline("SELECT * FROM `users` WHERE `UID`='$uid';");
	echo "Housing info updated.";
}?>
<form method="post" action="">
I <select name="isHousing" id="isHousing" onchange="housingUpdate();" style="width: auto;">
<option value="yes"<?php if($userData['housing']!="no") echo " selected=\"true\"";?>>will</option>
<option value="no"<?php if($userData['housing']=="no") echo " selected=\"true\"";?>>will not</option>
</select> require housing<?php if($userData['housing']=="no"){?><span id="duration"></span>.<br>
<span id="roommatesSpan"></span>
<?php }else{?><span id="duration"><select name="durationSel" id="durationSel" style="width: auto;">
<option value="short"<?php if($userData['housing']=="short") echo " selected=\"true\"";?>>the night of the 26th, leaving the night of the 27th</option>
<option value="long"<?php if($userData['housing']=="long") echo " selected=\"true\"";?>>the nights of the 26th and 27th, leaving the morning of the 28th</option>
</select></span>.<br>
<span id="roommatesSpan">
My requested roommates are:<br>
<textarea name="roommates" id="rommates"><?php echo $userData['roommateRequest'];?></textarea>
</span><br>
<?php }?>
<input type="submit" value="Update housing information">
</form>
<?php
}else{
	header("Location: .");
}
?>
</div><?php include_once 'includes/footer.php';?></body>
</html>
<?php ob_flush();?>