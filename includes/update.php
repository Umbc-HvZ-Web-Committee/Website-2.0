<?php

function to_seconds($interval){
	return ($interval->d * 24 * 60 * 60) +
	($interval->h * 60 *60) +
	$interval->s;
}

function setDeathTimer($gameID, $uid, $hoursFromMidnight, $newKill){
	
	/*$deathTime = date('Y-m-d H:i:s', strtotime('tomorrow midnight + '.$hoursFromMidnight.' hours')-1);
	if(date('N', strtotime($deathTime))>=6){ //death is Sat or Sun
		$deathTime = date('Y-m-d H:i:s', strtotime('tomorrow midnight + '.$hoursFromMidnight.' hours + 2 days')-1);
	}*/
	
	$deathTime = date('Y-m-d H:i:s', time());
	
	$sql = "SELECT `startDate` FROM `long_games` WHERE `gameID`='$gameID';";
	$ret = mysql_oneline($sql);
	$weeklongStart = $ret['startDate'];
	
	$weeklongStart = date('Y-m-d H:i:s', strtotime($weeklongStart));
	
	
	mysql_oneline("UPDATE `long_players` SET `deathTime`='$deathTime' WHERE `gameID`='$gameID' AND `playerID`='$uid'");
	//echo mysql_error();
	//echo "UPDATE `long_players` SET `deathTime`='$deathTime' WHERE `gameID`='$gameID' AND `playerID`='$uid'";
	
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
}

//TODO UPDATE WHOLE FILE
function updateState($uid, $gameID){
	$row = mysql_oneline("SELECT `state`, `cachedDeathTime`, CURRENT_TIMESTAMP AS now FROM `long_players` WHERE `playerID`='$uid' AND `gameID`='$gameID'");
	if($row['state']!=0 && $row['state']!=3 && $row['cachedDeathTime']!="0000-00-00 00:00:00"){
		if(strtotime($row['cachedDeathTime'])<strtotime($row['now'])){
			//HE DED.
			mysql_query("UPDATE `long_players` SET `state`=0 WHERE `playerID`='$uid' AND gameID='$gameID'");
			return 0;
		}
	}else if($row['state']==3 && $row['cachedDeathTime']!="0000-00-00 00:00:00"){
		if(strtotime($row['cachedDeathTime'])<strtotime($row['now'])){
			//check if the medkit has ever been logged; if it hasn't, delete crap, if it has, leave it (so you can get the error messages still)
			/*TODO DEAL WITH MEDKITS
			$ret = mysql_oneline("SELECT COUNT(*) AS cnt FROM `feedLog` WHERE `whoDied`='$uid';");
			if($ret['cnt']==0){
				//unreported, can safely delete medkit
				mysql_query("DELETE FROM `usersPlaying` WHERE `UID`='$uid';");
				return null;
			}//else do nothing
			*/
		}
	}
	return $row['state'];
}

function update($uid, $gameID, $event){
	//TODO updateDeathTime($uid, $gameID);
	updateAchieves($uid, $gameID, $event);
}

function updateDeathTime($uid, $gameID) {
	global $config;
	//update the cached death time
	$state = mysql_oneline("SELECT `state`, `cachedDeathDateTime` FROM `long_players` WHERE `UID`='$uid' AND gameID='$gameID'");
	$cddt = $state['cachedDeathDateTime'];
	$state = $state['state'];
	if($state!=3 && ($state<0 || $cddt!="0000-00-00 00:00:00")){//medkits are special, don't update their death times
		$ret = mysql_query("SELECT MIN(`timeOfFeed`) AS timeOfFeed FROM `feedLog` WHERE `whoDied` = '$uid';");
		if($ret){
			//if $ret is false then they aren't dead
			$settings = get_settings();
			$maxSecsTillStarve = intval($settings['maxHoursTillStarve'])*60*60;
			$secondsUntilStarve = intval($settings['hoursTillStarve'])*60*60;
	
			//This pulls the first log time and saves that.
			$ret = mysql_fetch_assoc($ret);
			$timeDied = date_create($ret['timeOfFeed']);
	
			//This gets all of the feeds that affect the zombie - any feeds to them, or any allfeeds that happened after they died - in order from earliest to latest.
			$ret = mysql_query("SELECT * FROM `feedLog` WHERE `whoWasFed` = '$uid' OR (`whoWasFed` = 'ALLZEDS' AND `timeOfFeed` < '".$ret['timeOfFeed']."') ORDER BY `timeOfFeed`;");
	
			//Because PHP's date classes are shitty, we're going to do this ourselves, keeping track of seconds until death.
			$lastUpdated = $timeDied;
			while($row = mysql_fetch_array($ret)){
				$feedTime = date_create($row['timeOfFeed']);
				$secondsAdded = intval($row['hoursGiven'])*60*60;
				$timeDiff = to_seconds(date_diff($lastUpdated, $feedTime));
				$secondsUntilStarve -= $timeDiff;
				$secondsUntilStarve += $secondsAdded;
				//disallow overfeeding
				if($secondsUntilStarve>$maxSecsTillStarve) $secondsUntilStarve = $maxSecsTillStarve;
				$lastUpdated = $feedTime;
			}
	
			//get the time of $lastUpdate + $secondsUntilStarve, somehow.
			$epoch = date_create_from_format("U", "0");
			$seconds = date_create_from_format("U", "$secondsUntilStarve");
			$timeDiff = date_diff($epoch, $seconds);
			$lastUpdated = date_add($lastUpdated, $timeDiff);
	
			//To get the death datetime, add the seconds until starve to the time it was last updated, and we're done!
			$mysqldate = $lastUpdated->format('Y-m-d H:i:s');
			mysql_query("UPDATE `usersPlaying` SET `cachedDeathDateTime` = '$mysqldate' WHERE `UID`='$uid';");
		}
	}
}

function updateAchieves($uid, $gameID, $event){
	global $config;
	//update all achievements
	require_once($_SERVER['DOCUMENT_ROOT'].$config['folder']."includes/achievementUpdateFunctions.php");
	$ret = mysql_query("SELECT `updateFunction` FROM `achievements_new` WHERE `isAuto`=1;");
	while($row = mysql_fetch_assoc($ret)){
		$function = $row['updateFunction'];
		$function = preg_replace("/\[\[UID\]\]/", "'".$uid."'", $function);
		$function = preg_replace("/\[\[EVENT\]\]/", "'".$event."'", $function);
		//OK before I even do this:
		//Yes, using eval() is dangerous.  However, it is safe in this context.
		//The code being evaled is a combination of a known valid UID and some
		//MySQL that is known to have been written manually by someone who
		//presumably knows what they're doing.  So I say it's ok.
		
		//TODO: uncomment
		eval($function);
	}
}

?>
