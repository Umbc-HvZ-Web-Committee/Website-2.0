<?php
require_once('includes/util.php');
load_config('config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('includes/loginUpdate.php');
$settings = get_settings();

//util functions
function printPlayerTable(){
	
	if(isset($_REQUEST['submit'])) {
		$orderBy = requestVar('order');
	} else {
		$orderBy = null;
	}
	
	$curGame = getCurrentLongGame();
	$curGame = $curGame['gameID'];
	
	if($curGame!=null){
		
		// 1 hour delay for kill logging
		// This does not currently work
		$ret = mysql_query("SELECT * FROM long_players WHERE state=1 AND gameID='$curGame'");
		while($row = mysql_fetch_assoc($ret))
		{
			
			$deathTime = DateTime::createFromFormat('Y-m-d H:i:s', $row['deathTime']);
			if($row['deathTime'] == "0000-00-00 00:00:00" || $deathTime == DateTime::createFromFormat('Y-m-d H:i:s', "0000-00-00 00:00:00"))
			{
				continue;
			}
			else{
			$currentTime = date_create();
			$timeToDisplay = $deathTime->date_modify('+1 hour');
			}
			if($currentTime > $timeToDisplay)
			{
				$playerID = $row['playerID'];
				mysql_query("UPDATE `long_players` SET `state`='-1' WHERE `playerID`='$playerID' AND `gameID`=$curGame;");
			}
		}
		
		$ret = mysql_oneline("SELECT COUNT(*) AS cnt FROM long_players WHERE state<0 AND gameID='$curGame'");
		$zombies = $ret['cnt'];
		$ret = mysql_oneline("SELECT COUNT(*) AS cnt FROM long_players WHERE state>0 AND gameID='$curGame'");
		$humans = $ret['cnt'];
		$ret = mysql_oneline("SELECT COUNT(*) AS cnt FROM long_players WHERE playerID LIKE 'OZ%' AND gameID='$curGame'");
		$hiddenOZ = $ret['cnt'];
		//points
		$ret = mysql_oneline("SELECT SUM(pointsGiven) points FROM long_points NATURAL JOIN long_players WHERE gameID='$curGame' AND state<0 GROUP BY 'ok'");
		//$zombiePoints = ($ret['points'] == NULL) ? 0 : $ret['points'];
		$ret = mysql_oneline("SELECT SUM(pointsGiven) points FROM long_points NATURAL JOIN long_players WHERE gameID='$curGame' AND state>0 GROUP BY 'ok'");
		//$humanPoints = ($ret['points'] == NULL) ? 0 : $ret['points'];
		echo "$humans human".($humans==1?"":"s")./*"with $humanPoints points"*/", and $zombies zombie".($zombies==1?"":"s")/*." with $zombiePoints points"*/;
//		echo "$humans Human".($humans==1?"":"s").", and $zombies Zombie".($zombies==1?"":"s");
		if($hiddenOZ){
			echo ", counting the ".($hiddenOZ==1?"":"$hiddenOZ ")."OZ".($hiddenOZ==1?"":"s")." as both human and zombie";
		}
		echo ".<br/><br/>";
	}
	
	//TODO WRITE THIS
	//TODO make settings get pulled from request variables for what to display, with default of course
	
	//TODO fix achievements
	$playerImage = "CONCAT('<img class=\"smallImg\" src=\"',profilePicture,'\"/>')";
	$markingImage = "CONCAT('<img class=\"smallImg\" src=\"',dartMarking,'\"/>')";
	//$achieveImage = "CONCAT('<center><img class=\"smallImg\" src=\"',image,'\" title=\"',name,' - ',description,'\"/></center>')";
	$achieveImage = "image, name, description";
	$name = "CONCAT(fname,' ',lname)";
	$state = "PlayerState(state) AS state";
	$hoursUntilDeath = "IF(state<0, TIMEDIFF(`deathTime`, NOW()), 'N/A')"; //if zombie, show death timer; otherwise, N/A
	if($curGame!=null){
// 		$name = "CONCAT($name, IF(isOnHitlist=1 AND state>0,'<img src=\"/images/crosshair.png\"/ style=\"width: 50px; height: 50px; position: absolute; top: 0; right: 0;\">',''))";
//		$headers = array("Name", "Username", "State", "Time until death", "Profile pic", "Kills this game", "Days survived this game", "Lifetime kills", "Latest day alive", "Favorite achievement");
//		$headers = array("Name", "Username", "State", "Profile pic", "Kills this game", "Days survived this game", "Lifetime kills", "Latest day alive", "Favorite achievement");
		$headers = array("Name", "State", "Profile pic", "Kills this game", "Lifetime kills", "Latest day alive", "Favorite achievement");
//		$columns = "$name, $state, $hoursUntilDeath, $playerImage, kills, daysSurvived, lifetimeKills, longestDaySurvived, $achieveImage";
//		$columns = "$name, $state, $playerImage, kills, daysSurvived, lifetimeKills, longestDaySurvived, $achieveImage";
		$columns = "$name, $state, $playerImage, kills, lifetimeKills, longestDaySurvived, $achieveImage";
		
		$tables = "users NATURAL LEFT JOIN (SELECT * FROM userAchieveLink_new NATURAL JOIN `achievements_new` WHERE isFavorite=1) AS achieves, long_players";
		$whereClause = "users.UID=long_players.playerID AND long_players.gameID='$curGame'";
		
		if($orderBy == "name") {
			$order = "state, lname";
		} else if ($orderBy == "kills") {
			$order = "state, lifetimeKills DESC";	
		} else if ($orderBy == "survived") {
			$order = "state, longestDaySurvived DESC";
		} else {
			$order = "state, lname";
		}
		
		
	}else{
//		$headers = array("Name", "Username", "Profile pic", "Lifetime kills", "Latest day alive", "Favorite achievement", "Dart Marking");
		$headers = array("Name", "Profile pic", "Lifetime kills", "Latest day alive", "Favorite achievement");
//		$columns = "$name, uname, $playerImage, lifetimeKills, longestDaySurvived, $achieveImage, $markingImage";
		$columns = "$name, $playerImage, lifetimeKills, longestDaySurvived, $achieveImage";
		$tables = "users NATURAL LEFT JOIN (SELECT * FROM userAchieveLink_new NATURAL JOIN `achievements_new` WHERE isFavorite=1) AS achieves";
		$whereClause = "appearancesThisTerm > 0 OR appearancesLastTerm > 0";
		
		if($orderBy == "name") {
			$order = "lname";
		} else if ($orderBy == "kills") {
			$order = "lifetimeKills DESC";
		} else if ($orderBy == "survived") {
			$order = "longestDaySurvived DESC";
		} else {
			$order = "lname";
		}
		
		/*foreach($columns[3] as $row) {
		/*switch($columns[3]) {
			case 0:
				$columns[3] = "N/A";
			case 1:
				$columns[3] = "Monday";
			case 2:
				$columns[3] = "Tuesday";
			case 3:
				$columns[3] = "Wednesday";
			case 4:
				$columns[3] = "Thursday";
			case 5:
				$columns[3] = "Friday";
			case 6:
				$columns[3] = "<img src=\"images/gold_star.png\"></img>";
		}
			echo "<p>$row</p>";
		}*/
	}
	
	//Remember that the printLine does stuff in the fetched order.

	$sql = "SELECT $columns,`UID` FROM $tables WHERE $whereClause ORDER BY $order";
	$ret = mysql_query($sql);
	
	if ($curGame != null) {
		echo "<table border=1 style=\"margin-left: -25px;\">";
	} else {
		echo "<table border=1>";
	}
	
	printLine($headers);

	while($val = mysql_fetch_array($ret)){
			
		if($curGame != null) {
			
			// Adjust for profile pictures
			$uid = array_pop($val);
			$profilePicture = mysql_fetch_assoc(mysql_query("SELECT `picture` FROM `profilePictures` WHERE `UID`='$uid';"));
			$profilePicture = $profilePicture['picture'];
			if($profilePicture == null) {
				$profilePicture = "anon.jpg";
			}
			$profilePicture = "/images/profilePictures/".$profilePicture;
			$profilePicture = "<center><img class=\"smallImg\" src=\"$profilePicture\"/></center>";
			$val[2] = $profilePicture;
			
			// Adjusting for achievement images
			if($val[6] != null) {
				$val[7] = strip_tags($val[7]);
				$val[8] = strip_tags($val[8]);
				$val[6] = "<center><img class=\"smallImg\" src=\"$val[6]\" title=\"$val[7] - $val[8]\"/></center>";
				array_pop($val);
				array_pop($val);
			} else {
				$val[6] = "";
				array_pop($val);
				array_pop($val);
			}
		} else {
			
			$uid = array_pop($val);
			
			$profilePicture = mysql_fetch_assoc(mysql_query("SELECT `picture` FROM `profilePictures` WHERE `UID`='$uid';"));
			$profilePicture = $profilePicture['picture'];
			if($profilePicture == null) {		
				$profilePicture = "anon.jpg";
			}
			$profilePicture = "/images/profilePictures/".$profilePicture;
			$profilePicture = "<center><img class=\"smallImg\" src=\"$profilePicture\"/></center>";
			$val[1] = $profilePicture;
			
			// Adjusting for achievement images
			if($val[4] != null) {
				$val[5] = strip_tags($val[5]);
				$val[6] = strip_tags($val[6]);
				$val[4] = "<center><img class=\"smallImg\" src=\"$val[4]\" title=\"$val[5] - $val[6]\"/></center>";
				array_pop($val);
				array_pop($val);
			} else {
				$val[4] = "";
				array_pop($val);
				array_pop($val);
			}
		}
		
		
		
		
		
		if($curGame != null) {
			
		/* UNCOMMENT IF STARVE TIMERS ARE USED	
		switch($val[7]) {
				case 0:
					$val[7] = "N/A";
					break;
				case 1:
					$val[7] = "Monday";
					break;
				case 2:
					$val[7] = "Tuesday";
					break;
				case 3:
					$val[7] = "Wednesday";
					break;
				case 4:
					$val[7] = "Thursday";
					break;
				case 5:
					$val[7] = "Friday";
					break;
				case 6:
					$val[7] = "<center><img src=\"images/gold_star.png\"></img></center>";
					break;
			}*/
			
		//DEFAULT LONG GAME TABLE
		
		switch($val[5]) {
				case 0:
					$val[5] = "N/A";
					break;
				case 1:
					$val[5] = "Monday";
					break;
				case 2:
					$val[5] = "Tuesday";
					break;
				case 3:
					$val[5] = "Wednesday";
					break;
				case 4:
					$val[5] = "Thursday";
					break;
				case 5:
					$val[5] = "Friday";
					break;
				case 6:
					$val[5] = "<center><img src=\"images/gold_star.png\"></img></center>";
					break;
			}
			
		} else {
			
			switch($val[3]) {
				case 0:
					$val[3] = "N/A";
					break;
				case 1:
					$val[3] = "Monday";
					break;
				case 2:
					$val[3] = "Tuesday";
					break;
				case 3:
					$val[3] = "Wednesday";
					break;
				case 4:
					$val[3] = "Thursday";
					break;
				case 5:
					$val[3] = "Friday";
					break;
				case 6:
					$val[3] = "<center><img src=\"images/gold_star.png\"></img></center>";
					break;
			}
			
		}
		printLine($val);
	}
	echo "</table>";
}

function printLine(array $values){
	echo "<tr>";
	foreach($values as $cur){
		echo "<td style=\"position: relative\">$cur</td>";
	}
	echo "</tr>";
}
?>
