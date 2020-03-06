<?php
require_once('pageIncludes/myProfile.inc.php');
require_once('includes/update.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php placeTabIcon(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>UMBC HvZ - My Profile</title>
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
			if($_SESSION['isAdmin'] >= -1) { //This is here to easily restrict access to the page. Just change the -1 to 1 or 2 to restrict.
				if($playerData){
					if(isset($GLOBALS['profileMessage']) && $GLOBALS['profileMessage']!="") echo "<h3>".$GLOBALS['profileMessage']."</h3>";
					echo "<h2><b>Hi, {$playerData['fname']}!</h2><hr style=\"display: block;\"></b><br/>";
					
					//temporary one-time block of code to set data right in database
					//leaving it here for reference and in case similar code is needed
					/*
					$uid = "US0000-";
					while($uid != "US003oj") {
						echo $uid;
						echo " ";
						$totalCount = mysql_oneline("SELECT COUNT(*) cnt FROM `meeting_log` WHERE `UID` = '$uid';");
						$totalCount = $totalCount['cnt'];
						echo $totalCount;
						echo " ";
						$humanCount = mysql_oneline("SELECT COUNT(*) cnt FROM `meeting_log` WHERE `UID` = '$uid' AND `startState` = '1';");
						$humanCount = $humanCount['cnt'];
						echo $humanCount;
						echo " ";
						$zombieCount = mysql_oneline("SELECT COUNT(*) cnt FROM `meeting_log` WHERE `UID` = '$uid' AND (`startState` < '0' OR `startState` = '2');");
						$zombieCount = $zombieCount['cnt'];
						echo $zombieCount;
						echo " ";
						$modCount = mysql_oneline("SELECT COUNT(*) cnt FROM `meeting_log` WHERE `UID` = '$uid' AND `startState` = '4';");
						$modCount = $modCount['cnt'];
						echo $modCount;
						echo " ";
						$adminCount = mysql_oneline("SELECT COUNT(*) cnt FROM `meeting_log` NATURAL JOIN `meeting_list` WHERE `UID` = '$uid' AND `meetingType` = '1';");
						$adminCount = $adminCount['cnt'];
						echo $adminCount;
						echo "<br/>";
						mysql_query("UPDATE `users` SET `zombieStartsTotal`='$zombieCount' WHERE `UID` = '$uid';");
						mysql_query("UPDATE `users` SET `humanStartsTotal`='$humanCount' WHERE `UID` = '$uid';");
						mysql_query("UPDATE `users` SET `gamesModdedTotal`='$modCount' WHERE `UID` = '$uid';");
						mysql_query("UPDATE `users` SET `appearancesTotal`='$totalCount' WHERE `UID` = '$uid';");
						mysql_query("UPDATE `users` SET `adminMeetingsTotal`='$adminCount' WHERE `UID` = '$uid';");
					
						$chars = array();
						array_push($chars, "-");
						for($i=0; $i<10; $i++){
							array_push($chars, "$i");
						}
						for($i=0, $c='a'; $i<26; $i++, $c++){
							array_push($chars, "$c");
						}
						echo "Set acceptable chars | ";
						//Take the highest UID, add one, then make a new ID from that.
						$id = str_split(substr($uid, 2));
						$curCharPos = 4;
						echo "Beginning loop | ";
						while($curCharPos>-1){
							echo "Begin iteration at index $curCharPos | ";
							$curChar = $id[$curCharPos];
							if($curChar != $chars[36]){
								$charIndex = array_search($curChar, $chars);
								$charIndex += 1;
								$id[$curCharPos] = $chars[$charIndex];
								break;
							}else{
								$id[$curCharPos] = $chars[0];
								//and continue to the next one
								$curCharPos -= 1;
							}
							echo "End iteration at index $curCharPos | ";
						}
						echo "Attaching prefix | ";
						$uid = "US".implode($id);
						echo "Attached";
						echo "<br/>";
					}*/
					
					
					/*
					$uid = "US0000-";
					while($uid != "US003q-") {
						echo $uid;
						echo " ";
						$totalCount = mysql_oneline("SELECT COUNT(*) cnt FROM `meeting_log` NATURAL JOIN `meeting_list` WHERE `UID` = '$uid' AND `meetingID` >= 'ME0005A' AND `meetingID` <= 'ME00069' AND `isResolved` = '1' ORDER BY `meetingID` DESC;");
						$totalCount = $totalCount['cnt'];
						echo $totalCount;
						echo " ";
						$humanCount = mysql_oneline("SELECT COUNT(*) cnt FROM `meeting_log` NATURAL JOIN `meeting_list` WHERE `UID` = '$uid' AND `startState` = '1' AND `UID` = '$uid' AND `meetingID` >= 'ME0005A' AND `meetingID` <= 'ME00069' AND `isResolved` = '1' ORDER BY `meetingID` DESC;");
						$humanCount = $humanCount['cnt'];
						echo $humanCount;
						echo " ";
						$zombieCount = mysql_oneline("SELECT COUNT(*) cnt FROM `meeting_log` NATURAL JOIN `meeting_list` WHERE `UID` = '$uid' AND (`startState` < '0' OR `startState` = '2') AND `UID` = '$uid' AND `meetingID` >= 'ME0005A' AND `meetingID` <= 'ME00069' AND `isResolved` = '1' ORDER BY `meetingID` DESC;");
						$zombieCount = $zombieCount['cnt'];
						echo $zombieCount;
						echo " ";
						$modCount = mysql_oneline("SELECT COUNT(*) cnt FROM `meeting_log` NATURAL JOIN `meeting_list` WHERE `UID` = '$uid' AND `startState` = '4' AND `UID` = '$uid' AND `meetingID` >= 'ME0005A' AND `meetingID` <= 'ME00069' AND `isResolved` = '1' ORDER BY `meetingID` DESC;");
						$modCount = $modCount['cnt'];
						echo $modCount;
						echo "<br/>";
						mysql_query("UPDATE `users` SET `zombieStartsThisTerm`='$zombieCount' WHERE `UID` = '$uid';");
						mysql_query("UPDATE `users` SET `humanStartsThisTerm`='$humanCount' WHERE `UID` = '$uid';");
						mysql_query("UPDATE `users` SET `gamesModdedThisTerm`='$modCount' WHERE `UID` = '$uid';");
						mysql_query("UPDATE `users` SET `appearancesThisTerm`='$totalCount' WHERE `UID` = '$uid';");
					
						$chars = array();
						array_push($chars, "-");
						for($i=0; $i<10; $i++){
							array_push($chars, "$i");
						}
						for($i=0, $c='a'; $i<26; $i++, $c++){
							array_push($chars, "$c");
						}
						echo "Set acceptable chars | ";
						//Take the highest UID, add one, then make a new ID from that.
						$id = str_split(substr($uid, 2));
						$curCharPos = 4;
						echo "Beginning loop | ";
						while($curCharPos>-1){
							echo "Begin iteration at index $curCharPos | ";
							$curChar = $id[$curCharPos];
							if($curChar != $chars[36]){
								$charIndex = array_search($curChar, $chars);
								$charIndex += 1;
								$id[$curCharPos] = $chars[$charIndex];
								break;
							}else{
								$id[$curCharPos] = $chars[0];
								//and continue to the next one
								$curCharPos -= 1;
							}
							echo "End iteration at index $curCharPos | ";
						}
						echo "Attaching prefix | ";
						$uid = "US".implode($id);
						echo "Attached";
						echo "<br/>";
					}
					*/
					
					$uid = $_SESSION['uid'];
					$ret = mysql_oneline("SELECT * FROM `users` WHERE `UID` = '$uid';");
					
					echo "<h2><i>Game & Attendance Statistics</i></h2><br/>";
					
					echo "<h3>This Semester's Attendance</h3></br>";
					$total = $ret['appearancesThisTerm'];
					$zombie = $ret['zombieStartsThisTerm'];
					$human = $ret['humanStartsThisTerm'];
					$mod = $ret['gamesModdedThisTerm'];
					$admins = $ret['adminMeetingsThisTerm'];
					echo "Total Appearances: $total </br>";
					echo "Missions Started Zombie/OZ: $zombie </br>";
					echo "Missions Started Human: $human </br>";
					echo "Missions Moderated: $mod </br>";
					echo "Officer Meetings Attended: $admins </br>";
					
					echo "</br><h3>Cumulative Attendance</h3></br>";
					$total = $ret['appearancesTotal'];
					$zombie = $ret['zombieStartsTotal'];
					$human = $ret['humanStartsTotal'];
					$mod = $ret['gamesModdedTotal'];
					$admins = $ret['adminMeetingsTotal'];
					echo "Total Appearances: $total </br>";
					echo "Missions Started Zombie/OZ: $zombie </br>";
					echo "Missions Started Human: $human </br>";
					echo "Missions Moderated: $mod </br>";
					echo "Officer Meetings Attended (Since January 2019): $admins </br>";
					echo "<br/><b>NOTE:</b> Data regarding starting side may not be accurate with round-based missions or missions with random OZs. Mission attendance was also not accurately tracked until 2017. Therefore, these numbers may not be 100% accurate. It is impossible to retroactively correct every attendance error from the past. However, if you believe there is an error in your attendance records regarding a recent meeting, please contact an officer.</br></br>";
					
					echo "If you have 5/25/50/100 missions but do not have the corresponding achievement, when you are signed in to your next meeting, the achievement should be awarded.<br/>";
					
					echo "</br><h3>Membership Status</h3></br>";
					//Club members can vote. Nobody else can.
					if(canVote($uid)) {
						echo "You are a member of UMBC Humans vs. Zombies.<br/><br/>";
					}
					else {
						echo "You are not a member of UMBC Humans vs. Zombies (yet).<br/><br/>";
					}
					echo "A UMBC student can become a member of the UMBC Humans vs. Zombies Club by attending 5 games or meetings in the current and previous Spring or Fall semester.<br/><br/>";
					$currentSemesterCount = $ret['appearancesThisTerm'];
					$lastSemesterCount = $ret['appearancesLastTerm'];
					$sum = $currentSemesterCount + $lastSemesterCount;
					echo "You have attended $currentSemesterCount meetings this semester and $lastSemesterCount meetings last semester for a total of $sum.<br/>";
					
					echo "<br/><h2><i>Long game settings</i></h2><br/>";
					
					if($curLongGame){
						$title = $curLongGame['title'];
						if($longPlayerData){
							//Print out their kill codes and other interesting info
							//echo "<h3>Your kill code for $title is {$longPlayerData['mainKill']}, and your feed codes are {$longPlayerData['feedKill1']} and {$longPlayerData['feedKill2']}.<br/><a href=\"myID.php\">My ID Card</a></h3><br/>";
							echo "<h3>Your kill code for $title is {$longPlayerData['mainKill']}.<br/></h3><br/>";
							echo "<br/>";
							//echo "<h3><a href=\"images/debit_card.png\">Click here for the debit card template</a></h3>";
						}
					}
					
					//Populate the OZ opt in field
					?>
					<h3>OZ Opt-In</h3>
					<form action="" method="post">
						<label for="ozIn"><input type="radio" name="ozOpt" value="in" id="ozIn"<?php if($playerData['ozOptIn']==1) echo ' checked="checked"' ?>/>Yes, I want to be in the OZ pool</label>
						<label for="ozOut"><input type="radio" name="ozOpt" value="out" id="ozOut"<?php if($playerData['ozOptIn']==0) echo ' checked="checked"' ?>/>No, I do not want to be in the OZ pool</label>
						
						<br/>
						<br/><b>Please provide a brief reason for why you want to be an OZ below:</b>
						<br/><i>Leaving this field blank will <u>disqualify</u> you as an OZ</i>
						
						<textarea name="ozText" style="width: 410px; height: 100px;"><?php echo preg_replace("/\\\\*'/","'",$playerData['ozParagraph']);?></textarea>
						<br/>
						<input type="submit" name="ozSubmit" value="Update OZ Preferences"/>
					</form>
					<br/>
					<b>NOTE:</b> This field does <b><u>not</u></b> reset Weeklong to Weeklong. <u>You are considered opted out or opted in with your same OZ reason until you change your selection and reason here.</u>
					<br/>
					<br/>
					<br/>
					<?php
					//iDied button 
					if($longPlayerData && $longPlayerData['state']>0){
						?>
						<h3>iDied</h3>
						<p>
						Please click this button ONLY if you were tagged and the kill <b>can't<b> be logged (killcode doesn't work or can't be found).
						DO NOT USE THIS BUTTON TO SUICIDE - you will be caught if you do this!<br/>
						OZs - use this button to reveal yourself after your 2 days or 2 kills are over.
						</p>
						<form action="" method="post">
							<input type="submit" name="iDied" value="iDied"/>
						</form>
						<br/>
						<?php
					}
					//Long game preregistering
					//TODO field population code
					?>
					<!-- <h3>Register for a long game</h3>
					<div><form action="" method="post">
						<?php 
							//longGameRegSelect($uid); 
						?>
					</form></div><br/> -->
					
					<!-- Profile Pictures -->
					<br/><h2><i>Change Your Profile Picture</i></h2><br/>
					<h3>Upload photo</h3>
					<p>Profile pictures cannot contain:
					<ul>
					<li>Gore</li>
					<li>Suggestive imagery</li>
					<li><strike>Memes</strike></li>
					</ul>
					This system is monitored and violation of the above rules will be cause for immediate warning upon the first offence, and
					loss of profile picture privileges upon the second.<br/><br/>
					Note that the optimal image size is 100px by 100px.
					Check the player list to see if your profile picture has updated, if it has not try updating it again.
					</p>
					<form method="post" enctype="multipart/form-data">
					<input type="file" name="image" id="image"/><br/>
					<input type="submit" name="profilePicture" value="Submit"/> 
					</form>
					<br/>
					
					<!-- Select Favorite Achievement possible 500 error location -->
					<br/><h2><i>Achievements</i></h2><br/>
					<h3>Select favorite achievement.</h3>
					<form action="/myProfile.php" method="post">
					Achievement: <select name="achieve">
						<?php 
						generateList();
						?>
					</select><br/>
					<input type="submit" name="favoriteAchieve" value="Submit"></input>
					</form>
					<br/>
					<br/>
					
					<h3>Current favorite achievement</h3>
					<?php displayFavAchievement(); ?>
					<br/>
					
					<br/>
					<h3>Currently Earned Achievements</h3>
					<?php printAchieveTable();?>
					
				<?php
				}else{
					echo "Please sign in to see your profile.";
				}
			}//else{
				//echo "Access to this page has been temporarily restricted to administrator-only while undergoing maintenance.";
			//}
			?>
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
