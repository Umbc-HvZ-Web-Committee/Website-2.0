<?php
require_once('../pageIncludes/admin/ozSelect.inc.php');
$playerData = mysql_oneline("SELECT * FROM users WHERE UID='{$_SESSION['uid']}'");
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
<?php htmlHeader(); ?>
</head>
<body>
<div id="wrapper">
	<?php pageHeader(); ?>
	<div id="page" class="container">
		<div id="content">
		<?php
		if($GLOBALS['meetingMessage']!="") echo "<h3>".$GLOBALS['meetingMessage']."</h3><br/>";
		$playerData = mysql_oneline("SELECT * FROM users WHERE UID='{$_SESSION['uid']}'");
		if($playerData['isLongGameAuthed'] >= 1){ ?>
			<h2>OZ Pool:</h2>
			<form action="" method="post">
				<?php longGameSelect();?>
				<input type="submit" value="Update"/>
			</form>
			<table border=1>
				<?php
					echo "<tr>";
					echo "<td>Starting Side</td>";
					echo "<td>Name</td>";
					echo "<td>OZ Paragraph</td>";
					echo "<td>Missions Started Human, Zombie, Moderator</td>";
					//echo "<td>Picture</td>";
					echo "<tr>";
						
					$sql = "SELECT UID, fname, lname, ozParagraph, timesAsOZ, humanStartsTotal, zombieStartsTotal, gamesModdedTotal, state FROM users, long_players WHERE "
					."users.UID=long_players.playerID AND users.ozOptIn=1 AND long_players.gameID = '$gameID'"
					."ORDER BY long_players.playerID";
					$qury = mysql_query($sql);
					while($ret = mysql_fetch_assoc($qury)){
						$para = $ret['ozParagraph'];
						$para = str_replace("\n","<br>",$para);
						echo "<tr>";
						if($ret['state'] == 2) {
							echo "<td><b>OZ</b></td>";
						}
						else if ($_SESSION['uid'] >= 2 || $playerData['isLongGameAuthed'] >= 2) {
							echo '<td><form action="" method="post"><input type="hidden" name="longGameSelect" value="'.$gameID.'" />'
							.'<input type="hidden" name="ozUID" value="'.$ret['UID'].'" /><input type="submit" name="ozSelect" value="Make OZ"/></form></td>';
						}
						else {
							echo "<td>Human</td>";
						}
						
						if($ret['timesAsOZ'] > 0) {
							echo "<td><strike>{$ret['fname']} {$ret['lname']}</strike></td>";
						} else {
							echo "<td>{$ret['fname']} {$ret['lname']}</td>";
						}
						
						echo "<td>$para</td>";
						
						$humanStarts = $ret['humanStartsTotal'];
						$zombieStarts = $ret['zombieStartsTotal'];
						$modStarts = $ret['gamesModdedTotal'];
						
						echo "<td>".$humanStarts.", ".$zombieStarts.", ".$modStarts."</td>";
						
						
						
						echo "<tr>";
					}
				?>
			</table>
			<br/><br/>
			<?php
				echo "Un-selection of an OZ can only happen manually in the database. Selection of an OZ requires a long game admin level of 2.";
			?>
		<?php }else{ ?>
			<h2>Hey, you're not an admin, get out of here!</h2>
		<?php } ?>
		</div>
		<?php if(!($playerData['isLongGameAuthed'] >= 1)) { /*Don't show login form if already logged as a mod, allows table to occupy more space*/ ?>
			<div id="sidebar">
				<div class="section1">
					<?php displayLoginForm();?>
				</div>
			</div>
		<?php } ?>
	<div class="clearfix">&nbsp;</div>
	</div>
	<div id="footer" class="container">
		<?php printFooter(); ?>
	</div>
</div>
</body>
</html>