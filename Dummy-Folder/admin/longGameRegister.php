<?php
require_once('../pageIncludes/admin/longGameRegister.inc.php');
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
		<?php 
		
		
		if($playerData['isLongGameAuthed'] >= 2){
			if($GLOBALS['submitMessage']!="") echo "<h3>${GLOBALS['submitMessage']}</h3>" ?>
			
			<h2>Register for a long game</h2>
			<form action="" method="post">
				Adding player for game & Waiver Submission: <?php longGameSelect();?><br/>
				Player identification (username, UMBC ID, QR code): <input type="text" name="playerID"/><br/>
				<!--Bandanna number being borrowed (just the number): <input type="text" name="bandannaNumber"/><br/> -->
				<label for="waiverIn"><input type="radio" name="waiver" value="newOne" id="waiverIn"/> Turning in a new waiver for the current academic year</label><br/>
				<label for="waiverNew"><input type="radio" name="waiver" value="turnedIn" id="waiverNew"/> Already turned in a waiver for the current academic year</label><br/>
				<label for="waiver"><input type="radio" name="waiver" value="waiverOnly" id="waiverNew"/> Only turning in a waiver (not playing next long game)</label><br/>
				
				<!-- <br/>Register for text updates:<br/>
				<label for="yes"><input type="radio" name="phone" value="yes" id="yes"/> Yes</label><br/>
				<label for="no"><input type="radio" name="phone" value="no" id="no"/> No</label><br/>
				Phone number: <input type="text" name="phoneNum"/><br/>
				Carrier: <input type="text" name="carrier"/><br/> -->
				
				<input type="submit" name="submit" value="Submit"/>
			</form><br/>
			<h2>Create a long game</h2>
			<form action="" method="post">
				<input type="text" name="title"/><br/>
				Dates: <input type="text" name="startDate" size="3" placeholder="mm/dd" maxlength="5"/> to 
				<input type="text" name="endDate" size="3" placeholder="mm/dd" maxlength="5"/><br/>
				<input type="submit" name="create" value="Create"/>
			</form><br/>
			<h2>Close long game</h2>
			<form action="" method="post">
				<?php longGameSelect();?><br/>
				<input type="submit" name="close" value="Close Game"/>
			</form>
			<br/>
			<!--
			<h2>Return a bandanna</h2>
			<form action="" method="post">
				<input type="text" name="number"/>
				<input type="submit" name="return" value="Submit"/>
			</form><br/>
			-->
			<h2>Generate player mailing list</h2>
			<form action="" method="post">
				<input type="submit" name="mailing" value="Generate"/>
				<input type="submit" name="mailing_h" value="Generate (Human Only)"/>
				<input type="submit" name="mailing_z" value="Generate (Zombie Only)"/>
				<input type="submit" name="removeMailing" value="Hide Current List"/>
			</form>
			<br/>
			<?php echo "$html";?>
			<br/>
			<h2>Sidebar settings</h2>
			<form action="" method="post">
				<label for="baseSlides"><input type="radio" name="setSlides" value="no"/>Display one-night slides on the sidebar</label></br>
				<label for="longSlides"><input type="radio" name="setSlides" value="yes"/>Display weeklong slides on the sidebar</label></br>
				<input type="submit" name="slides" value="Set slides"/></br>
			</form>
			</br>
		<?php }else if($playerData['isLongGameAuthed'] >= 1) {
			?>
			<h2>Register for a long game</h2>
			<form action="" method="post">
				Adding player for game & Waiver Submission: <?php longGameSelect();?><br/>
				Player identification (username, UMBC ID, QR code): <input type="text" name="playerID"/><br/>
				<!--Bandanna number being borrowed (just the number): <input type="text" name="bandannaNumber"/><br/> -->
				<label for="waiverIn"><input type="radio" name="waiver" value="newOne" id="waiverIn"/> Turning in a new waiver for the current academic year</label><br/>
				<label for="waiverNew"><input type="radio" name="waiver" value="turnedIn" id="waiverNew"/> Already turned in a waiver for the current academic year</label><br/>
				<label for="waiver"><input type="radio" name="waiver" value="waiverOnly" id="waiverNew"/> Only turning in a waiver (not playing next long game)</label><br/>
				
				<!-- <br/>Register for text updates:<br/>
				<label for="yes"><input type="radio" name="phone" value="yes" id="yes"/> Yes</label><br/>
				<label for="no"><input type="radio" name="phone" value="no" id="no"/> No</label><br/>
				Phone number: <input type="text" name="phoneNum"/><br/>
				Carrier: <input type="text" name="carrier"/><br/> -->
				
				<input type="submit" name="submit" value="Submit"/>
			</form><br/>
			<h2>Generate player mailing list</h2>
			<form action="" method="post">
				<input type="submit" name="mailing" value="Generate"/>
				<input type="submit" name="mailing_h" value="Generate (Human Only)"/>
				<input type="submit" name="mailing_z" value="Generate (Zombie Only)"/>
				<input type="submit" name="removeMailing" value="Hide Current List"/>
			</form>
			<br/>
			<?php echo "$html";?>
			<br/>
		<?php }else{ ?>
			<h2>Hey, you're not an admin, get out of here!</h2>
		<?php } ?>
		</div>
		<div id="sidebar">
			<div class="section1">
				<?php displayLoginForm();?>
			</div>
		</div>
	</div>
	<div id="footer" class="container">
		<?php printFooter(); ?>
	</div>
</div>
</body>
</html>
<?php

?>
