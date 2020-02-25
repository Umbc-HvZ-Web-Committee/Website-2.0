<?php
require_once('pageIncludes/achievements.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php placeTabIcon(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>UMBC HvZ - Achievements</title>
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
		
		<script type="text/javascript">
		/*var pass = prompt("This page is currently under construction. Admins may enter the password:");
		if(pass != "JaytheOZ") {
			alert("Password incorrect.  Redirecting to umbchvz.com");
			window.location="http://umbchvz.com";
		}*/
		</script>
		
			<h1 style="text-align:center; margin-top: 10px;"><b>Achievement Database</b></h1><br/>
			<b><center>If you're new to the achievement system, read the <a href="achievementsFaq.php">Achievement FAQs</a></center></b><br/>
			<b><center>To submit an achievement icon design, <a href="achievementsFaq.php#Q4">click here</a></center></b><br/>
			<b><center>To submit an achievement, <a href="https://umbchvz.com/achievementsFaq.php#Q6">click here</a></center></b><br/>
			<p> In addition to simply having fun at our missions, you can attempt to earn the achievements listed here.
			There are three standard achievement classes: Basic, Recruit, and Veteran; these are categorized based on their difficulty
			to earn. Furthermore, each achievement is also given one of the following affiliations: Human, Zombie, or Neutral.</p>
			<p>There does exist a prestigious fourth class of achievement called Legendary. Legendary achievements are incredibly difficult
			to earn, and might only ever be awarded to a limited number of players. Some of these achievements will only be awardable at
			certain times. Legendary achievements that could feasibly be earned again (however unlikely) are listed at the bottom of this
			database.
			</p>
			<p><b><center>~Note that all achievements must be verifiable by a moderator to be earned~</center></b></p>
			<?php 
			printAchievementDatabase();?>
			
			<br/><br/>
			<h2><b>Retired Achievements</b></h2>
			<p>The achievement system was overhauled and restarted in the Spring 2016 semester after a long period of inactivity.
			The following achievements belong to the old system and are displayed here in remeberance of the Veterans who earned them.
			</p>
			<p><center><b>~Note that, as they are retired, none of these achievements will be awarded~</b></center></p>
			
			<?php 
			printRetiredAchievements();
			?>
			
		</div>
		<?php printSidebar(); ?>
		<div class="clearfix">&nbsp;</div>
	</div>
	<div id="footer" class="container">
		<?php printFooter(); ?>
	</div>
</div>
</body>
</html>