<?php
require_once('../pageIncludes/admin/blogPost.inc.php');
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
<body onload="load();">
<div id="wrapper">
	<?php pageHeader(); ?>
	<div id="page" class="container">
		<div id="content">
		<?php if($GLOBALS['meetingMessage']!="") echo "<h3>".$GLOBALS['meetingMessage']."</h3><br/>"; ?>
		<?php if($_SESSION['isAdmin'] >= 2){ ?>
		
			<h1 style="text-align:center">Create a Blog Post</h1><br/>
			<h3 style="text-align:center">Currently only supports html formatting<br/>
			Go to <a href="http://www.onlinehtmleditor.net/" target="_blank">this online html editor</a> to check your formatting</br>
			(Plain text is also acceptable.)</h3><br/>
				
			<form action="confirmPost.php" method="post">
			<h4>Author:  <?php echo $playerData['fname']; ?> <br/>
			Title: <input type="text" name="title"></input> <br/>
			Content:<br/>
			<textarea rows="10" cols="60" name="content"></textarea>
			</h4>
			<input type="submit" name="submit" value="Submit"></input>
			</form>
		
		<?php }else{ ?>
			<h2>Hey, you're not an admin, get out of here!</h2>
		<?php } ?>
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