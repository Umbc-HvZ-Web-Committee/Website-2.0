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

if(array_key_exists("team", $_GET)){do{
	$team = preg_replace("/[^A-Za-z0-9-]/", "", $_GET['team']);
	$teamInfo = mysql_oneline("SELECT * FROM `teams` WHERE `code` = '$team'");
	$teamName = $teamInfo['name'];
	$teamLeader = $teamInfo['mod'];
	$sql = "SELECT * FROM `users` WHERE `team`='$team' ORDER BY `SID` ASC";
	$query = mysql_query($sql);
	while($ret = mysql_fetch_assoc($query)){
		$SID = $ret['SID'];
		$retSchool = mysql_oneline("SELECT * FROM `schools` WHERE `SID`=$SID");
		$school = $retSchool['name'];
		
		$msg = $msg.$ret['fname']." ".$ret['lname']." - ".$school."<br/>";
	}
}while(false);}

?>
<html>
<head><?php placeTabIcon(); ?><?php include_once '../includes/htmlHeader.php';?></head>
<body><?php include_once '../includes/header.php';
echo "<div class='content'>";
echo "<h2>Display Team Members</h2>";
echo "<h3>$teamName - Group Mod: $teamLeader</h3>";
if(isset($msg)) echo "<div style='text-align:left; margin-left:230px; text-shadow: 1px 1px #000000, 1px 1px #000000, 1px 1px #000000, 1px 1px #000000;'>$msg</div>";?>
<form action="" method="get">
	<br>Select the team name<br>
	<select name="team">
	<option value="" disabled selected>--Select Team--</option>
	
	<?php 
	$sql = "SELECT * FROM `teams`";
	$query = mysql_query($sql);
	while($ret = mysql_fetch_assoc($query)) {
		$value = $ret['code'];
		$teamName = $ret['name'];
		echo "<option value='$value'>$teamName</option>";
	}
	?>
	</select>
	
	<input type="submit" value="Submit">
	
	<!-- Dynamic generation of options -->
	<?php 
	?>
	
</form>
<?php include_once '../includes/footer.php';?></div></body>
</html><?php }?>