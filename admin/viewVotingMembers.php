<?php
require_once('../pageIncludes/admin/blogPost.inc.php');
$playerData = mysql_oneline("SELECT * FROM users WHERE UID='{$_SESSION['uid']}'");

// Code for Clear Records
if(isset($_POST['clearYear']))
{
	// Switch to next semester
    mysql_query("UPDATE `users` SET `appearancesLastTerm` = `appearancesThisTerm` WHERE 1;");
    mysql_query("UPDATE `users` SET `appearancesThisTerm` = '0' WHERE 1;");
    mysql_query("UPDATE `users` SET `zombieStartsThisTerm` = '0' WHERE 1;");
    mysql_query("UPDATE `users` SET `humanStartsThisTerm` = '0' WHERE 1;");
    mysql_query("UPDATE `users` SET `gamesModdedThisTerm` = '0' WHERE 1;");
    mysql_query("UPDATE `users` SET `adminMeetingsThisTerm` = '0' WHERE 1;");
	// Reset waiver info because those expire by the year
    mysql_query("UPDATE `users` SET `hasTurnedInWaiver`= '0' WHERE 1;");
}
if(isset($_POST['clearSemester']))
{
	// Switch to next semester
    mysql_query("UPDATE `users` SET `appearancesLastTerm` = `appearancesThisTerm` WHERE 1;");
    mysql_query("UPDATE `users` SET `appearancesThisTerm` = 0 WHERE 1;");
    mysql_query("UPDATE `users` SET `zombieStartsThisTerm` = 0 WHERE 1;");
    mysql_query("UPDATE `users` SET `humanStartsThisTerm` = 0 WHERE 1;");
    mysql_query("UPDATE `users` SET `gamesModdedThisTerm` = 0 WHERE 1;");
    mysql_query("UPDATE `users` SET `adminMeetingsThisTerm` = 0 WHERE 1;");
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php placeTabIcon(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>UMBC HvZ - Admin Panel</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="/style.css" rel="stylesheet" type="text/css" media="all" />
<link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css' />

<script>
function confirmDeleteYear()
{
	var result = confirm("This will clear records for last term and move to the next term. This will also clear annual records. This action cannot be undone! Continue?");
	if(result == false)
	{
		return false;
	}	
	return true;
}
</script>

<script>
function confirmDeleteTerm()
{
	var result = confirm("This will clear records for last term and move to the next term. This action cannot be undone! Continue?");
	if(result == false)
	{
		return false;
	}	
	return true;
}
</script>

<?php htmlHeader(); ?>
</head>
<body>
<div id="wrapper">
	<?php pageHeader(); ?>
	<div id="page" class="container">
		<div id="content">
		
		<?php
		if($_SESSION['isAdmin'] >= 1){
			echo "<br/><h2><b><center>Voting Members</center></b></h2>";
			echo "<br></br>";
		
			echo "<table border=1>";
		
			echo "<tr>";
			echo "<td>Name</td>";
			echo "<td>Total Appearances</td>";
			echo "<td>Appearances This Term</td>";
			echo "<td>Appearances Last Term</td>";
			echo "<tr>";
			
			$ret = mysql_query("SELECT * FROM `users` WHERE `appearancesThisTerm` + `appearancesLastTerm` >= 5 ORDER BY `appearancesThisTerm` + `appearancesLastTerm` DESC");
			while($row = mysql_fetch_assoc($ret)) {
				$fname = $row['fname'];
				$lname = $row['lname'];
				echo "<td>$fname $lname</td>";
				$totalAppearances  = $row['appearancesTotal'];
				echo "<td>$totalAppearances</td>";
				$appearancesThisTerm = $row['appearancesThisTerm'];
				echo "<td>$appearancesThisTerm</td>";
				$appearancesLastTerm = $row['appearancesLastTerm'];
				echo "<td>$appearancesLastTerm</td>";
				echo "<tr>";
			}

			/*$ret = mysql_query("SELECT `fname`, `lname`, `appearancesThisYear` FROM `users` WHERE `appearancesThisYear` >= 5 ORDER BY `appearancesThisYear` DESC");
			while($row = mysql_fetch_assoc($ret)) {
				$fname = $row['fname'];
				$lname = $row['lname'];
				$meetingsAttended = $row['appearancesThisYear'];
				echo "$fname $lname - $meetingsAttended Meetings attended this year<br/>";
			}
			*/
			echo "</table><br/><br/>";
			
			if($_SESSION['isAdmin'] >= 2) {
				?>
				<br/><br/>
				<form method="post" onsubmit="return confirmDeleteYear();">
				<input type="submit" value="End Year" name="clearYear" id="clearYear"/>
				</form>
				<br/>
				<form method="post" onsubmit="return confirmDeleteTerm();">
				<input type="submit" value="End Semester" name="clearTerm" id="clearTerm"/>
				</form>
				<br/><br/>
				<?php
			}
			
			$count = mysql_oneline("SELECT COUNT(*) cnt FROM `users` WHERE `appearancesThisTerm` + `appearancesLastTerm` >= 5;");
			$count = $count['cnt'];
			echo "Number of voting members: $count";
		}else{
			echo "<h2>Hey, you're not an admin, get out of here!</h2>";
		}
		?>
		</div>
		<div id="sidebar">
			<div class="section1">
				<?php displayLoginForm();?>
			</div>
		</div>
		<div class="clearfix">&nbsp;</div>
	</div>
	<div id="footer" class="container">
		<?php printFooter(); ?>
	</div>
</div>
</body>
</html>