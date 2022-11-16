<?php
require_once('../includes/util.php');
require_once('../includes/update.php');
require_once('../includes/achievementUpdateFunctions.php');
load_config('../config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('../includes/loginUpdate.php');
$settings = get_settings();

if($_SESSION['isAdmin']>=2){
	if(isset($_REQUEST['submit'])){
		$gameID = requestVar('longGameSelect');
		$playerID = requestVar('playerID');
		//$bandannaNumber = requestVar('bandannaNumber');
		$waiverState = requestVar('waiver');
		//$phoneOptIn = requestVar('phone');
		$phoneOptIn = "no";
		//$phoneNum = requestVar('phoneNum');
		//$carrier = requestVar('carrier');
		
		//get UID, assuming you can
		$ret = getUID($playerID);
		if($ret){
			//found user
			$uid = $ret['UID'];
			//echo $uid;
			//echo "\n";
			$name = $ret['fname']." ".$ret['lname'];
			
			//check if user is already registered
			$retval = mysql_oneline("SELECT COUNT(*) AS cnt FROM long_players WHERE playerID='$uid' AND gameID='$gameID'");
			if($retval['cnt']==0){
				//check if user isn't lying about waiver
				$retval = mysql_oneline("SELECT hasTurnedInWaiver FROM users WHERE UID='$uid'");
				if($waiverState=="newOne" || $retval['hasTurnedInWaiver']==1){
					//actually add them
					
					//if the player is preregistered, use those kill codes; otherwise generate new ones
					/*$prereg = mysql_oneline("SELECT COUNT(*) AS cnt, mainKill, feedKill1, feedKill2 FROM long_preregister WHERE gameID = '$gameID' AND UID='$uid';");
					if($prereg['cnt']==0){*/
						$mainKill = generateRandomID(array("long_players", "long_preregister"), "mainKill", "MK", $killChars);
						$feedKill1 = generateRandomID(array("long_players", "long_preregister"), array("feedKill1","feedKill2"), "FK", $killChars);
						$feedKill2 = generateRandomID(array("long_players", "long_preregister"), array("feedKill1","feedKill2"), "FK", $killChars);
					/*}else{
						$mainKill = $prereg['mainKill'];
						$feedKill1 = $prereg['feedKill1'];
						$feedKill2 = $prereg['feedKill2'];
					//}
					
					
					/*
					//sign out equipment
					$bandannaNumber = "".$bandannaNumber;
					while(strlen($bandannaNumber)<3) $bandannaNumber = '0'.$bandannaNumber;
					$bandanna = "EQBA".$bandannaNumber;
					$result = mysql_query("SELECT 1 FROM `equipment` WHERE EID='$bandanna';");
					if(!(mysql_num_rows($result) > 0)) {
						mysql_query("INSERT INTO equipment(EID, owner, description, loanedTo) VALUES ('$bandanna', 'UMBC HvZ', 'Bandanna', '$uid');");
					} else {
						mysql_query("UPDATE equipment SET loanedTo='$uid' WHERE EID='$bandanna';");
					}
					*/
					
					
					//echo "gameID: ".$gameID."\n";
					//echo "playerID: ".$uid."\n";
					//echo "MK: ".$mainKill."\n";
					//echo "FK1: ".$feedKill1."\n";
					//echo "FK2: ".$feedKill2."\n";
					//echo "inserting... ";
					mysql_query("INSERT INTO long_players(gameID, playerID, mainKill, feedKill1, feedKill2) VALUES ('$gameID','$uid','$mainKill','$feedKill1','$feedKill2');");
					//echo "done!";
					
					
					//set waiver state
					mysql_query("UPDATE users SET hasTurnedInWaiver=1 WHERE UID='$uid'");
					
					//Award "Playing the Long Game" achievement, if necessary
					updateAchieves($uid, null, "longRegister");
					
					//print happy message
					$GLOBALS['submitMessage']="Welcome to the game $name!<br/>";
					/*if($prereg['cnt']==0){
						$GLOBALS['submitMessage'].="Did not preregister.";
					}else{
						$GLOBALS['submitMessage'].="Did preregister.";
						if($prereg['isPrinted']==0){
							$GLOBALS['submitMessage'].="but ID card is not printed.";
						}else{
							$GLOBALS['submitMessage'].="and ID card has been printed!";
						}
						
						//log that they did indeed register for the long game
						mysql_query("UPDATE long_preregister SET isRegistered = 1 WHERE gameID = '$gameID' AND UID='$uid';");
					}
					*/
				}elseif($waiverState == "waiverOnly"){
					mysql_query("UPDATE users SET hasTurnedInWaiver=1 WHERE UID='$uid'");
					$GLOBALS['submitMessage'] = "Your waiver has been submitted";
				}else{
					//error out
					$GLOBALS['submitMessage']="You haven't turned in a waiver yet, please turn one in.";
				}
			}else{
				//error out
				$GLOBALS['submitMessage']="It looks like $name is already registered for that game!";
			}
		}else{
			//couldn't find
			$GLOBALS['submitMessage']="Sorry, I can't find someone that matches that ID.";
		}
	}elseif(isset($_REQUEST['create'])){
		//create new long game
		$title = requestVar("title");
		$startDate = requestVar("startDate");
		$endDate = requestVar("endDate");
		if(preg_match("/[0-1][0-9]\/[0-3][0-9]/", $startDate) && preg_match("/[0-1][0-9]\/[0-3][0-9]/", $endDate)) {
			
			$currTime = date("Y");
			$startDate = $currTime."/".$startDate." 00:00:00";
			$endDate = $currTime."/".$endDate." 23:59:59";
			$startDate = preg_replace("/\//", "-", $startDate);
			$endDate = preg_replace("/\//", "-", $endDate);	
					
			$id = getNextHighestID("long_games", "gameID", "LG");
			
			$sql = "INSERT INTO long_games(title, gameID, startDate, endDate) VALUES ('$title','$id', '$startDate', '$endDate');";
			mysql_query($sql);
			
			
			$GLOBALS['submitMessage']="Game created.";
		} else {
			$GLOBALS['submitMessage']="Please enter dates in the specified format. Preceeding 0's must be added.";
		}
		
	
	}elseif (isset($_REQUEST['close'])) {
		//Close long game
		mysql_query("UPDATE `users` SET `attendedPregame` = '0' WHERE 1;");
		updateAchieves(null, null, "gameEnd");
		/*$longGameID = mysql_query("SELECT MAX(gameID) FROM `long_players`;");
		//$longGameID = $longGameID['MAX(gameID)'];
		$sql = "SELECT `playerID`, `state` FROM `long_players` WHERE `gameID`='$longGameID';";
		$ret = mysql_query($sql);
		while($row = mysql_fetch_assoc($ret)) {
			
			$uid = $row['playerID'];
			$state = $row['state'];
			if($state > 0) {
				mysql_query("UPDATE `users` SET longestDaySurvived=5 WHERE uid='$uid';");
			}
			//mysql_query("UPDATE `users` SET `appearancesThisYear`=`appearancesThisYear`+5 WHERE uid='$uid'");
			
			
		}*/
		
		$time = time();
		$timestamp = date('Y-m-d H:i:s',$time);
		
		$id = $_POST['longGameSelect'];
		mysql_query("UPDATE `long_games` SET endDate='$timestamp' WHERE gameID='$id';");
	
	
	}/*elseif (isset($_REQUEST['return'])) {
		
		// Save the number
		$bandanna = requestVar("number");
		$bandannaNumber = $bandanna;
		
		// Register as returned in the equipment table
		$bandanna = "".$bandanna;
		while(strlen($bandanna)<3) $bandanna = '0'.$bandanna;
		$bandanna = "EQBA".$bandanna;
		mysql_query("UPDATE equipment SET loanedTo='RETURNED' WHERE EID='$bandanna';");
		
		// Register as returned in the long_players table
		mysql_query("UPDATE long_players SET bandanna='0' WHERE bandanna='$bandannaNumber';");
		
		$GLOBALS['submitMessage']="Bandanna Returned.";
		
		
	}*/elseif (isset($_REQUEST['mailing']) || isset($_REQUEST['mailing_h']) || isset($_REQUEST['mailing_z'])) {
		
		$gameID = mysql_fetch_assoc(mysql_query("SELECT MAX(gameID) FROM `long_players`"));
		$gameID = $gameID['MAX(gameID)'];
		$sql = "";
		if($_REQUEST['mailing']) {
			$sql = "SELECT `playerID` FROM `long_players` WHERE `gameID`='$gameID' ORDER BY `playerID`;";
			$sqlCount = "SELECT COUNT(*) AS 'cnt' FROM `long_players` WHERE `gameID`='$gameID';";
		}
		if($_REQUEST['mailing_h']) {
			$sql = "SELECT `playerID` FROM `long_players` WHERE `gameID`='$gameID'  AND `state` > '0' ORDER BY `playerID`;";
			$sqlCount = "SELECT COUNT(*) AS 'cnt' FROM `long_players` WHERE `gameID`='$gameID' AND `state` > '0';";
		}
		if($_REQUEST['mailing_z']) {
			$sql = "SELECT `playerID` FROM `long_players` WHERE `gameID`='$gameID'  AND `state` != '1' ORDER BY `playerID`;";
			$sqlCount = "SELECT COUNT(*) AS 'cnt' FROM `long_players` WHERE `gameID`='$gameID' AND `state` != '1';";
		}
		
		$html = $html."<table border=1>";
		
		$html = $html."<tr>";
		$html = $html."<td>Name</td>";
		$html = $html."<td>Email Address</td>";
		$html = $html."<td>Username</td>";
		$html = $html."<td>Killcode (if human/OZ)</td>";
		$html = $html."<tr>";
		
		$email_chain = "Raw emails: ";
		
		$ids = mysql_query($sql);
		while($row = mysql_fetch_assoc($ids)) {
			
			$uid = $row['playerID'];
			
			$sqlTwo = "SELECT `fname`, `lname`, `email`, `uname` FROM `users` WHERE `uid`='$uid';";
			$ret = mysql_oneline($sqlTwo);
			$sqlThree = "SELECT `mainKill` FROM `long_players` WHERE `playerID`='$uid' AND `gameID`='$gameID';";
			$killcodes = mysql_oneline($sqlThree);
			
			$fname = $ret['fname'];
			$lname = $ret['lname'];
			$email = $ret['email'];
			$uname = $ret['uname'];
			$killcode = $killcodes['mainKill'];
			
			$email_chain = $email_chain.$email;
			$email_chain = $email_chain." ";
			
			//$html = $html."<br/>$email $fname $lname $uname";
			
			$html = $html."<td>$fname $lname</td>";
			$html = $html."<td>$email</td>";
			$html = $html."<td>$uname</td>";
			$html = $html."<td>$killcode</td>";
			$html = $html."<tr>";
		
		}
		$html = $html."</table><br/><br/><br/>";
		$html = $html.$email_chain."</br></br>";
		
		$count = mysql_oneline($sqlCount);
		$count = $count['cnt'];
		$html = $html."Number of players included: ".$count."<br/><br/>";
		
		
	}elseif (isset($_REQUEST['clear'])) {
		$html = "";
	}
}
?>
