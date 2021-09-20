<?php
require_once('../includes/util.php');
require_once('../includes/update.php');
load_config('../config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('../includes/loginUpdate.php');
$settings = get_settings();

$curLongGame = getCurrentLongGame();

//Player state constants
$OZ_TAG = -2;
$ZOMBIE_TAG = -1;
$OTHER_TAG = 0;
$HUMAN_TAG = 1;
$OZ_HIDDEN_TAG = 2;
$MODERATOR_TAG = 4;
$TERMINAL_TAG = -4;
$GENERAL_PLAYER_TAG = -3;

//Meeting type constants
$MEETING_MISSION = 0;
$MEETING_ADMIN = 1;
$MEETING_OTHER = 2;
$MEETING_NOMINAL = 3;

//Uncomment if there are problems
//echo("Hello!");
//var_dump($_SESSION);
//var_dump($_REQUEST);

if($_SESSION['isAdmin']>=1){
	if(isset($_REQUEST['submit'])){ //Used for submit requests. As of 01/22/2020 this is the only thing going on with this page
		$func = $_REQUEST['submit'];
		//echo($func);
		if($func=="Sign player in"){
			$playerID = requestVar('playerID');
			$meeting = requestVar('meetingSelect');
			$ret = getUID($playerID);
			$state = requestVar('state');
			//$meetingType = requestVar('meetingType');
			if(!$curLongGame){
				$unregistered = isset($_REQUEST['unregistered']);
			}else{
				$unregistered = false;
				//Determining player's state. Valid states are defined in the state constants
				if($ret /* If $ret is not null, the player has a registered account*/){
					if ($state == $GENERAL_PLAYER_TAG) {
						$state = mysql_oneline("SELECT `state` FROM `long_players` WHERE `gameID` = '{$curLongGame['gameID']}' AND `playerID` = '{$ret['UID']}';");
						$state = $state['state'];
					}else if($state){
						$state = $state['state'];
					}else{
						$state = $OTHER_TAG;
					}
				}else{
					$state = $OTHER_TAG;
				}
			}
			
			$meetingType = mysql_oneline("SELECT * FROM `meeting_list` WHERE `meetingID` = '$meeting'");
			$meetingType = $meetingType['meetingType'];
			//echo $meetingType;
			if ($meetingType != $MEETING_MISSION) { 
				//If the meeting is not a mission, there is no need for differentiation between states
				//so the state for everyone will be 0
				//echo "Hi lol";
				$state = $OTHER_TAG;
			}
			
			if(!$unregistered){
				//get UID, assuming you can
				if($ret){
					//found user
					$uid = $ret['UID'];
					$name = $ret['fname']." ".$ret['lname'];
					
					$ret2 = mysql_oneline("SELECT * FROM `users` WHERE `UID` = '$uid';");
					$totalAttendance = $ret2['appearancesTotal'] - $ret2['adminMeetingsTotal'];
					$attendance = $ret2['appearancesThisTerm'] + $ret2['appearancesLastTerm'];
					//echo "BEFORE ATTENDENCE: $attendance";
					
					
					//check if user is already signed in
					$ret = mysql_oneline("SELECT COUNT(*) AS cnt FROM meeting_log WHERE UID='$uid' AND meetingID='$meeting';");
					
					$isPregame = requestVar('pregame');
					if($isPregame) {
						mysql_query("UPDATE `users` SET `attendedPregame`= 1 WHERE `UID` = '$uid';");
					}
					
					$isHoliday = requestVar('holiday');
					if($isHoliday) {
						updateAchieves($uid, null, "holidayMission");
					}
					
					if($ret['cnt']==0){
						//add user to meeting
						mysql_query("INSERT INTO meeting_log(meetingID, UID, startState) VALUES ('$meeting','$uid',$state);");
						$GLOBALS['meetingMessage']="Sign in successful. Hello $name!";
	
						/*
						//Increment attendance counter
						$attendance = $attendance + 1;
						$totalAttendance = $totalAttendance + 1;
						
						*/
						
						//Uncomment for special missions; set the function in achievementUpdateFunctions.php to award proper achievement
						//updateAchieves($uid, null, "specialMission");
						
						
						//Give attendance-based achievements
						if($attendance >= 5) {
							updateAchieves($uid, null, "attendance0");
						}
						if($totalAttendance >= 25) {
							updateAchieves($uid, null, "attendance1");
						}
						if($totalAttendance >= 50) {
							updateAchieves($uid, null, "attendance2");
						}
						if($totalAttendance >= 100) {
							updateAchieves($uid, null, "attendance3");
						}
						
						
						/*
						//echo "AFTER ATTENDENCE: $attendance";
						mysql_query("UPDATE `users` SET `appearancesThisYear`='$attendance' WHERE `UID` = '$uid';");
						mysql_query("UPDATE `users` SET `meetingsAttended`='$totalAttendance' WHERE `UID` = '$uid';");
						*/
						
						if($curLongGame) {
							$longGamePlayer = mysql_oneline("SELECT * FROM `long_players` WHERE `gameID`='{$curLongGame['gameID']}' AND `playerID`='$uid';");
							$missionsPlayed = $longGamePlayer['missionsPlayed'];
							$missionsPlayed = $missionsPlayed + 1;
                            mysql_query("UPDATE `long_players` SET `missionsPlayed`='$missionsPlayed' WHERE `gameID`='{$curLongGame['gameID']}' AND `playerID`='$uid';");
						}
						
						//Check vaccine status
						//Please let me delete this block in the future please
						
						$vaccineStatus = $ret2['vaccineStatus'];
						if($vaccineStatus == '0') {
							$GLOBALS['meetingMessage']=$GLOBALS['meetingMessage']." This player has reported as unvaccinated to the officers. Please make sure $name wears a mask as required.";
						} else if($vaccineStatus == '1') {
							$GLOBALS['meetingMessage']=$GLOBALS['meetingMessage']." This player has received only one dose of the coronavirus vaccine.";
						} else if($vaccineStatus >= '2') {
							//$GLOBALS['meetingMessage']=$GLOBALS['meetingMessage']." This player is fully-vaccinated.";
						} else {
							$GLOBALS['meetingMessage']=$GLOBALS['meetingMessage']." This player has not reported vaccine status to the officer board. Please follow-up on this.";
						}
						
					}else{
						//update user sign in
						mysql_query("UPDATE meeting_log SET startState=$state WHERE UID='$uid' AND meetingID='$meeting';");
						$GLOBALS['meetingMessage']="$name is already signed in, but their sign in state has been updated.";
						
						//Uncomment line below for special missions, set the function in achievementUpdateFunctions.php to award proper achievement
						//updateAchieves($uid, null, "specialMission");
					}
				}else{
					//couldn't find
					$GLOBALS['meetingMessage']="Sorry, I can't find someone that matches that ID.";
				}
			}else{
				//just drop the user in
				mysql_query("INSERT INTO meeting_unregistered_log(meetingID, playerName, startState) VALUES ('$meeting','$playerID',$state);");
				$GLOBALS['meetingMessage']="Unregistered sign in successful. Hello $playerID!";
			}
		}elseif($func=="Create new meeting"){
			$meetingType = requestVar('meetingType');
			if($meetingType == "admin") {
				$meetingName = "[Admin] ";
				$type = "1";
			}
			else if($meetingType == "mission") {
				$meetingName = "[Mission] ";
				$type = "0";
			}
			else if($meetingType == "other") {
				$meetingName = "[Other] ";
				$type = "2";
			}
			else if($meetingType == "nominal") {
				$meetingName = "[Nominal] ";
				$type = "3";
			}
			if(requestVar('meetingName') == "") {
				$meetingName = $meetingName."Unnamed Meeting";
			}
			else {
				$meetingName = $meetingName.requestVar('meetingName');
			}
			$meetingID = getNextHighestID('meeting_list', 'meetingID', 'ME');
			mysql_query("INSERT INTO meeting_list(meetingID,meetingName,creationDate,meetingType) VALUES ('$meetingID','$meetingName',CURDATE(),'$type');");
			//Uncomment if there are problems
			//echo("INSERT INTO meeting_list(meetingID,meetingName,creationDate,meetingType) VALUES ('$meetingID','$meetingName',CURDATE(),'$type');");
			//echo (mysql_error());
			if($curLongGame){
				mysql_query("INSERT INTO long_meetings(gameID, meetingID) VALUES ('{$curLongGame['gameID']}','$meetingID');");
				$GLOBALS['meetingMessage']="Meeting \"$meetingName\" created and added to current long game.";
			}else{
				$GLOBALS['meetingMessage']="Meeting \"$meetingName\" created.";
			}
		}elseif($func=="Resolve meeting"){
			$meetingID = requestVar('meetingSelect');
			$meetingWinner = requestVar('state');
			
			
			$ctr = $HUMAN_TAG;
			
			$zombieVictory = false; //used to award patient zero
			if($meetingWinner == -1) {
				$zombieVictory = true;
			}
			
			while($ctr != $TERMINAL_TAG) {
				//DEBUG MAIL TEMPLATE: mail($email, $subject, $msg);
				//echo "Running outer loop using side number $ctr ";
				$sql = "SELECT * FROM `meeting_log` WHERE `MeetingID` = '$meetingID' AND `startState`='$ctr';";
				$query = mysql_query($sql);
				while($ret = mysql_fetch_assoc($query)) {
					$UID = $ret['UID'];
					//echo "Running middle loop using side number $ctr and UID $UID";
					//Uncomment line below for special missions, set the function in achievementUpdateFunctions.php to award proper achievement
					//updateAchieves($UID, null, "specialMission");
					$sql2 = "SELECT * FROM `users` WHERE `UID` = '$UID'";
					$query2 = mysql_query($sql2);
					$iterations = 0;
					//echo "<br/>";
					//while($ret2 = mysql_fetch_assoc($query2) && iterations == 0) {
						$iterations += 1;
						$ret3 = mysql_oneline("SELECT * FROM `meeting_list` WHERE `meetingID` = '$meetingID';");
						$meetingID = $ret3['meetingID'];
						//echo "Running inner loop using side number $ctr and UID $UID and meetingID $meetingID";
						//echo $query2;
						//$meetingsTerm = $query2['appearancesThisTerm']; //TODO: Check to see if this fixes it and apply elsewhere						
						//$meetingsTerm = $meetingsTerm + 1;	
						//echo " E";						
						//$meetingsTotal = $query2['appearancesTotal'];						
						//$meetingsTotal = $meetingsTotal + 1;
						//echo " E";
						mysql_query("UPDATE `users` SET `appearancesTotal` = `appearancesTotal` + 1 WHERE `UID`='$UID';");
						mysql_query("UPDATE `users` SET `appearancesThisTerm` = `appearancesThisTerm` + 1 WHERE `UID`='$UID';");
						//echo " E";
						if($ret3['meetingType'] == $MEETING_MISSION) {
							//echo " E";
							if($ret['startState'] == $HUMAN_TAG) {
								//$meetingsTotal = $query2['humanStartsTotal'];
								//$meetingsTotal = $meetingsTerm + 1;
								//$meetingsTerm = $query2['humanStartsThisTerm'];
								//$meetingsTerm = $meetingsTotal + 1;
								mysql_query("UPDATE `users` SET `humanStartsTotal` = `humanStartsTotal` + 1 WHERE `UID`='$UID';");
								mysql_query("UPDATE `users` SET `humanStartsThisTerm` = `humanStartsThisTerm` + 1 WHERE `UID`='$UID';");
								//echo " E";
							} 
							elseif($ret['startState'] == $OZ_HIDDEN_TAG || $ret['startState'] == $ZOMBIE_TAG || $ret['startState'] == $OZ_TAG) {
								//$meetingsTotal = $ret2['zombieStartsTotal'];
								//$meetingsTotal = $meetingsTerm + 1;
								//$meetingsTerm = $ret2['zombieStartsThisTerm'];
								//$meetingsTerm = $meetingsTotal + 1;
								mysql_query("UPDATE `users` SET `zombieStartsTotal` = `zombieStartsTotal` + 1 WHERE `UID`='$UID';");
								mysql_query("UPDATE `users` SET `zombieStartsThisTerm` = `zombieStartsThisTerm` + 1 WHERE `UID`='$UID';");
								if ($zombieVictory == true) {
									updateAchieves($UID, null, "patientZero");
								}	
							}
							elseif($ret['startState'] == $MODERATOR_TAG) {
								//$meetingsTotal = $ret2['gamesModdedTotal'];
								//$meetingsTotal = $meetingsTerm + 1;
								//$meetingsTerm = $ret2['gamesModdedThisTerm'];
								//$meetingsTerm = $meetingsTotal + 1;
								mysql_query("UPDATE `users` SET `gamesModdedTotal` = `gamesModdedTotal` + 1 WHERE `UID`='$UID';");
								mysql_query("UPDATE `users` SET `gamesModdedThisTerm` = `gamesModdedThisTerm` + 1 WHERE `UID`='$UID';");
							}
						}
						else if ($ret3['meetingType'] == $MEETING_ADMIN) {
							$meetingsTotal = $ret2['adminMeetingsTotal'];
							$meetingsTotal = $meetingsTotal + 1;
							$meetingsTerm = $ret2['adminMeetingsThisTerm'];
							$meetingsTerm = $meetingsTerm + 1;
							mysql_query("UPDATE `users` SET `adminMeetingsTotal` = `adminMeetingsTotal` + 1 WHERE `UID`='$UID';");
							mysql_query("UPDATE `users` SET `adminMeetingsThisTerm` = `adminMeetingsThisTerm` + 1 WHERE `UID`='$UID';");
						}
						else if ($ret3['meetingType'] == $MEETING_OTHER) {
							continue;
						}
						else if ($ret3['meetingType'] == $MEETING_NOMINAL) {
							mysql_query("UPDATE `users` SET `appearancesTotal` = `appearancesTotal` - 1 WHERE `UID`='$UID';");
						}
					//}
				}
				if($ctr == $HUMAN_TAG) { //1
					$ctr = $OZ_HIDDEN_TAG; //2
				} elseif($ctr == $OZ_HIDDEN_TAG) { //2
					$ctr = $ZOMBIE_TAG; //-1
				} elseif($ctr == $ZOMBIE_TAG) { //-1
					$ctr = $OZ_TAG; //-2
				} elseif($ctr == $OZ_TAG) { //-2
					$ctr = $MODERATOR_TAG; //4
				} elseif($ctr == $MODERATOR_TAG) { //4
					$ctr = $OTHER_TAG; //0
				} elseif($ctr == $OTHER_TAG) { //0
					$ctr = $TERMINAL_TAG; //-4
				}
			}
			
			mysql_query("UPDATE `meeting_list` SET `resolution` = '$meetingWinner', `isResolved` = '1' WHERE `meetingID` = '$meetingID';");
			
			$player = mysql_query("SELECT * FROM `users` WHERE `UID` = '$UID';");
			$missions = $player['appearancesTotal'] - $player['adminMeetingsTotal'];
			$attendance = $player['appearancesThisTerm'] + $player['appearancesLastTerm'];
			
			//Give attendance-based achievements
			if($attendance >= 5) {
				updateAchieves($uid, null, "attendance0");
				//echo "checking $uid for achievement membership\n";
			}
			if($missions >= 25) {
				updateAchieves($uid, null, "attendance1");
				//echo "checking $uid for achievement experienced\n";
			}
			if($missions >= 50) {
				updateAchieves($uid, null, "attendance2");
				//echo "checking $uid for achievement war veteran\n";
			}
			if($missions >= 100) {
				updateAchieves($uid, null, "attendance3");
				//echo "checking $uid for achievement war expert\n";
			}
			echo " A Solid E!";
			
		}elseif($func=="Unresolve meeting"){ //Does not work
			mysql_query("UPDATE `meeting_list` SET `resolution`='NULL', `isResolved`='0' WHERE `meetingID`='$meetingID';");
			
			$sql = "SELECT * FROM `meeting_log` WHERE `MeetingID` = '$meetingID' AND `startState`='$ctr'";
			$query = mysql_query($sql);
			while($ret = mysql_fetch_assoc($query)) {
				$UID = $ret['UID'];
				$sql2 = "SELECT * FROM `users` WHERE `UID` = '$UID'";
				$query2 = mysql_query($sql2);
				//while($ret2 = mysql_fetch_assoc($query2)) {
					$ret3 = mysql_oneline("SELECT * FROM `meeting_list` WHERE `meetingID` = '$meetingID';");
					mysql_query("UPDATE `users` SET `appearancesTotal`=`appearancesTotal`-1 WHERE `UID`='$uid';");
					mysql_query("UPDATE `users` SET `appearancesThisTerm`=`appearancesThisTerm`-1 WHERE `UID`='$uid';");
					if($ret3['missionType'] == 0) {
						if($ret['startState'] == $HUMAN_TAG) {
							mysql_query("UPDATE `users` SET `humanStartsTotal`=`humanStartsTotal`-1 WHERE `UID`='$uid';");
							mysql_query("UPDATE `users` SET `humanStartsThisTerm`=`humanStartsTerm`-1 WHERE `UID`='$uid';");
						} 
						elseif($ret['startState'] == $OZ_HIDDEN_TAG || $ret['startState'] == $ZOMBIE_TAG || $ret['startState'] == $OZ_TAG) {
							mysql_query("UPDATE `users` SET `zombieStartsTotal`=`zombieStartsTotal`-1 WHERE `UID`='$uid';");
							mysql_query("UPDATE `users` SET `zombieStartsThisTerm`=`zombieStartsThisTerm`-1 WHERE `UID`='$uid';");
						}
						elseif($ret['startState'] == $MODERATOR_TAG) {
							mysql_query("UPDATE `users` SET `gamesModdedTotal`=`gamesModdedTotal`-1 WHERE `UID`='$uid';");
							mysql_query("UPDATE `users` SET `gamesModdedThisTerm`=`gamesModdedThisTerm`-1 WHERE `UID`='$uid';");
						}
					}
					elseif ($ret['missionType'] == 1) {
						mysql_query("UPDATE `users` SET `adminMeetingsTotal`=`adminMeetingsTotal`-1 WHERE `UID`='$uid';");
						mysql_query("UPDATE `users` SET `adminMeetingsThisTerm`=`adminMeetingsThisTerm`-1 WHERE `UID`='$uid';");
					}
				//}
			}
			if($ctr == $HUMAN_TAG) { //1
				$ctr = $OZ_HIDDEN_TAG; //2
			} elseif($ctr == $OZ_HIDDEN_TAG) { //2
				$ctr = $ZOMBIE_TAG; //-1
			} elseif($ctr == $ZOMBIE_TAG) { //-1
				$ctr = $OZ_TAG; //-2
			} elseif($ctr == $OZ_TAG) { //-2
				$ctr = $MODERATOR_TAG; //4
			} elseif($ctr == $MODERATOR_TAG) { //4
				$ctr = $OTHER_TAG; //0
			} elseif($ctr == $OTHER_TAG) { //0
				$ctr = $TERMINAL_TAG; //-4
			}
			
		}elseif($func=="View Attendance"){
			
			$meetingID = requestVar('meetingSelect');
			
			$meeting = mysql_oneline("SELECT * FROM `meeting_list` WHERE `meetingID`='$meetingID'");
			$meetingWinner = $meeting['resolution'];
			
			$ctr = $HUMAN_TAG;
			$zombieVictory = false; //used to award patient zero
			$hasOZ = false;
			if($meetingWinner == 1) {
				$meetingWinner = "Human Victory!";
			} else if ($meetingWinner == -1) {
				$meetingWinner = "Zombie Victory!";
				$zombieVictory = true;
			} else {
				$meetingWinner = "Other Victory";
			}
			
			$GLOBALS['meetingMessage'] = $meeting['meetingName'];
			$GLOBALS['meetingMessage'] = $GLOBALS['meetingMessage']."<br/><br/>$meetingWinner<br/><br/>";
			$humanCtr = 0;
			$zombieCtr = 0;
			$otherCtr = 0;
			$modCtr = 0;
			$nameStr = "";
			while($ctr != $TERMINAL_TAG) {
				$sql = "SELECT * FROM `meeting_log` WHERE `MeetingID` = '$meetingID' AND `startState`='$ctr' ORDER BY `UID`;";
				$query = mysql_query($sql);
				while($ret = mysql_fetch_assoc($query)) {
					$UID = $ret['UID'];
					//Uncomment line below for special missions, set the function in achievementUpdateFunctions.php to award proper achievement
					//updateAchieves($UID, null, "specialMission");
					$sql2 = "SELECT * FROM `users` WHERE `UID` = '$UID'";
					$query2 = mysql_query($sql2);
					while($ret2 = mysql_fetch_assoc($query2)) {
						$name = $ret2['fname']." ".$ret2['lname']." - ";
						if($ret['startState'] == $HUMAN_TAG) {
							$humanCtr += 1;
							$name = $name."Human<br/>";
						} 
						elseif($ret['startState'] == $OZ_HIDDEN_TAG) {
							$hasOZ = true;
							$humanCtr += 1;
							$zombieCtr += 1;
							$name = $name."Human<br/>";
							if ($zombieVictory == true) {
								updateAchieves($UID, null, "patientZero");
							}	
						} elseif($ret['startState'] == $ZOMBIE_TAG || $ret['startState'] == $OZ_TAG) {
							$zombieCtr += 1;
							$name = $name."Zombie<br/>";
							if ($zombieVictory == true) {
								updateAchieves($UID, null, "patientZero");
							}						
						} elseif($ret['startState'] == $MODERATOR_TAG) {
							$modCtr += 1;
							$name = $name."Moderator<br/>";
						} else {
							$otherCtr += 1;
							$name = $name."Other<br/>";
						}
						$nameStr = $nameStr.$name;
					}
				}
				if($ctr != $OZ_HIDDEN_TAG || $ctr != $OZ_TAG) { //Condition needed to make sure OZs are not separated from their player-appeared affiliation, since this includes the line break
					//I don't know why this fixes it but somehow it does idk php is weird 
				}
				if($ctr == $HUMAN_TAG) { //1
					$ctr = $OZ_HIDDEN_TAG; //2
				} elseif($ctr == $OZ_HIDDEN_TAG) { //2
					$ctr = $ZOMBIE_TAG; //-1
					$nameStr = $nameStr."<br/>";
				} elseif($ctr == $ZOMBIE_TAG) { //-1
					$ctr = $OZ_TAG; //-2
				} elseif($ctr == $OZ_TAG) { //-2
					$ctr = $MODERATOR_TAG; //4
					$nameStr = $nameStr."<br/>";
				} elseif($ctr == $MODERATOR_TAG) { //4
					$ctr = $OTHER_TAG; //0
					$nameStr = $nameStr."<br/>";
				} elseif($ctr == $OTHER_TAG) { //0
					$ctr = $TERMINAL_TAG; //-4
					$nameStr = $nameStr."<br/>";
				}
			}
			
			$GLOBALS['meetingMessage'] = $GLOBALS['meetingMessage']."$humanCtr Humans<br/>$zombieCtr Zombies<br/>$otherCtr Other<br/>$modCtr Mods<br/>";
			$total = $humanCtr + $zombieCtr + $otherCtr + $modCtr;
			$GLOBALS['meetingMessage'] = $GLOBALS['meetingMessage']."$total Total<br/>";
			
			if($hasOZ) {
				$GLOBALS['meetingMessage'] = $GLOBALS['meetingMessage']."Counting the disguised OZ(s) as both human and zombie<br/>";
			}
			
			$GLOBALS['meetingMessage'] = $GLOBALS['meetingMessage']."<br/>$nameStr<br/><br/>";
			
			//Starter code for unresolving meeting functionality. 
			//TODO: Finish
			/*
			$GLOBALS['meetingMessage'] = $GLOBALS['meetingMessage']."THIS FUNCTIONALITY HAS NOT BEEN TESTED. DO NOT PRESS IF YOU DON'T KNOW WHAT YOU'RE DOING<br/>";
			$GLOBALS['meetingMessage'] = $GLOBALS['meetingMessage']."<input type=\"submit\" name=\"submit\" value=\"Unresolve meeting\"/>";
			*/
		}
	}
}
?>