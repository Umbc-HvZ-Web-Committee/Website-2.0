<?php
require_once('../pageIncludes/admin/training.inc.php');
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
		<?php if($_SESSION['isAdmin'] >= 2){ ?>
			<h2>Officer & Web Committee Website Guide</h2>
			
			<p>This page is for resources for the Officer Board & Web Committee to familiarize themselves 
			with this club's website. Links to outside videos are provided at the moment as placeholders 
			for content that does not exist yet. All new officers & web committee members should be directed
			to this page so these resources can serve as website training for them.</p>
			
			<h3>Basic <strike>Normie</strike> Website Walkthrough</h3>
			<embed src="https://www.youtube.com/embed/watch?v=TSN0s06Ip_8" width="480" height="286" scale="aspect" controller="true">
			
			<h3>Admin Panel Walkthrough</h3>
			<embed src="https://www.youtube.com/embed/watch?v=B6UVxGpAp48" width="480" height="286" scale="aspect" controller="true">
			
			<!-- The yt links are shitpost placeholders for future content to be put here -->
			
			<h3>Weeklong Logistics Walkthrough</h3>
			<embed src="https://www.youtube.com/watch?v=6Dh-RL__uN4" width="1440" height="858" scale="aspect" controller="true">
			
			<h3>Election Logistics Walkthrough</h3>
			<embed src="https://www.youtube.com/watch?v=U06jlgpMtQs" width="1440" height="858" scale="aspect" controller="true">
		
		<?php }else if($_SESSION['isAdmin'] >= 3){ ?>
			<h2>Additional Resources (Web Committee Only)</h2>

			<h3>Web Committee GitHub and Database Access</h3>
			<embed src="https://www.youtube.com/watch?v=Qskm9MTz2V4" width="1440" height="858" scale="aspect" controller="true">
			
			
			
		<?php }else{ ?>
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