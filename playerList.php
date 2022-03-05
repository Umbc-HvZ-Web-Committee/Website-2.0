<?php
require_once('pageIncludes/playerList.inc.php');
if (isset($_SESSION['UID'])) {
    $UID = $_SESSION['UID'];
    $playerData = mysql_oneline("SELECT * FROM `users` WHERE `UID`='$UID'");
} else {
    $UID = null;
    $playerData = array();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php placeTabIcon(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>UMBC HvZ - Player List</title>
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
			<h1 style="text-align:center; margin-top: 10px;"><b>Player List</b></h1><br/>
			<form>
			<b>Order By:</b>
			<input type="radio" name="order" value="name"/> Name
			<input type="radio" name="order" value="kills"/> Kill Count
			<input type="radio" name="order" value="survived"/> Days Survived
			<input type="radio" name="order" value="creation"/> Account Creation Time
			&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Submit" name="submit"/>
			</form><br/>
			<?php		
			printPlayerTable();
			?>
		</div>
		<div id="sidebar">
			<div class="section1">
				<?php displayLoginForm();?>
			</div>
			<br />
			<div class="section4">
				<?php retrieveSlides();?>
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