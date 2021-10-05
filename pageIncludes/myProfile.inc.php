<?php
require_once('includes/util.php');
load_config('config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('includes/loginUpdate.php');
$settings = get_settings();

if (isset($_SESSION['uid'])) {
    $uid = $_SESSION['uid'];
} else {
    $uid = "";
}

//Get variables
$playerData = mysql_oneline("SELECT * FROM users WHERE UID='$uid'");
$curLongGame = getNextLongGame();
$gameID = $curLongGame['gameID'];
//If there is a long game and the player signed in is playing, get their long game data for where it matters
if($playerData && $curLongGame){
	$longPlayerData = mysql_oneline("SELECT * FROM long_players WHERE gameID='{$curLongGame['gameID']}' AND playerID='$uid'");
}else{
	$longPlayerData = false;
}

//Utility functions for this file

//Formerly used for preregistration
//TODO: Enable automatic registration from here for players that are signed into a pregame AND have a waiver turned in
function longGameRegSelect($uid){
	if($_SESSION['attendedPregame'] == 1 && $_SESSION['hasTurnedInWaiver'] == 1) {
		$qret = mysql_query("SELECT * FROM `long_games` NATURAL RIGHT JOIN `long_players` WHERE "
		." `playerID` = '$uid' AND CURRENT_TIMESTAMP < `startDate` ORDER BY startDate DESC;");
		echo $qret;
		$firstLoop = true;
		while($ret = mysql_fetch_assoc($qret)){
			$id = $ret['gameID'];
			$name = $ret['title'];
			$startDate = preg_split('/ /', $ret['startDate']);
			$startDate = $startDate[0];
			if($firstLoop){
				echo "You are registered for:<br/>";
				$firstLoop = false;
			}
			echo "$name - starts on $startDate";
			echo "<br>";
			echo '<form action="" method="post"><input type="hidden" name="gameID" value="'.$id.'"><input type="submit" name="removeReg" value="Unregister"></form>';
			echo "<br/>";
		}
	    if(!$firstLoop) echo "<br/>";
	}

	//THIS STATEMENT IS CONFUSING SO LET ME EXPLAIN
	//Subselect first.  So, pick every game that has you already registered in it.  Then left join that with every game.
	//This gives you every game, with the UID set if you've registered, and NULL if you haven't. So just pull out the NULLs,
	//filter & order by startDate, and tada! :D
	$qret = mysql_query("SELECT * FROM `long_games` NATURAL LEFT JOIN (SELECT * FROM `long_players` WHERE `playerID` = '$uid') ".
	                    "WHERE `playerID` IS NULL AND CURRENT_TIMESTAMP < `startDate` ORDER BY startDate DESC;");					
	echo $qret;
    if (isset($ret)) {
        echo $ret;
    } else {
        echo 'no $ret';
    }
	if($qret && $ret == mysql_fetch_assoc($qret)){
		echo '<select name="longGameSelect"/>';
		$id = $ret['gameID'];
		$name = $ret['title'];
		echo "<option value=\"$id\">$name</option>";
		while($ret = mysql_fetch_assoc($qret)){
			$id = $ret['gameID'];
			$name = $ret['title'];
			echo "<option value=\"$id\">$name</option>";
		}
		echo '</select>';
		echo '<input type="submit" name="longReg" value="Register"/>';
	}else{
		echo "No games to register for.";
	}
}

//POST request processing
if(isset($_REQUEST['betaSubmit'])){
	$betaOpt = requestVar("betaOpt");
	$betaText = requestVar("betaText");
	$betaOptIn = ($betaOpt=="in"?1:0);
	$uid = $_SESSION['uid'];
	
	mysql_query("UPDATE users SET isbetaTester=$betaOptIn WHERE UID='$uid'");
	
	$GLOBALS['profileMessage'] = "New feature preferences updated.";

if(isset($_REQUEST['ozSubmit'])){
	$ozOpt = requestVar("ozOpt");
	$ozText = requestVar("ozText");
	$ozOptIn = ($ozOpt=="in"?1:0);
	$uid = $_SESSION['uid'];
	
	mysql_query("UPDATE users SET ozOptIn=$ozOptIn, ozParagraph='$ozText' WHERE UID='$uid'");
	
	$GLOBALS['profileMessage'] = "OZ preferences updated.";
}

if(isset($_REQUEST['iDied'])){
	if($longPlayerData['state']>0){
		$state = $longPlayerData['state'];
		$newState = -1 * $state;
		$gameID = $curLongGame['gameID'];
		mysql_query("UPDATE `long_players` SET `state`='$newState', `iDied`=1 WHERE `playerID`='{$playerData['UID']}' AND `gameID`='{$curLongGame['gameID']}';");
		/*mysql_query("INSERT INTO `long_feeds` (`gameID`, `whoDied`, `whoFed`, `timeOfKill`, `hoursGiven`, `notes`) "
		."VALUES ('{$curLongGame['gameID']}', '{$playerData['UID']}', 'NOFEEDS', CURRENT_TIMESTAMP, {$settings['hoursTillStarve']}, '{$playerData['fname']} {$playerData['lname']} reported their own death.');");
		include 'includes/update.php';*/
		//setDeathTimer($curLongGame['gameID'], $playerData['UID'], $settings['hoursTillStarve'], true);
		if($newState == -1) { 
			updateAchieves($_SESSION['uid'], $gameID, "killed");
			$GLOBALS['profileMessage'] = "You are now marked as dead."; 
		}
		else if ($newState == -2) {
			$GLOBALS['profileMessage'] = "You are now marked as an OZ.";
			//Transfer from dummy OZ and delete dummy OZ
			
			$dummyID = "OZ".substr($uid, 2);
			echo $dummyID;
			echo "\n";
			$dummy = mysql_query("SELECT * FROM `long_players` WHERE `gameID` = '$gameID' AND `playerID` = '$dummyID';");
			$kills = $dummy['kills'];
			echo $kills;
			echo "\n";
			echo $uid;
			echo "\n";
			//Give OZ credit for their OZ kills
			mysql_query("UPDATE `long_players` SET `kills` = `kills` + $kills WHERE `playerID` = '$uid' AND `gameID`='$gameID';");
			mysql_query("UPDATE `users` SET `lifetimeKills` = `lifetimeKills` + $kills WHERE `UID` = '$uid';");
			//Reflect that the OZ made their OZ kills and not a dummy
			mysql_query("UPDATE `long_players` SET `killerID` = '$uid' WHERE `killerID` = '$dummyID' AND `gameID`='$gameID';");
			//Clear OZ's killcode
			mysql_query("UPDATE `long_players` SET `mainKill` = '' WHERE `playerID` = '$uid' AND `gameID`='$gameID';");
			$GLOBALS['profileMessage'] = "You are now marked as an OZ. You have also been credited with your OZ kills.";
			
		}
		else {
			$GLOBALS['profileMessage'] = "I don't know what just happened, but I think you are now marked as dead. Please go check the player page to make sure everything makes sense. Also, please let the moderators know you saw this message, especially if everything does not make sense on the player page.";
		}
	}
}

if(isset($_REQUEST['longReg'])){
	$retval = mysql_oneline("SELECT COUNT(*) AS cnt FROM long_players WHERE playerID='$uid' AND gameID='$gameID'");
	if($retval['cnt']==0){
		$toReg = requestVar('longGameSelect');
		$mainKill = generateRandomID(array("long_players", "long_preregister"), "mainKill", "MK", $killChars);
		$feedKill1 = generateRandomID(array("long_players", "long_preregister"), array("feedKill1","feedKill2"), "FK", $killChars);
		$feedKill2 = generateRandomID(array("long_players", "long_preregister"), array("feedKill1","feedKill2"), "FK", $killChars);
		mysql_query("INSERT INTO `long_players`(gameID, playerID, mainKill, feedKill1, feedKill2) VALUES ('$gameID', '$uid', '$mainKill', '$feedKill1', '$feedKill2');");
		$GLOBALS['profileMessage'] = "Registration complete!";
	}
	else {
		$GLOBALS['profileMessage'] = "You are already registered for that game";
	}
}

if(isset($_REQUEST['removeReg'])){
	$toReg = requestVar('gameID');
	mysql_query("DELETE FROM `long_register` WHERE gameID='$toReg' AND UID='$uid';");
	$GLOBALS['profileMessage'] = "Registration removed.";
}

//Re-get variables because they could have changed
$playerData = mysql_oneline("SELECT * FROM users WHERE UID='$uid'");
$curLongGame = getNextLongGame();
if($playerData && $curLongGame){
	$longPlayerData = mysql_oneline("SELECT * FROM long_players WHERE gameID='{$curLongGame['gameID']}' AND playerID='$uid'");
}else{
	$longPlayerData = false;
}

if(isset($_REQUEST['profilePicture'])) {
	
	// Get image information
	$image = $_FILES['image']['name'];
	if ($image) {
		$imageKey = null;
		$filename = stripslashes($image);
		$filename = preg_replace('/\s+/', '', $filename);
		$name = removeExtension($filename);
		$ext = getExtension ($filename);
		$ext = strtolower($ext);
		
		// Accept only valid extensions
		if (($ext != "png") && ($ext != "jpg") && ($ext != "jpeg") && ($ext != "gif")) {
			$GLOBALS['profileMessage'] = "<br><b>Invalid file. Please upload only png, jpg, or gif files.</b>";
		
		} else {
			$size = filesize ( $_FILES ['image'] ['tmp_name'] );
			if (/*$size > MAX_SIZE * 1024*/ false) {
				$GLOBALS['profileMessage'] = "<br><b>File exceeds size limitations</b>";
			
			} else {
				$imageKey = $name.$uid.".".$ext;
			}
		}
	
	} else {
		$GLOBALS['profileMessage'] = "<br><b>No file selected</b>";
	}
	
	// If a valid image was given
	if($imageKey != null) {
		
		// Check for current profile picture
		$ret = mysql_fetch_assoc(mysql_query("SELECT * FROM `profilePictures` WHERE `UID`='$uid';"));
		
		// If there is one, delete it
		if($ret['picture'] != null) {
			$fileToDelete = $ret['picture'];
			$fileToDelete = $_SERVER['DOCUMENT_ROOT']."/images/profilePictures/".$fileToDelete;
			if(file_exists($fileToDelete)) {
				unlink($fileToDelete);
				mysql_query("DELETE FROM `profilePictures` WHERE `UID`='$uid';");
			}
		}
		
		// Add the new picture
		$newPicturePath = $_SERVER['DOCUMENT_ROOT']."/images/profilePictures/".$imageKey;
		$success = move_uploaded_file($_FILES['image']['tmp_name'], $newPicturePath);
		if(!$success) {
			$GLOBALS['profileMessage'] = "<br><b>Picture could not be uploaded</b>";
		} else {
			mysql_query("INSERT INTO `profilePictures`(`UID`, `picture`) VALUES ('$uid','$imageKey');");
			$GLOBALS['profileMessage'] = "<br>Profile picture updated!";
		}
	}
}
	

if(isset($_REQUEST['favoriteAchieve'])) {
	
	// Getting the key
	$selectedAID = $_REQUEST['achieve'];
	
	// Finding current favorite
	$sql = "SELECT `AID` FROM `userAchieveLink_new` WHERE `UID`='$uid' AND `isFavorite`='1';";
	$ret = mysql_query($sql);
	$ret = mysql_fetch_assoc($ret);
	$AID = $ret['AID'];
	
	// Setting current favorite to not favorite
	$sql = "UPDATE `userAchieveLink_new` SET `isFavorite`=0 WHERE `AID`='$AID' AND `UID`='$uid'";
	$ret = mysql_query($sql);
	
	// Setting selected to favorite
	$sql = "UPDATE `userAchieveLink_new` SET `isFavorite`=1 WHERE `AID`='$selectedAID' AND `UID`='$uid'";
	$ret = mysql_query($sql);
	
	$GLOBALS['profileMessage'] = "<br>Favorite Achievement Updated!";
}

function generateList() {
	
	$uid = $_SESSION['uid'];
	
	$basic = array();
	$recruit = array();
	$veteran = array();
	$legendary = array();
	$retired = array();
	
	//This is just a natural inner join but I don't feel like changing it -Kyle, The Last Webmaster
	$sql = "SELECT * FROM `achievements_new` INNER JOIN `userAchieveLink_new` ON `achievements_new`.`AID`=`userAchieveLink_new`.`AID` WHERE `UID`='$uid'";
	$ret = mysql_query($sql);
	while($row = mysql_fetch_assoc($ret)) {
		switch($row['class'])//Check achevment level 
		{
			case "e":
				array_push($basic, $row);
				break;
			case "m":
				array_push($recruit, $row);
				break;
			case "h":
				array_push($veteran, $row);
				break;
			case "l":
				array_push($legendary, $row);
				break;
			case "r":
				array_push($retired, $row);
				break;
		}
	}
	
	echo "<option disabled>--- Basic ---</option>";
	foreach($basic as $achieve) {
		$value = $achieve['AID'];
		$name = $achieve['name'];
		echo "<option value=$value>$name</option>";
	}

	echo "<option disabled>-- Recruit --</option>";
	foreach($recruit as $achieve) {
		$value = $achieve['AID'];
		$name = $achieve['name'];
		echo "<option value=$value>$name</option>";
	}
	
	echo "<option disabled>-- Veteran --</option>";
	foreach($veteran as $achieve) {
		$value = $achieve['AID'];
		$name = $achieve['name'];
		echo "<option value=$value>$name</option>";
	}
	
	echo "<option disabled>- Legendary -</option>";
	foreach($legendary as $achieve) {
		$value = $achieve['AID'];
		$name = $achieve['name'];
		echo "<option value=$value>$name</option>";
	}
	
	if(sizeof($retired) > 0) {
		echo "<option disabled>-- Retired --</option>";
		foreach($retired as $achieve) {
			$value = $achieve['AID'];
			$name = $achieve['name'];
			echo "<option value=$value>$name</option>";
		}
	}
}

function displayFavAchievement() {//500 error here
	
	$uid = $_SESSION['uid'];
	
	$sql = "SELECT name, description, class, alignment, image FROM `achievements_new` INNER JOIN `userAchieveLink_new` ON `achievements_new`.`AID`=`userAchieveLink_new`.`AID` AND `userAchieveLink_new`.`isFavorite`=1 WHERE `UID`='$uid';";
	$ret = mysql_query($sql);
	$ret = mysql_fetch_assoc($ret);

    if (isset($ret['image'])) {
        $image = $ret['image'];
	    $ret['image'] = "<center><img class=\"smallImg\" src=\"$image\"></img></center>";
    }

    if (isset($ret['class'])) {
        switch($ret['class']) {
            case "e":
                $ret['class'] = "Basic";
                break;
            case "m":
                $ret['class'] = "Recruit";
                break;
            case "h":
                $ret['class'] = "Veteran";
                break;
            case "l":
                $ret['class'] = "Legendary";
                break;
            case "r":
                $ret['class'] = "Retired";
                break;
        }
    }

    if (isset($ret['alignment'])) {
        switch($ret['alignment']) {
            case "h":
                $ret['alignment'] = "Human";
                break;
            case "z":
                $ret['alignment'] = "Zombie";
                break;
            case "n":
                $ret['alignment'] = "Neutral";
                break;
            case "m":
                $ret['alignment'] = "Moderator";
                break;
            case "r":
                $ret['alignment'] = "Retired";
                break;
        }
    }
	
	echo "<table align='center' border='1' cellspacing='1' cellpadding='3'>";
	echo "<tr bgcolor='#800000'><th><font color='white'>Achievement</font></th><th><font color='white'>Description</font></th><th><font color='white'>Class</font></th><th><font color='white'>Affiliation</font></th><th><font color='white'>Image</font></th></tr>";
	echo "<tr bgcolor='#FFFFFF' align='center'>";
	foreach($ret as $cur){
		echo "<td style=\"position: relative\">$cur</td>";
	}
	echo "</tr>";
	echo "</table>";
	
}

function printAchieveTable() {
	
	$uid = $_SESSION['uid'];
	
	$basic = array();
	$recruit = array();
	$veteran = array();
	$legendary = array();
	$retired = array();
	
	$sql = "SELECT name, description, class, alignment, image FROM `achievements_new` INNER JOIN `userAchieveLink_new` ON `achievements_new`.`AID`=`userAchieveLink_new`.`AID` WHERE `UID`='$uid' ORDER BY class";
	$ret = mysql_query($sql);
	while($row = mysql_fetch_assoc($ret)) {
		switch($row['class']) {
			case "e":
				array_push($basic, $row);
				break;
			case "m":
				array_push($recruit, $row);
				break;
			case "h":
				array_push($veteran, $row);
				break;
			case "l":
				array_push($legendary, $row);
				break;
			case "r":
				array_push($retired, $row);
				break;
		}
	}	
	
	echo "<table align='center' border='1' cellspacing='1' cellpadding='3'>";
	echo "<tr bgcolor='#800000'><th><font color='white'>Achievement</font></th><th><font color='white'>Description</font></th><th><font color='white'>Class</font></th><th><font color='white'>Affiliation</font></th><th><font color='white'>Image</font></th></tr>";
	
	foreach($basic as $val) {
		printLine($val);
	}
	foreach($recruit as $val) {
		printLine($val);
	}
	foreach($veteran as $val) {
		printLine($val);
	}
	foreach($legendary as $val) {
		printLine($val);
	}
	foreach($retired as $val) {
		printLine($val);
	}
	echo "</table>";
	
}

function printLine(array $values){

    if (isset($values['class'])) {
        switch($values['class']) {
            case "e":
                $values['class'] = "Basic";
                break;
            case "m":
                $values['class'] = "Recruit";
                break;
            case "h":
                $values['class'] = "Veteran";
                break;
            case "l":
                $values['class'] = "Legendary";
                break;
            case "r":
                $values['class'] = "Retired";
                break;
        }
    }

    if (isset($values['alignment'])) {
        switch($values['alignment']) {
            case "h":
                $values['alignment'] = "Human";
                break;
            case "z":
                $values['alignment'] = "Zombie";
                break;
            case "n":
                $values['alignment'] = "Neutral";
                break;
            case "m":
                $values['alignment'] = "Moderator";
                break;
            case "r":
                $values['alignment'] = "Retired";
                break;
        }
    }
	$image = $values['image'];
	$values['image'] = "<center><img class=\"smallImg\" src=\"$image\"></img></center>";
	
	echo "<tr bgcolor='#FFFFFF' align='center'>";
	foreach($values as $cur){
		echo "<td style=\"position: relative\">$cur</td>";
	}
	echo "</tr>";
}

function getExtension($filename) {
	
	$periodPos = strpos($filename, ".");
	$extension = substr($filename, $periodPos + 1);
	return $extension;
}

function removeExtension($filename) {

	$periodPos = strpos($filename, ".");
	$name = substr($filename, 0, $periodPos);
	return $name;
}
?>