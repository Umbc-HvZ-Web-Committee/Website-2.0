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
				<?php /*
				<h2>Sidebar settings</h2>
				<form action="" method="post">
				<label for="baseSlides"><input type="radio" name="setSlides" value="no"/>Display one-night slides on the sidebar</label></br>
				<label for="longSlides"><input type="radio" name="setSlides" value="yes"/>Display weeklong slides on the sidebar</label></br>
				<input type="submit" name="slides" value="Set slides"/></br>
				</form><br/>
				*/ ?>
				
				<h2>Mission Slides</h2>
				<br/><br/>
				
				<h3>How to use:</h3><br/>
				<p>Select the slides to display on the sidebar with the radio buttons below. 
				If the set of slides you want to display is not there, 
				copy-paste the url of the google slides. Remove everything including and after "edit#slide", 
				and make sure the url ends with "/". The buttons to set slides only apply to ONE of the slideshows on the sidebar. 
				If you are updating multiple slideshows, you will have to update each one individually. 
				You can also use this to change the slideshow headings. Most of the time, the headings should read 
				"This Week's Missions", "Monday", "Thursday", "IGNORE" (from top text bar to bottom text bar)
				</p>
				<br/><br/>
				
				<h3>First Slides</h3>
				<p>Select a set of slides to display under the first slides (usually Monday slides):</p>
				<form action="" method="post">
				<label for="hvz101"><input type="radio" name="mondaySlides" value="hvz101"/>HvZ 101</label></br>
				<label for="hvz102"><input type="radio" name="mondaySlides" value="hvz102"/>HvZ 102</label></br>
				<label for="hvz202"><input type="radio" name="mondaySlides" value="hvz202"/>HvZ 202</label></br>
				<label for="underConstruction"><input type="radio" name="mondaySlides" value="underConstruction"/>Under Construction</label></br>
				<label for="endSemester"><input type="radio" name="mondaySlides" value="endSemester"/>End of Semester</label></br>
				<label for="fiveNight"><input type="radio" name="mondaySlides" value="fiveNight"/>Five Night</label></br><br/>
				Specify slide: <input type="text" name="customMondaySlides" id="customMondaySlide"/></br><br/>
				Starting Slide Number (Default is first slide): <input type="text" name="startingSlideNumber" id="startingSlideNumber"/></br><br/>
				<input type="submit" name="submit" value="Set First Slides"/></br><br/>
				</form><br/>
				<br/>
				<h3>Second Slides</h3>
				<p>Select a set of slides to display under the second slides (usually Thursday slides):</p>
				<form action="" method="post">
				<label for="hvz101"><input type="radio" name="thursdaySlides" value="hvz101"/>HvZ 101</label></br>
				<label for="hvz102"><input type="radio" name="thursdaySlides" value="hvz102"/>HvZ 102</label></br>
				<label for="hvz202"><input type="radio" name="thursdaySlides" value="hvz202"/>HvZ 202</label></br>
				<label for="underConstruction"><input type="radio" name="thursdaySlides" value="underConstruction"/>Under Construction</label></br>
				<label for="endSemester"><input type="radio" name="thursdaySlides" value="endSemester"/>End of Semester</label></br>
				<label for="fiveNight"><input type="radio" name="thursdaySlides" value="fiveNight"/>Five Night</label></br><br/>
				Specify slide: <input type="text" name="customThursdaySlides" id="customThursdaySlide"/></br><br/>
				Starting Slide Number (Default is first slide): <input type="text" name="startingSlideNumber" id="startingSlideNumber"/></br><br/>
				<input type="submit" name="submit" value="Set Second Slides"/></br>
				</form><br/>
				<br/>
				<h3>Third Slides</h3>
				<p>Select a set of slides to display under the third slides (usually point slides):</p>
				<form action="" method="post">
				<label for="hvz101"><input type="radio" name="pointSlides" value="hvz101"/>HvZ 101</label></br>
				<label for="hvz102"><input type="radio" name="pointSlides" value="hvz102"/>HvZ 102</label></br>
				<label for="hvz202"><input type="radio" name="pointSlides" value="hvz202"/>HvZ 202</label></br>
				<label for="underConstruction"><input type="radio" name="pointSlides" value="underConstruction"/>Under Construction</label></br>
				<label for="endSemester"><input type="radio" name="pointSlides" value="endSemester"/>End of Semester</label></br>
				<label for="fiveNight"><input type="radio" name="pointSlides" value="fiveNight"/>Five Night</label></br><br/>
				Specify slide: <input type="text" name="customPointSlides" id="customPointSlide"/></br><br/>
				Starting Slide Number (Default is first slide): <input type="text" name="startingSlideNumber" id="startingSlideNumber"/></br><br/>
				<input type="submit" name="submit" value="Set Third Slides"/></br>
				</form><br/>
				<br/>
				<h3>Mission Slide Headers</h3>
				<p>Enter a heading in one of the text boxes below to update the corresponding heading (leave blank for no change)</p>
				<form action="" method="post">
				Main Heading: <input type="text" name="mainHeading" id="mainHeading"/></br><br/>
				First Slides: <input type="text" name="firstSlides" id="firstSlides"/></br><br/>
				Second Slides: <input type="text" name="secondSlides" id="secondSlides"/></br><br/>
				Third Slides (Enter "IGNORE" to clear) <input type="text" name="thirdSlides" id="thirdSlides"/></br><br/>
				<input type="submit" name="submit" value="Update Headings"/></br>
				</form><br/>
				
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