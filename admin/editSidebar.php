<?php
require_once('../pageIncludes/admin/editSidebar.inc.php');
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
			<?php if($_SESSION['isAdmin'] >= 2){ ?>
			
				<b>WARNING: Nothing on thispage has any functionaliy yet. Please do not interact with this unless you have been directed to do so by the web committee or it actually works properly now and I just forgot to clean this warning up</b><br/>
				<h2>Sidebar settings</h2>
				<form action="" method="post">
				<label for="baseSlides"><input type="radio" name="setSlides" value="no"/>Display one-night slides on the sidebar</label></br>
				<label for="longSlides"><input type="radio" name="setSlides" value="yes"/>Display weeklong slides on the sidebar</label></br>
				<input type="submit" name="slides" value="Set slides"/></br>
				</form><br/>
			
				<h2>Monday Slides</h2>
				<p>Select a set of slides to display under Monday's slides:</p>
				<form action="" method="post">
				<label for="hvz101"><input type="radio" name="mondaySlides" value="hvz101"/>HvZ 101</label></br>
				<label for="hvz102"><input type="radio" name="mondaySlides" value="hvz102"/>HvZ 102</label></br>
				<label for="underConstruction"><input type="radio" name="mondaySlides" value="underConstruction"/>Under Construction</label></br>
				<label for="end"><input type="radio" name="mondaySlides" value="end"/>End of Semester</label></br>
				<?echo "<br>Other: <input name=customMondaySlides".($test?"":" value=\"{$customMondaySlide}\"")."><br><br>";?>
				<input type="submit" name="mondaySlides" value="Set slides"/></br>
				</form><br/>
			<?}else{ ?>
				<h2>Hey, you're not an admin, get out of here!</h2>
			<? } ?>
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