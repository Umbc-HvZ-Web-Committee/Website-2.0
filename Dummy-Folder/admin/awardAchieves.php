<?php
require_once('../pageIncludes/admin/awardAchieves.inc.php');
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
		<?php if($_SESSION['isAdmin'] >= 2){ ?>

			<h1 style="text-align:center">Award an Achievement</h1><br/>
			<form action="" method="post">
			Username or Email: <input type="text" name="uname"></input><br/>
			Achievement: 
				<select name="achieve">
					<?php 
					generateList();
					?>
				</select><br/>
			<input type="submit" name="submit" value="Submit"></input>
			</form>
			
			<?php 
			echo $status;
				
		} else{ ?>
			<h2>Hey, you're not an admin, get out of here!</h2>
		<? } ?>
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