<?php
require_once('../pageIncludes/admin/points.inc.php');
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
<link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'/>
<?php htmlHeader(); ?>
</head>
<body>
<div id="wrapper">
	<?php pageHeader(); ?>
	<div id="page" class="container">
		<div id="content">
		<?php if($_SESSION['isAdmin'] >= 2 || $playerData['isLongGameAuthed'] >= 1){ ?>
			<?php if($curGame){ ?>
				<?php if($notificationText!="") echo "<h3>".$notificationText."</h3><br/>"; ?>
				<br/>
				<h3>Totals</h3>
				<h4>Currently, zombies have <?php echo $zombiePoints;?> points, and humans have <?php echo $humanPoints;?> points.</h4>
				<br/>
				<h3>View points log</h3>
				<br/>
				<div style="overflow: auto; height: 300px;">
				<?php echo $pointsLog; ?>
				</div>
				<?php if ($playerData['isLongGameAuthed'] >= 2) {?>
					<h3>Give points out</h3>
					For complex point additions (i.e. all humans/zombies at a mission), contact someone with database access; they can do it much faster than you can and will be glad to help.<br/>
					<form action="" method="post">
						Player(s) to give points to:<br/><?php echo $playerChecklist; ?><br/>
						<table>
						<tr><td>Points to give (negative takes away points):</td><td><input type="text" name="points"/></td></tr>
						<tr><td>Reason:</td><td><input type="text" name="reason"/></td></tr>
						</table>
						<input type="submit" name="submit" value="Submit"/>
					</form>
				<?php }?>
			<?php }else{ ?>
				Sorry, a long game isn't in progress, so you cannot give players points.
			<?php }?>
		<?php }else{ ?>
			<h2>Hey, you're not an admin, get out of here!</h2>
		<?php } ?>
		</div>
		<div id="sidebar">
			<div class="section1">
				<?php displayLoginForm();?>
			</div>
			<br />
			<div class="section4">
				<?php retrieveSlides();?>
			</div>
		<div class="clearfix">&nbsp;</div>
	</div>
	<div id="footer" class="container">
		<?php printFooter(); ?>
	</div>
</div>
</body>
</html>
