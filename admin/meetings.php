<?php
require_once('../pageIncludes/admin/meetings.inc.php');
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
<script type="text/javascript">
function load(){
	document.getElementById("signInID").focus();
}
</script>
</head>
<body onload="load();">
<div id="wrapper">
	<?php pageHeader(); ?>
	<div id="page" class="container">
		<div id="content">
		<?php if(array_key_exists('meetingMessage', $GLOBALS) && $GLOBALS['meetingMessage']!="") echo "<h3>".$GLOBALS['meetingMessage']."</h3><br/>"; ?>
		<?php if($_SESSION['isAdmin'] >= 2){ ?>
			<h2>Meeting Sign In</h2>
			<form action="" method="post"><p>
				Meeting to sign into: <?php meetingSelect(); ?><br/>
				QR Code, username, email address, or UMBC ID: <input type="text" name="playerID" id="signInID"/>
				<?php if(!$curLongGame){?>
					<!-- <br/>
					<label for="unregistered"><input type="checkbox" name="unregistered" id="unregistered"/>Unregistered player (put their full name in the box)</label>-->
					<br/>
					<label for="pregame"><input type="checkbox" name="pregame" id="pregame" <!--checked="checked-->"/>Also attending a pre-game for the next long game</label>
					<br/>
					<label for="pregame"><input type="checkbox" name="holiday" id="holiday" <!--checked="checked-->"/>Also attending a holiday mission</label>
					<br/>
					<label for="state1"><input type="radio" name="state" value="1" id="state1" checked="checked"/>Human</label>
					<label for="state2"><input type="radio" name="state" value="2" id="state2"/>OZ (Hidden)</label>
					<label for="state-1"><input type="radio" name="state" value="-1" id="state-1"/>Zombie</label>
					<label for="state4"><input type="radio" name="state" value="4" id="state4"/>Moderator</label>
					<label for="state0"><input type="radio" name="state" value="0" id="state0"/>N/A / undecided</label><br/>
				<?php }else {?>
					<br/>
					<label for="state4"><input type="radio" name="state" value="4" id="state4"/>Moderator</label>
					<label for="state-3"><input type="radio" name="state" value="-3" id="state-3"/>Player</label>
					<label for="state0"><input type="radio" name="state" value="0" id="state0"/>Other</label><br/>
				<?php }?>
				<input type="submit" name="submit" value="Sign player in"/>
			</p></form>
			<h2>Meeting Creation</h2>
			<form action="" method="post"><p>
				Remember that today's date is appended to the meeting name. Meetings will have their category (e.g. "[Mission] ") added to the beginning of the name.<br/><br/> 
				Note: Nominal meetings will not count towards the total number of appearances but can be used to count towards the required semester total for membership<br/><br>
				Name for meeting: <input type="text" name="meetingName"/><br/>
				<label for="mission"><input type="radio" name="meetingType" value="mission" id="mission" checked="checked"/>HvZ Mission</label>
				<label for="admin"><input type="radio" name="meetingType" value="admin" id="admin"/>Admin Meeting</label>
				<label for="admin"><input type="radio" name="meetingType" value="nominal" id="nominal"/>Nominal Meeting</label>
				<label for="mission"><input type="radio" name="meetingType" value="other" id="other"/>Other Meeting</label>
				<br/>
				<input type="submit" name="submit" value="Create new meeting"/>
			</p></form>
			<h2>Meeting resolution</h2>
			<form action="" method="post"><p>
				Meeting to resolve: <?php meetingSelect(); ?><br/>
				<input type="radio" name="state" value="1"/>Humans won<br/>
				<input type="radio" name="state" value="-1"/>Zombies won<br/>
				<input type="radio" name="state" value="0"/>Other<br/>
				<!-- <input type="radio" name="state" value="NULL" checked="checked"/>Do not allocate points<br/> -->
				<input type="submit" name="submit" value="Resolve meeting"/>
			</p></form>
			<h2>Players in meeting</h2>
			<form action="" method="post"><p>
				<div>Meeting to check attendance for:</div><?php meetingSelect2(); ?><br/>
				<input type="submit" name="submit" value="View Attendance"/>
			</p></form>
		
		<?php }else if ($_SESSION['isAdmin'] >= 1){ ?>
			<h2>Meeting Sign In</h2>
			<form action="" method="post"><p>
				Meeting to sign into: <?php meetingSelect(); ?><br/>
				QR Code, username, email address, or UMBC ID: <input type="text" name="playerID" id="signInID"/>
				<?php if(!$curLongGame){?>
					<!-- <br/>
					<label for="unregistered"><input type="checkbox" name="unregistered" id="unregistered"/>Unregistered player (put their full name in the box)</label>-->
					<br/>
					<label for="pregame"><input type="checkbox" name="pregame" id="pregame" <!--checked="checked-->"/>Also attending a pre-game for the next long game</label>
					<br/>
					<label for="pregame"><input type="checkbox" name="holiday" id="holiday" <!--checked="checked-->"/>Also attending a holiday mission</label>
					<br/>
					<label for="state1"><input type="radio" name="state" value="1" id="state1" checked="checked"/>Human</label>
					<label for="state2"><input type="radio" name="state" value="2" id="state2"/>OZ (Hidden)</label>
					<label for="state-1"><input type="radio" name="state" value="-1" id="state-1"/>Zombie</label>
					<label for="state4"><input type="radio" name="state" value="4" id="state4"/>Moderator</label>
					<label for="state0"><input type="radio" name="state" value="0" id="state0"/>N/A / undecided</label><br/>
				<?php }else {?>
					<br/>
					<label for="state4"><input type="radio" name="state" value="4" id="state4"/>Moderator</label>
					<label for="state-3"><input type="radio" name="state" value="-3" id="state-3"/>Player</label>
					<label for="state0"><input type="radio" name="state" value="0" id="state0"/>Other</label><br/>
				<?php }?>
				<input type="submit" name="submit" value="Sign player in"/>
			</p></form>
			<h2>Meeting Creation</h2>
			<form action="" method="post"><p>
				Remember that today's date is appended to the meeting name. Meetings will have their category (e.g. "[Mission] ") added to the beginning of the name.<br/><br/> 
				Note: Nominal meetings will not count towards the total number of appearances but can be used to count towards the required semester total for membership<br/><br>
				Name for meeting: <input type="text" name="meetingName"/><br/>
				<label for="mission"><input type="radio" name="meetingType" value="mission" id="mission" checked="checked"/>HvZ Mission</label>
				<label for="admin"><input type="radio" name="meetingType" value="admin" id="admin"/>Admin Meeting</label>
				<label for="admin"><input type="radio" name="meetingType" value="nominal" id="nominal"/>Nominal Meeting</label>
				<label for="mission"><input type="radio" name="meetingType" value="other" id="other"/>Other Meeting</label>
				<br/>
				<input type="submit" name="submit" value="Create new meeting"/>
			</p></form>
			<h2>Players in meeting</h2>
			<form action="" method="post"><p>
				<div>Meeting to check attendance for:</div><?php meetingSelect2(); ?><br/>
				<input type="submit" name="submit" value="View Attendance"/>
			</p></form>
				<div>
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