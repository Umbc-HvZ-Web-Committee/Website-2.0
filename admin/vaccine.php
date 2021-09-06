<?php
require_once('../pageIncludes/admin/vaccine.inc.php');
$settings = get_settings();
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
<a name="top"></a>
<div id="wrapper">
	<?php pageHeader(); ?>
	<div id="page" class="container">
		<div id="content">
		<?php if(array_key_exists('meetingMessage', $GLOBALS) && $GLOBALS['meetingMessage']!="") echo "<h3>".$GLOBALS['meetingMessage']."</h3><br/>"; ?>
		
		<?php if($_SESSION['isAdmin'] >= 2) { ?>
			<h2>Update vaccination records</h2>
			
			<form action="" method="post">
			Enter username of player below:<br/> 
			<input type="text" name="user" id="user"/></br><br/>
			Voting Link: <br/>
			<label for="vaccineStatus"><input type="radio" name="vaccineStatus" value="0"/>Not vaccinated</label></br>
			<label for="vaccineStatus"><input type="radio" name="vaccineStatus" value="1"/>Received single dose</label></br>
			<label for="vaccineStatus"><input type="radio" name="vaccineStatus" value="2"/>Double-vaccinated, or fully-vaccinated via one-shot vaccine</label></br>
			<label for="vaccineStatus"><input type="radio" name="vaccineStatus" value="3"/>Fully-vaccinated with booster</label></br>
			<br/>
			<input type="submit" name="submit" value="Update Vaccine Status"/></br>
			</form><br/>
				
		<?php } else { ?>
			<h2>Hey, you're not an admin, get out of here!</h2>
		<?php } ?>
		</div>
		<?php printSidebar(); 
		?>
		<div class="clearfix">&nbsp;</div>
	</div>
	<div id="footer" class="container">
		<?php printFooter(); ?>
	</div>
</div>
</body>
</html>