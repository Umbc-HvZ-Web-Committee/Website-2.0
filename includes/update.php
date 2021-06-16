<?php

function to_seconds($interval){
	return ($interval->d * 24 * 60 * 60) +
	($interval->h * 60 *60) +
	$interval->s;
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
