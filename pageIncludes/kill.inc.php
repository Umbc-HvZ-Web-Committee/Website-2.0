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
		$timeOfFeed = "";
		$hoursGiven = "0";
		$gameID = getCurrentLongGame();
		$gameID = $gameID['gameID'];
		$newKill = false;

// 		$settings = get_settings(); //I don't think this is necessary... Whoever said this was right. Check line 7!
		$me = $_SESSION['uid'];
		$ret = mysql_oneline("SELECT `state` from `long_players` WHERE `playerID`='$me' AND `gameID`='$gameID';");
		
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

		//Check QR validity
		if(substr($qr, 0, 2)!="MK" && substr($qr, 0, 2)!="FK"){
			$GLOBALS['killNotification'] = "That's not a valid code. Valid codes start with 'MK' or 'FK', yours started with '".substr($eid, 0, 2)."'.<br>";
			break;
		}
		
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
		
		//Make sure that an OZ's killcode is not being logged
		$ret = mysql_oneline("SELECT * FROM `long_players` WHERE `playerID` = $uid;");
		if($ret['state'] == 2 || $ret['state'] == -2) {
			$GLOBALS['killNotification'] =  "That player is an OZ. You cannot log their killcode because OZs cannot be killed. If you tagged an OZ, you will not be awarded credit for a kill. I don't make the rules, I just enforce them.";
			echo "\a";
			break;
		}
		
		//Log the location of the kill if it is specified
		$killLocation = requestVar("killLocation");
		mysql_query("UPDATE `long_players` SET `killLocation` = '$killLocation' WHERE `playerID`='$uid' AND `gameID`='$gameID';");
		
		
		/*
		//This gets the time of feed, or sets it to now if it hasn't been logged yet.
		$ret = mysql_oneline("SELECT COUNT(*) AS cnt, `timeOfKill` FROM `long_feeds` WHERE `whoDied`='$uid' AND `gameID`='$gameID' GROUP BY `timeOfKill`;");
		if($ret['cnt']>0){
			//all should have same time, doesn't matter which one we pull
			$timeOfFeed = $ret['timeOfFeed'];
			$freshlyDead = false;
		}else{
			$timeOfFeed = date_create()->format('Y-m-d H:i:s');
			$freshlyDead = true;
		}
		$tofDate = strtotime($timeOfFeed);
		*/
		
		//This does a lot.  It:
		// - verifies that the given kill code is valid
		// - pulls the UID of the valid player
		// - if it's the main kill, gives kill credit to the logger
		// - sets the hours to give to the player (based on kill type)
		// - remove that kill code so it can't be used again
		$GLOBALS['killNotification'] .= "<h3>Kill logged.</h3><br>";
		$ret = mysql_oneline("SELECT COUNT(*) AS cnt, `playerID`, `state`, isOnHitlist FROM `long_players` WHERE `mainKill`='$qr' AND `gameID`='$gameID' GROUP BY `playerID`, `state`, `isOnHitlist`;");
		$pointsToGive = 0;
		$hitlist = false;
		if($ret['state']==1) {
			$newKill = true;
		}
		if($ret['cnt']==1){
			if($ret['state']==3){
				//medkit, so feed, but don't give kill point, and leave state as medkit.
				$hoursGiven = $settings['hoursPerMedkit'];
				mysql_query("UPDATE `long_players` SET `mainKill`='' WHERE `playerID`='$uid' AND `gameID`='$gameID';");
			}else{
				//Commenting out point-based and hitlist-based code
				/*
				$mainKill = 1;
				//For a main kill, give half the human's points or 2, whichever is greater.
				$pointsToGive = 2;
				$cur = mysql_oneline("SELECT SUM(pointsGiven) points FROM long_points WHERE playerID='$uid'");
				$points = $cur['points'];
				if($points/2>$pointsToGive) $pointsToGive=$points/2;
				
				//If the person killed is on the hitlist, and this is a main feed, then notify the admins
				if($ret['isOnHitlist']==1){
					$tmp = mysql_oneline("SELECT fname, lname FROM users WHERE UID='$me'");
					$killerName = $tmp['fname']." ".$tmp['lname'];
					$cur = mysql_oneline("SELECT fname, lname FROM users WHERE UID='$uid'");
					$deadName = "{$cur['fname']} {$cur['lname']}";
					$cur = mysql_oneline("SELECT SUM(pointsGiven) points FROM long_points WHERE playerID='$uid'");
					$points = $cur['points'];
					mail("benharris5@gmail.com", "$killerName killed $deadName, who was on the hitlist!", "$killerName just killed $deadName, who was on the hitlist with $points points when they died.  Congrats to them!\n\nThis is an automated notice, please yell at Ben if you think this is wrong/spam.");
					mail("umbchvzofficers@gmail.com", "$killerName killed $deadName, who was on the hitlist!", "$killerName just killed $deadName, who was on the hitlist with $points points when they died.  Congrats to them!\n\nThis is an automated notice, please yell at Ben if you think this is wrong/spam.");
					$GLOBALS['killNotification'] = "<h3>Kill logged. You bagged a human on the hitlist, congrats!</h3><br>";
					$pointsToGive += 1;
					$hitlist = true;
				}
				*/
				
				mysql_query("UPDATE `long_players` SET `kills`=`kills`+1 WHERE `playerID`='$me' AND `gameID`='$gameID';");
				mysql_query("UPDATE `users` SET `lifetimeKills`=`lifetimeKills`+1 WHERE `uid`='$me';");
				mysql_query("UPDATE `long_players` SET `killerID`='$me' WHERE `playerID`='$uid' AND `gameID`='$gameID';"); 
				//$hoursGiven = $settings['hoursPerMainFeed'];
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
					//If the person killed is on the hitlist, then give a bonus point and a pat on the back
					/*
					if($ret['isOnHitlist']==1){
						$GLOBALS['killNotification'] = "<h3>Kill logged. You bagged a human on the hitlist, congrats!</h3><br>";
						$pointsToGive += 1;
						$hitlist = true;
					}
					*/
				}
			}else{
				$ret = mysql_oneline("SELECT COUNT(*) AS cnt, `playerID`, `state`, isOnHitlist FROM `long_players` WHERE `feedKill2`='$qr' AND `gameID`='$gameID' GROUP BY `playerID`, `state`, `isOnHitlist`;");
				if($ret['cnt']==1){
					if($ret['state']==3){
						//medkit
						$GLOBALS['killNotification'] .= "LOL THAT'S A DUMMY MEDKIT CODE, ENJOY YOUR FAKE BRAIN!<br>";
						break;
					}else{
						/*
						mysql_query("UPDATE `long_players` SET `feedKill2`='', `state`=-1 WHERE `playerID`='$uid' AND `gameID`='$gameID';");
						$hoursGiven = $settings['hoursPerFeed'];
						$pointsToGive = 1;
						//If the person killed is on the hitlist, then give a bonus point and a pat on the back
						if($ret['isOnHitlist']==1){
							$GLOBALS['killNotification'] = "<h3>Kill logged. You bagged a human on the hitlist, congrats!</h3><br>";
							$pointsToGive += 1;
							$hitlist = true;
						}
						*/
					}
				}else{
					$GLOBALS['killNotification'] .= "That isn't a valid kill code, or that killcode has already been claimed.<br>";
					break;
				}
			}
		}
		$notes = "";
		//And finally, commit all that.
		/*
		mysql_query("INSERT INTO `long_feeds` (`gameID`, whoDied`, `whoFed`, `timeOfKill`, `wasMainKill`, `hoursGiven`, `notes`) "
		."VALUES ('$gameID', '$uid', '$me', '$timeOfFeed', $mainKill, $hoursGiven, '$notes')");
		
		//Give points if applicable
		if($pointsToGive>0){
			$ret = mysql_oneline("SELECT CONCAT(fname, ' ', lname) name FROM users WHERE UID='$uid'");
			if($hitlist){
				//mysql_query("INSERT INTO long_points(gameID, playerID, pointsGiven, reason) VALUES ('$gameID', '$me', $pointsToGive, 'Killed {$ret['name']} + hitlist bonus')");
			}else{
				//mysql_query("INSERT INTO long_points(gameID, playerID, pointsGiven, reason) VALUES ('$gameID', '$me', $pointsToGive, 'Killed {$ret['name']}')");
			}
		}
		*/
		
		echo "your kill has been logged";
		
		//Now update the two affected players.
		update($uid, $gameID, "killed");
		if($mainKill==1) update($me, $gameID, "madeKill");
		//else update($me, $gameID, "fed");
		
		//Update the death timer of the person who was fed, and set it for the killed if they're freshly dead.
		
		// Uncomment this line only for starve based games 
		// **THIS FUNCTION HAS NOT BEEN UPDATED AND WILL SCREW UP THE 'longest Day Survived' COLUMN IF USED AS IS
		//setDeathTimer($gameID, $me, $hoursGiven);
		if($freshlyDead){
			//setDeathTimer($gameID, $uid, $settings['hoursTillStarve'], $newKill);
		}
		
		$qr = "";
		$notes = "";
	}while(false);
}
?>
