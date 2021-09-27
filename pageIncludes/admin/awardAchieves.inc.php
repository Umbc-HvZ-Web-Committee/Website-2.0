<?php
require_once('../includes/util.php');
require_once('../includes/achievementUpdateFunctions.php');
load_config('../config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('../includes/loginUpdate.php');
$settings = get_settings();

if(isset($_REQUEST['submit'])){
	
	$func = $_REQUEST['submit'];
	$value = $_REQUEST['achieve'];
	$playerID = requestVar('uname');
	$ret = getUID($playerID);
	if(!$ret) {
		$status = "</br><h3>Player not found.</h3>";
	} else {
			
		$uid = $ret['UID'];
		$email = $ret['email'];
		$name = $ret['fname']." ".$ret['lname'];
		
		$achieveLookupSql = "SELECT * FROM `achievements_new` WHERE `key`='$value'";
		$achieveLookupRet = mysql_oneline($achieveLookupSql);
		$achieveAID = $achieveLookupRet['AID'];
		$achieveName = $achieveLookupRet['name'];
		
		if(!giveAchieve($achieveAID, $uid))
		{
			$status = "</br><h3>Player already has that achievement.</h3>";
		} else {
			$status = "</br><h3>".$name." has been awarded the achievement ".$achieveName.".</h3>";
		}
		
		
		$sql = "SELECT * FROM `userAchieveLink_new` WHERE UID='$uid'";
		$retTwo = mysql_query($sql);
		while($row = mysql_fetch_assoc($retTwo)) {
			if($achieveAID == $row['AID']) {
				$found = true;
				break;
			}
		}
		
		
		/*if($found) {//Displays incorrect case
			$status = "</br><h3>Player already has that achievement.</h3>";
		} else {		
			mysql_query("INSERT INTO `userAchieveLink_new`(`AID`, `UID`) VALUES ('$achieveAID','$uid');");
			$status = "</br><h3>".$name." has been awarded the achievement ".$achieveName.".</h3>";
			
			$subject = "Achievement \"$achieveName\" Awarded";
			$msg = <<<EOF
Congratulations!
			
You have been awarded the achievement $achieveName! To set this achievement as your displayed achievement on 'umbchvz.com/playerList', go to 'umbchvz.com/myProfile' and log in. Under the 'Achievements' header, select this achievement from the dropdown menu and click 'Submit'. This achievement should now be displayed in the same row as your name on the Player List.
			
Please note that artwork has not been chosen for all achievements and the image may still say 'Coming Soon'. If this is the case, hover your mouse over the image to verify the proper achievement was selected. The artwork will automatically update as it becomes available.
			
~ Kyle J Mosier - Webmaster ~
THIS IS AN AUTOMATED MESSAGE. 
EOF;
			
			mail($email, $subject, $msg);
		}*/
	}
} else {
	$status = "";
}


function generateList() {
	echo "<optgroup label='--- Basic ---'>";
	$sql = "SELECT * FROM `achievements_new` WHERE class='e';";
	$ret = mysql_query($sql);
	while($row = mysql_fetch_assoc($ret)) {
		$value = $row['key'];
		$name = $row['name'];
		echo "<option value='$value'>$name</option>";
	}
	echo "<option disabled></option></optgroup>";

	echo "<optgroup label='--- Recruit ---'>";
	$sql = "SELECT * FROM `achievements_new` WHERE class='m';";
	$ret = mysql_query($sql);
	while($row = mysql_fetch_assoc($ret)) {
		$value = $row['key'];
		$name = $row['name'];
		echo "<option value='$value'>$name</option>";
	}
	echo "<option disabled></option></optgroup>";
		
	echo "<optgroup label='--- Veteran ---'>";
	$sql = "SELECT * FROM `achievements_new` WHERE class='h';";
	$ret = mysql_query($sql);
	while($row = mysql_fetch_assoc($ret)) {
		$value = $row['key'];
		$name = $row['name'];
		echo "<option value='$value'>$name</option>";
	}
	echo "<option disabled></option></optgroup>";
	
	if($_SESSION['isAdmin'] >= 2) {
		echo "<optgroup label='--- Legendary ---'>";
		$sql = "SELECT * FROM `achievements_new` WHERE class='l';";
		$ret = mysql_query($sql);
		while($row = mysql_fetch_assoc($ret)) {
			$value = $row['key'];
			$name = $row['name'];
			echo "<option value='$value'>$name</option>";
		}
	}
	echo "</optgroup>";
}



?>