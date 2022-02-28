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
	mysql_query("UPDATE `users` SET `state`=IF(`state`=0,1,0) WHERE `UID`='$id';");
	$ret = mysql_oneline("SELECT `fname`, `lname`, name, team, IF(`state`=0,'signed out','signed in') AS state FROM `users` NATURAL JOIN schools WHERE `UID`='$id';");
	$name = $ret['fname']." ".$ret['lname'];
	$state = $ret['state'];
	$team = $ret['team'];
	$teamInfo = mysql_oneline("SELECT * FROM `teams` WHERE `code` = '$team'");
	$teamName = $teamInfo['name'];
	$msg = $name." is now ".$state.".<br>School: ".$ret['name']."<br>Team: ".$teamName;
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
echo $msg;
?>
<h2>Sign In Players</h2>
There are currently <?php echo "$humans humans and $zombies zombies."?><br>
<form action="" method="get">
	<input type="text" name="id" value="PUIV">
	<input type="submit" value="Submit">
</form></div>

<?php
	echo "<br><br>If their name is not in bold, then they have filled out a waiver";

	$html = $html."<table border=1>";
		
	$html = $html."<tr>";
	$html = $html."<td>Name</td>";
	$html = $html."<td>Email Address</td>";
	$html = $html."<td>Username</td>";
	$html = $html."<td>Player ID (use to sign-in)</td>";
	$html = $html."<tr>";
		
	$sql = "SELECT `fname`, `lname`, `email`, `uname`, `UID`, `hasTurnedInWaiver` FROM `users` WHERE 1;";
	$ids = mysql_query($sql);
	while($row = mysql_fetch_assoc($ids)) {
		if($row['hasTurnedInWaiver'] == '1') {
			$fname = $row['fname'];
			$lname = $row['lname'];
			$email = $row['email'];
			$uname = $row['uname'];
			$playerID = $row['UID'];
			
			//$html = $html."<br/>$email $fname $lname $uname";
			
			$html = $html."<td>$fname $lname</td>";
			$html = $html."<td>$email</td>";
			$html = $html."<td>$uname</td>";
			$html = $html."<td>$playerID</td>";
			$html = $html."<tr>";
		} else {
			$fname = $row['fname'];
			$lname = $row['lname'];
			$email = $row['email'];
			$uname = $row['uname'];
			$playerID = $row['UID'];
			
			//$html = $html."<br/>$email $fname $lname $uname";
			
			$html = $html."<strong><td>$fname $lname</td>";
			$html = $html."<td>$email</td>";
			$html = $html."<td>$uname</td>";
			$html = $html."<td>$playerID</td></strong>";
			$html = $html."<tr>";
		}
		
	}
	$html = $html."</table><br/><br/>";
	echo $html;
?>


<?php include_once '../includes/footer.php';?></div></body>
</html><?php }?>