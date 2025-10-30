<?php
//Utility functions

function hasAchieve($aid, $uid) {
	
	// Check to see if the achievement is in `userAchieveLink`, if so cnt == 1
	$ret = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) AS cnt FROM `userAchieveLink_new` WHERE `AID`='$aid' AND `UID`='$uid';"));
	return $ret['cnt']==1;
}

// Returns 1 if achievement was awarded, 0 if user already had it
function giveAchieve($aid, $uid) {
	
	// Check to see if achievement has been earned
	if(!hasAchieve($aid, $uid)) {
		// If not
		mysql_query("INSERT INTO `userAchieveLink_new` (`AID`, `UID`) VALUES ('$aid', '$uid');");
		$nameLookup = mysql_query("SELECT name from `achievements_new` WHERE `AID`='$aid';");
		$nameLookup = mysql_fetch_assoc($nameLookup);
		$achieveName = $nameLookup['name'];
		
		$subject = "Achievement \"$achieveName\" Awarded";
		$msg = <<<EOF
Congratulations!
		
You have been awarded the achievement $achieveName! To set this achievement as your displayed achievement on 'umbchvz.com/playerList.php', go to 'umbchvz.com/myProfile.php' and log in. Under the 'Achievements' header, select this achievement from the dropdown menu and click 'Submit'. This achievement should now be displayed in the same row as your name on the Player List.
		
Please note that artwork has not been chosen for all achievements and the image may still say 'Coming Soon'. If this is the case, hover your mouse over the image to verify the proper achievement was selected. The artwork will automatically update as it becomes available.
		
~ Fernando Chicas, Ethan Gavers, Eli Kramer-Smyth - UMBC HvZ Web Committee ~
THIS IS AN AUTOMATED MESSAGE.
EOF;
		$emailLookup = mysql_query("SELECT email FROM `users` WHERE `UID`='$uid';");
		$emailLookup = mysql_fetch_assoc($emailLookup);
		$email = $emailLookup['email'];
		mail($email, $subject, $msg);
			
		return 1;
	}	
	return 0;
}

//Gives an achievement without sending an email alert
function quietGiveAchieve($aid, $uid) {

	// Check to see if achievement has been earned
	if(!hasAchieve($aid, $uid)) {
		// If not
        mysql_query("INSERT INTO `userAchieveLink_new` (`AID`, `UID`) VALUES ('$aid', '$uid');");
	}
}

function takeAchieve($aid, $uid) {

	// Check to see if achievement has been earned
	if(hasAchieve($aid, $uid)) {
		// If not
		mysql_query("DELETE FROM `userAchieveLink_new` WHERE `UID`='$uid' AND `AID`='$aid';");
	}
}



//FUNCTIONS GO HERE
//Remember, you are responsible for setting the achievement!
//Remember, the event that caused this update happened BEFORE this function is called. So it's been logged already.
//[[UID]] is replaced in the database by the UID
//[[EVENT]] is replaced by the event text
//an event type of "all" should force you to check always

//Make sure to set the updateFunction value for the achievement in question (learned from experience). Also set the isAuto value to 1.


function specialMission($uid, $event) {
	//make sure to change key to proper achievement
	$aid = mysql_fetch_assoc(mysql_query("SELECT `AID` FROM `achievements_new` WHERE `key`='holidayCheer';")); 
	$aid = $aid['AID'];
	if($event == "specialMission") {
		giveAchieve($aid, $uid);
	}
}

function zbashMission($uid, $event) {
	//make sure to change key to proper achievement
	$aid = mysql_fetch_assoc(mysql_query("SELECT `AID` FROM `achievements_new` WHERE `key`='2Spooky';")); 
	$aid = $aid['AID'];
	if($event == "zbashMission") {
		giveAchieve($aid, $uid);
	}
}

function holidayMission($uid, $event) {
	//make sure to change key to proper achievement
	$aid = mysql_fetch_assoc(mysql_query("SELECT `AID` FROM `achievements_new` WHERE `key`='holidayCheer';")); 
	$aid = $aid['AID'];
	if($event == "holidayMission") {
		giveAchieve($aid, $uid);
	}
}

//This needs to be deactivated if for some strange reason a mission with zombie->human "curing mechanics"
//is run (which should never, ever happen anyway, but that is beside the point)
function patientZero($uid, $event) {
	$aid = mysql_fetch_assoc(mysql_query("SELECT `AID` FROM `achievements_new` WHERE `key`='patientZero';"));
	$aid = $aid['AID'];
	if($event == "patientZero") {
		giveAchieve($aid, $uid);
	}
}

function membership($uid, $event) {
	$aid = mysql_fetch_assoc(mysql_query("SELECT `AID` FROM `achievements_new` WHERE `key`='membership';"));
	$aid = $aid['AID'];
	if($event == "attendance0") {
		giveAchieve($aid, $uid);
	}
}

function experienced($uid, $event) {
	$aid = mysql_fetch_assoc(mysql_query("SELECT `AID` FROM `achievements_new` WHERE `key`='experienced';"));
	$aid = $aid['AID'];
	if($event == "attendance1") {
		giveAchieve($aid, $uid);
	}
}

function warVet($uid, $event) {
	$aid = mysql_fetch_assoc(mysql_query("SELECT `AID` FROM `achievements_new` WHERE `key`='warVet';"));
	$aid = $aid['AID'];
	if($event == "attendance2") {
		giveAchieve($aid, $uid);
	}
}

function warExpert($uid, $event) {
	$aid = mysql_fetch_assoc(mysql_query("SELECT `AID` FROM `achievements_new` WHERE `key`='warExpert';"));
	$aid = $aid['AID'];
	if($event == "attendance3") {
		giveAchieve($aid, $uid);
	}
}

function warMaster($uid, $event) {
	$aid = mysql_fetch_assoc(mysql_query("SELECT `AID` FROM `achievements_new` WHERE `key`='warMaster';"));
	$aid = $aid['AID'];
	if($event == "attendance4") {
		giveAchieve($aid, $uid);
	}
}

function idRatherDie($uid, $event) {
	$aid = mysql_fetch_assoc(mysql_query("SELECT `AID` FROM `achievements_new` WHERE `key`='idRatherDie';"));
	$aid = $aid['AID'];
	if($event == "attendanceZ1") {
		giveAchieve($aid, $uid);
	}
}

function liveLaughStun($uid, $event) {
	$aid = mysql_fetch_assoc(mysql_query("SELECT `AID` FROM `achievements_new` WHERE `key`='liveLaughStun';"));
	$aid = $aid['AID'];
	if($event == "attendanceH1") {
		giveAchieve($aid, $uid);
	}
}

function moderationMachine($uid, $event) {
	$aid = mysql_fetch_assoc(mysql_query("SELECT `AID` FROM `achievements_new` WHERE `key`='moderationMachine';"));
	$aid = $aid['AID'];
	if($event == "attendanceM1") {
		giveAchieve($aid, $uid);
	}
}

function communityConcious($uid, $event) {
	$aid = mysql_fetch_assoc(mysql_query("SELECT `AID` FROM `achievements_new` WHERE `key`='communityConcious';"));
	$aid = $aid['AID'];
	if($event == "attendanceC1") {
		giveAchieve($aid, $uid);
	}
}

//Does not work
function legendaryHunter($uid, $event) {
	$aid = mysql_fetch_assoc(mysql_query("SELECT `AID` FROM `achievements_new` WHERE `key`='legendaryHunter';"));
	$aid = $aid['AID'];
	if($event=="madeKill" || $event=="all"){
	
		$kills = mysql_fetch_assoc(mysql_query("SELECT `lifetimeKills` FROM `users` WHERE `playerID`='$uid'"));
		$kills = $kills['lifetimeKills'];
		if($kills >= 25) {
			giveAchieve($aid, $uid);
		}
	}
}

//Untested
function growingHorde($uid, $event) {
	$aid = mysql_fetch_assoc(mysql_query("SELECT `AID` FROM `achievements_new` WHERE `key`='growingHorde';"));
	$aid = $aid['AID'];
	if($event=="madeKill" || $event=="all"){
	
		$gameID = mysql_fetch_assoc(mysql_query("SELECT MAX(`gameID`) as 'gameID' FROM `long_games`;"));
		$gameID = $gameID['gameID'];
		$kills = mysql_fetch_assoc(mysql_query("SELECT `kills` FROM `long_players` WHERE `playerID`='$uid' AND `gameID`='$gameID';"));
		$kills = $kills['kills'];
		if($kills >= 1) {
			giveAchieve($aid, $uid);
		}
	}
}

function expertHunter($uid, $event) {
	$aid = mysql_fetch_assoc(mysql_query("SELECT `AID` FROM `achievements_new` WHERE `key`='expertHunter';"));
	$aid = $aid['AID'];
	if($event=="madeKill" || $event=="all"){
	
		$gameID = mysql_fetch_assoc(mysql_query("SELECT MAX(`gameID`) as 'gameID' FROM `long_games`;"));
		$gameID = $gameID['gameID'];
		$kills = mysql_fetch_assoc(mysql_query("SELECT `kills` FROM `long_players` WHERE `playerID`='$uid' AND `gameID`='$gameID';"));
		$kills = $kills['kills'];
		if($kills >= 3) {
			giveAchieve($aid, $uid);
		}
	}
}


function angelOfDeath($uid, $event) {
	$aid = mysql_fetch_assoc(mysql_query("SELECT `AID` FROM `achievements_new` WHERE `key`='angelOfDeath';"));
	$aid = $aid['AID'];
	if($event=="madeKill" || $event=="all"){
	
		$gameID = mysql_fetch_assoc(mysql_query("SELECT MAX(`gameID`) as 'gameID' FROM `long_games`;"));
		$gameID = $gameID['gameID'];
		$kills = mysql_fetch_assoc(mysql_query("SELECT `kills` FROM `long_players` WHERE `playerID`='$uid' AND `gameID`='$gameID';"));
		$kills = $kills['kills'];
		if($kills >= 10) {
			giveAchieve($aid, $uid);
		}
	}
}


function noCigar($uid, $event) {
	$aid = mysql_fetch_assoc(mysql_query("SELECT `AID` FROM `achievements_new` WHERE `key`='noCigar';"));
	$aid = $aid['AID'];
	if($event=="killed" || $event=="all"){
		$gameID = mysql_fetch_assoc(mysql_query("SELECT MAX(`gameID`) as 'gameID' FROM `long_games`;"));
		$gameID = $gameID['gameID'];
		$startDate = mysql_fetch_assoc(mysql_query("SELECT `startDate` FROM `long_games` WHERE `gameID`='$gameID';"));
		$startDate = $startDate['startDate'];
		$ret = mysql_oneline("SELECT CURRENT_TIMESTAMP AS now;");
		$now = $ret['now'];
		$diff = abs(strtotime($now) - strtotime($startDate)); // Gives result in seconds
		if($diff >= 345600 && $diff <= 432000) {
			giveAchieve($aid, $uid);
		}
	}
}

function reportForDuty($uid, $event) {
	$aid = mysql_fetch_assoc(mysql_query("SELECT `AID` FROM `achievements_new` WHERE `key`='reportForDuty';"));
	$aid = $aid['AID'];
	if($event == "registered") {
		quietGiveAchieve($aid, $uid);
	}
}

function firstBlood($uid, $event) {
	$aid = mysql_fetch_assoc(mysql_query("SELECT `AID` FROM `achievements_new` WHERE `key`='firstBlood';"));
	$aid = $aid['AID'];
	if($event=="killed" || $event=="all"){
		
		$gameID = mysql_fetch_assoc(mysql_query("SELECT MAX(`gameID`) as 'gameID' FROM `long_games`;"));
		$gameID = $gameID['gameID'];
		$numDead = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) AS cnt FROM `long_players` WHERE `state`=-1 AND `gameID`='$gameID';"));
		$numDead = $numDead['cnt'];
		if($numDead == 1) {
			giveAchieve($aid, $uid);
		}
	}
}

function alphaPred($uid, $event) {
	$aid = mysql_fetch_assoc(mysql_query("SELECT `AID` FROM `achievements_new` WHERE `key`='alphaPred';"));
	$aid = $aid['AID'];
	if($event=="gameEnd"){
	
		$gameID = mysql_fetch_assoc(mysql_query("SELECT MAX(`gameID`) as 'gameID' FROM `long_games`;"));
		$gameID = $gameID['gameID'];
		$maxKills = mysql_fetch_assoc(mysql_query("SELECT MAX(`kills`) as 'kills' FROM `long_players` WHERE `gameID`='$gameID';"));
		$maxKills = $maxKills['kills'];
		if($maxKills < 3) {
			return;
		}
		
		$ret = mysql_query("SELECT `playerID` FROM `long_players` WHERE `kills`='$maxKills' AND `gameID`='$gameID';");
		while($row = mysql_fetch_assoc($ret)){
			$uid = $row['playerID'];
			giveAchieve($aid, $uid);
		}
	}
}

function actuallyOZ($uid, $event) {
	$aid = mysql_fetch_assoc(mysql_query("SELECT `AID` FROM `achievements_new` WHERE `key`='actuallyOZ';"));
	$aid = $aid['AID'];
	if($event=="madeOZ"){
		quietGiveAchieve($aid, $uid);
	}
}

function playingTheLongGame($uid, $event) {
	$aid = mysql_fetch_assoc(mysql_query("SELECT `AID` FROM `achievements_new` WHERE `key`='playingLongGame';"));
	$aid = $aid['AID'];
	if($event=="longRegister"){
		giveAchieve($aid, $uid);
	}
}


/* THE FOLLOWING ACHIEVEMENTS HAVE ALL BEEN RETIRED. CODE HAS BEEN PRESERVED FOR RECORD-KEEPING */

/*----BEGIN RETIRED-----
//RETIRED AS ACHIEVEMENTS NO LONGER GIVE POINTS
function giveAchieve($aid, $uid){
	$ret = mysql_fetch_assoc(mysql_query("SELECT `points`, `team` FROM `achievements` WHERE `AID`='$aid';"));
	if($ret['team']=="h"){
		mysql_query("UPDATE `users` SET `humanPoints`=`humanPoints`+".$ret['points']." WHERE `UID`='$uid';");
	}else if($ret['team']=="z"){
		mysql_query("UPDATE `users` SET `zombiePoints`=`zombiePoints`+".$ret['points']." WHERE `UID`='$uid';");
	}else if($ret['team']=="m"){
		mysql_query("UPDATE `users` SET `modPoints`=`modPoints`+".$ret['points']." WHERE `UID`='$uid';");
	}

	mysql_query("INSERT INTO `pointLog` (`UID`, `points`, `team`, `reason`) VALUES ('$uid',".$ret['points'].",'".$ret['team']."','$aid earned')");

	if(!hasAchieve($aid, $uid)){
		//insert new achieve
		mysql_query("INSERT INTO `userAchieveLink` (`AID`, `UID`) VALUES ('$aid','$uid');");
	}else{ //increment the count
		mysql_query("UPDATE `userAchieveLink` SET `count`=`count`+1 WHERE `UID`='$uid' AND `AID`='$aid';");
	}
}

// RETIRED AS ACHIEVEMENTS CAN ONLY BE EARNED ONCE NOW
function achieveCount($aid, $uid){
	if(!hasAchieve($aid, $uid)) return 0;
	$ret = mysql_fetch_assoc(mysql_query("SELECT `count` FROM `userAchieveLink` WHERE `AID`='$aid' AND `UID`='$uid';"));
	return $ret['count'];
}

//RETIRED FOR THE SAME REASON AS giveAchieve
function takeAchieve($aid, $uid){
	$ret = mysql_fetch_assoc(mysql_query("SELECT `points`, `team` FROM `achievements` WHERE `AID`='$aid';"));
	if($ret['team']=="h"){
		mysql_query("UPDATE `users` SET `humanPoints`=`humanPoints`-".$ret['points']." WHERE `UID`='$uid';");
	}else if($ret['team']=="z"){
		mysql_query("UPDATE `users` SET `zombiePoints`=`zombiePoints`-".$ret['points']." WHERE `UID`='$uid';");
	}else if($ret['team']=="m"){
		mysql_query("UPDATE `users` SET `modPoints`=`modPoints`-".$ret['points']." WHERE `UID`='$uid';");
	}

	mysql_query("INSERT INTO `pointLog` (`UID`, `points`, `team`, `reason`) VALUES ('$uid',-".$ret['points'].",'".$ret['team']."','$aid lost')");

	$count = achieveCount($aid, $uid);
	if($count==0) return; //can't have negative achieves
	else if($count==1){
		//remove the only instance; also disallows favorites
		mysql_query("DELETE FROM `userAchieveLink` WHERE `UID`='$uid' AND `AID`='$aid';");
	}else{ //decrement the count
		mysql_query("UPDATE `userAchieveLink` SET `count`=`count`-1 WHERE `UID`='$uid' AND `AID`='$aid';");
	}
}

RETIRED AS ACHIEVES HAVE NO COUNT ATTRIBUTE NOW
function hasAchieve($aid, $uid){
	$ret = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) AS cnt FROM `userAchieveLink` WHERE `AID`='$aid' AND `UID`='$uid';"));
	return $ret['cnt']==1;
}


function closeButNoCigar($uid, $event){
	$aid = "AC00001";
	if($event=="killed" || $event=="all"){
		$settings = get_settings();
		$endDate = $settings['gameEnd'];
		$ret = mysql_oneline("SELECT CURRENT_TIMESTAMP AS now;");
		$now = $ret['now'];
		$diff = date_diff(date_create($endDate), date_create($now));
		if($diff->d==0) giveAchieve($aid, $uid);
	}
}

function firstBlood($uid, $event){
	$aid = "AC00002";
	if($event=="killed" || $event=="all"){
		//This pulls the number of records in the feed log that did feed someone (i.e. real kills, not OZ marking)
		$ret = mysql_oneline("SELECT COUNT(*) AS cnt FROM `feedLog` WHERE `hoursGiven`!=0;");
		if($ret['cnt']==1){ //remember, you're already in the log
			giveAchieve($aid, $uid);
		}
	}
}

function savedByTheBell($uid, $event){
	$aid = "AC00003";
	if($event=="fed" || $event=="madeKill" || $event=="all"){
		$row = mysql_oneline("SELECT `cachedDeathDateTime`, CURRENT_TIMESTAMP AS now FROM `usersPlaying` WHERE `UID`='$uid';");
		$deathTime = date_create($row['cachedDeathDateTime']);
		$now = date_create($row['now']);
		$diff = date_diff($now, $deathTime);
		if($diff->d==0 && $diff->h==0 && $diff->m<=30){//no days, no hours, less than 30min inclusive
			giveAchieve($aid, $uid);
		}
	}
}
----END OF RETIRED-----*/

?>
