<?php
require_once('pageIncludes/kill.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php placeTabIcon(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>UMBC HvZ - Log A Kill</title>
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
			<?php
			if(isset($GLOBALS['killNotification']) && $GLOBALS['killNotification']!="") echo $GLOBALS['killNotification']."<br/>";
			if(isLoggedIn()){
			?>
			<form action="" method="post">
			Kill ID: <input name="killID"></input>
			<input type="submit" name="submit" value="Submit"/>
			</form>
			<?php }else{?>
			<h3>Please log in to log your kill.</h3>
			<?php }?>
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
