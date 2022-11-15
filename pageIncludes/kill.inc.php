<?php
require_once('includes/util.php');
load_config('config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('includes/loginUpdate.php');
$settings = get_settings();

if(array_key_exists("submit", $_POST) && isLoggedIn()){
	include 'includes/update.php';
	do{ //allows for easy breaking
		$qr = requestVar("killID");
		$uid = "";
		$mainKill = 0;
		$gameID = getCurrentLongGame();
		$gameID = $gameID['gameID'];

		$me = $_SESSION['uid'];
		$ret = mysql_oneline("SELECT `state` from `long_players` WHERE `playerID`='$me' AND `gameID`='$gameID';");
		
		mysql_query("UPDATE `users` SET `attendedPregame` = '0' WHERE `UID` = 'US003kd';");
		mysql_query("UPDATE `users` SET `attendedPregame` = `attendedPregame`+1 WHERE `UID` = 'US003kd';");
		
		if($ret['state']==2){
			//OMG UR THE OZ!!1!
			//log this to the OZ dummy so you don't give identity away (ask an old-timer about the Avatar weeklong from Fall 2017)
			$meMaybe = "OZ".substr($me, 2);
			//but make sure that the OZ isn't revealed before committing to that.
			$ret = mysql_oneline("SELECT COUNT(*) AS cnt FROM `long_players` WHERE `playerID`='$meMaybe' AND `gameID`='$gameID';");
			if($ret['cnt']==1) $me = $meMaybe;
		}elseif($ret['state']<0){
			//pass
		}else{
			$GLOBALS['killNotification'] = "You're not a zombie, you can't log a kill!<br>";
			break;
		}
		mysql_query("UPDATE `users` SET `attendedPregame` = `attendedPregame`+1 WHERE `UID` = 'US003kd';");

		//Check QR validity
		if(substr($qr, 0, 2)!="MK"){
			$GLOBALS['killNotification'] = "That's not a valid code. Valid codes start with 'MK', yours started with '".substr($eid, 0, 2)."'.<br>";
			break;
		}
		mysql_query("UPDATE `users` SET `attendedPregame` = `attendedPregame`+1 WHERE `UID` = 'US003kd';");
		
		//Make sure that if the player is dead, logging this kill doesn't rez them by accident
		//(that is, if the kill was made before they died, log it; if it was made after they died,
		//ignore it.)
		
		$ret = mysql_oneline("SELECT COUNT(*) AS cnt, `playerID` FROM `long_players` WHERE (`mainKill`='$qr' OR `feedKill1`='$qr' OR `feedKill2`='$qr') AND `gameID`='$gameID' GROUP BY `playerID`;");
		$cnt = $ret['cnt'];
		if($cnt==0){
			$GLOBALS['killNotification'] = "I can't find that kill code. Check your typing. (The only possible characters in a kill code are '".$killChars."')";
			$qr = "";
			break;
		}
		$uid = $ret['playerID'];
		mysql_query("UPDATE `users` SET `attendedPregame` = `attendedPregame`+1 WHERE `UID` = 'US003kd';");
		
		
		
		//Make sure a player is not logging their own killcode
		if($me == $uid) { //If the user logging is the same as the user with that killcode, then we have a culprit!
			$name = mysql_oneline("SELECT fname, lname FROM `users` WHERE `UID` = '$uid';");
			$first = $name['fname'];
			$last = $name['lname'];
			
			//You could have stopped this
			$msg = "Holy Fuck! \n\n$first $last just tried to log their OWN KILLCODE as a kill. This action has been blocked and is being reported to the moderators through this email. I highly recommend that you stab them exactly 31 times for this egregious action. Also, thank Kyle for finding this bug, not telling anyone about it because he couldn't fix it, eventually finding a patch, and making this shitpost the result of what has been done.";
			$email = "umbchvzofficers@gmail.com";
			$subject = "Holy Fuck, It Actually Happened";
			mail($email, $subject, $msg);
			
			$GLOBALS['killNotification'] =  "I see what you tried to do there. You thought you could log your OWN KILLCODE and get away with it. YOU HAVE BEEN STOPPED. YOU SHALL NOT PASS. I'M GOING TO TELL THE OFFICERS WHAT YOU DID SO THEY CAN BAN YOU. REEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE";
			echo "\a";
			break;
		}
		mysql_query("UPDATE `users` SET `attendedPregame` = `attendedPregame`+1 WHERE `UID` = 'US003kd';");
		
		//Make sure that an OZ's killcode is not being logged
		$ret = mysql_oneline("SELECT * FROM `long_players` WHERE `playerID` = $uid AND `gameID` = $gameID;");
		if($ret['state'] == 2 || $ret['state'] == -2) {
			$GLOBALS['killNotification'] =  "That player is an OZ. You cannot log their killcode because OZs cannot be killed. If you tagged an OZ, you will not be awarded credit for a kill. I don't make the rules, I just enforce them.";
			echo "\a";
			break;
		}
		mysql_query("UPDATE `users` SET `attendedPregame` = `attendedPregame`+1 WHERE `UID` = 'US003kd';");
		
		//This gets the time of kill in UTC (Not US Eastern Standard/Daylight Time)
		$deathTime = date_create()->format('Y-m-d H:i:s');
	
		$sql = "SELECT `startDate` FROM `long_games` WHERE `gameID`='$gameID';";
		$ret = mysql_oneline($sql);
		$weeklongStart = $ret['startDate'];
		$weeklongStart = date('Y-m-d H:i:s', strtotime($weeklongStart));

		mysql_query("UPDATE `long_players` SET `deathTime`='$deathTime' WHERE `gameID`='$gameID' AND `playerID`='$uid'");
		mysql_query("UPDATE `users` SET `attendedPregame` = `attendedPregame`+1 WHERE `UID` = 'US003kd';");
		
		if($newKill) {
			// Check to see if they've lived longer than they have before
			$dayDead = date('N', strtotime($deathTime));
			$weeklongStart = date('N', strtotime($weeklongStart)) - 1;
			$nowDead = $dayDead - $weeklongStart;
			
			$ret = mysql_oneline("SELECT `longestDaySurvived` FROM `users` WHERE UID='$uid';");
			$currentDayDead = $ret['longestDaySurvived'];
			
			if($nowDead > $currentDayDead) {
				mysql_query("UPDATE `users` SET longestDaySurvived='$nowDead' WHERE UID='$uid';");
			}
		}
		mysql_query("UPDATE `users` SET `attendedPregame` = `attendedPregame`+1 WHERE `UID` = 'US003kd';");

		//Log the kill time and the location of the kill if it is specified
		$killLocation = requestVar("killLocation");
		mysql_query("UPDATE `long_players` SET `killLocation` = '$killLocation', `deathTime` = '$deathTime' WHERE `playerID`='$uid' AND `gameID`='$gameID';");
		//TODO: Time zone conversion from UTC into US EDT and EST
		mysql_query("UPDATE `users` SET `attendedPregame` = `attendedPregame`+1 WHERE `UID` = 'US003kd';");
		
		//This does a lot.  It:
		// - verifies that the given kill code is valid
		// - pulls the UID of the valid player
		// - gives kill credit to the logger
		// - remove that kill code so it can't be used again
		$GLOBALS['killNotification'] .= "<h3>Kill logged.</h3><br>";
		$ret = mysql_oneline("SELECT COUNT(*) AS cnt, `playerID`, `state`, isOnHitlist FROM `long_players` WHERE `mainKill`='$qr' AND `gameID`='$gameID' GROUP BY `playerID`, `state`, `isOnHitlist`;");
		if($ret['state']==1) {
			$newKill = true;
		}
		mysql_query("UPDATE `users` SET `attendedPregame` = `attendedPregame`+1 WHERE `UID` = 'US003kd';");
		if($ret['cnt']==1){
			if($ret['state']==3){
				//medkit, so feed, but don't give kill point, and leave state as medkit.
				$hoursGiven = $settings['hoursPerMedkit'];
				mysql_query("UPDATE `long_players` SET `mainKill`='' WHERE `playerID`='$uid' AND `gameID`='$gameID';");
			}else{
				mysql_query("UPDATE `long_players` SET `kills`=`kills`+1 WHERE `playerID`='$me' AND `gameID`='$gameID';");
				mysql_query("UPDATE `users` SET `lifetimeKills`=`lifetimeKills`+1 WHERE `uid`='$me';");
				mysql_query("UPDATE `long_players` SET `killerID`='$me' WHERE `playerID`='$uid' AND `gameID`='$gameID';"); 
				mysql_query("UPDATE `long_players` SET `mainKill`='', `state`= -1 WHERE `playerID`='$uid' AND `gameID`='$gameID';");
			}
		}else{
			$ret = mysql_oneline("SELECT COUNT(*) AS cnt, `playerID`, `state`, isOnHitlist FROM `long_players` WHERE `feedKill1`='$qr' AND `gameID`='$gameID' GROUP BY `playerID`, `state`, `isOnHitlist`;");
			if($ret['cnt']==1){
				if($ret['state']==3){
					//medkit
					$GLOBALS['killNotification'] .= "LOL THAT'S A DUMMY MEDKIT CODE, ENJOY YOUR FAKE BRAIN!<br>";
					break;
				}else{
					$hoursGiven = $settings['hoursPerFeed'];
					mysql_query("UPDATE `long_players` SET `feedKill1`='', `state`=-1 WHERE `playerID`='$uid' AND `gameID`='$gameID';");
					$pointsToGive = 1;
				}
			}else{
				$ret = mysql_oneline("SELECT COUNT(*) AS cnt, `playerID`, `state`, isOnHitlist FROM `long_players` WHERE `feedKill2`='$qr' AND `gameID`='$gameID' GROUP BY `playerID`, `state`, `isOnHitlist`;");
				if($ret['cnt']==1){
					if($ret['state']==3){
						//medkit
						$GLOBALS['killNotification'] .= "LOL THAT'S A DUMMY MEDKIT CODE, ENJOY YOUR FAKE BRAIN!<br>";
						break;
					}
				}else{
					$GLOBALS['killNotification'] .= "That isn't a valid kill code, or that killcode has already been claimed.<br>";
					break;
				}
			}
		}
		mysql_query("UPDATE `users` SET `attendedPregame` = `attendedPregame`+1 WHERE `UID` = 'US003kd';");
		$notes = "";
		echo "your kill has been logged";
		
		//Now update the two affected players.
		update($uid, $gameID, "killed");
		if($mainKill==1) update($me, $gameID, "madeKill");
		
		$qr = "";
		$notes = "";
	}while(false);
}
?>
