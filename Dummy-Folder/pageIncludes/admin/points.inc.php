<?php
require_once('../includes/util.php');
load_config('../config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('../includes/loginUpdate.php');
$settings = get_settings();

$curGame = getCurrentLongGame();
if($curGame){
	$curGameTitle = $curGame['title'];
	$curGame = $curGame['gameID'];
	
	//Process a point add request
	$notificationText="";
	if(array_key_exists("submit", $_POST)) do{
		$points = requestVar("points");
		if(is_numeric($points)) $points = intval($points);
		else{
			$notificationText="Sorry, that's not a number. Please use a number for points.";
			break;
		}
		$reason = trim(requestVar("reason"));
		if($reason==""){
			$notificationText="Please provide a reason.";
			break;
		}
		
		foreach($_POST['givePointsTo'] as $player){
			mysql_query("INSERT INTO long_points(gameID, playerID, pointsGiven, reason) VALUES ('$curGame', '$player', $points, '$reason')");
			echo mysql_error();
		}
		$notificationText="Points have been given!";
	}while(false); //allows for break; to work
	
	//Create the checklist of all players
	//TODO populate this if the previous submit failed
	$playerChecklist = "<table><tr>";
	$ret = mysql_query("SELECT UID, fname, lname FROM long_players JOIN users ON long_players.playerID=users.UID WHERE gameID='$curGame' ORDER BY fname, lname");
	$line = 0;
	while($cur = mysql_fetch_assoc($ret)){
		$query = "SELECT SUM(pointsGiven) points FROM `long_points` WHERE gameID='$curGame' AND playerID='{$cur['UID']}' GROUP BY playerID";
		$ptsQuery = mysql_query($query);
		if($ptsQuery) 
		{
			$ptsQuery = mysql_fetch_assoc($ptsQuery);
			$points = $ptsQuery['points'];
			if($points == NULL)
			{
				$points = 0;
			}
		}
		else 
		{
			$points = 0;
		}
		$playerChecklist.='<td><label for="'.$cur['UID'].'"><input type="checkbox" name="givePointsTo[]" id="'.$cur['UID'].'" value="'.$cur['UID'].'"/> '.$cur['fname']." ".$cur['lname']." - ".$points.' point'.($points==1?'':'s').'</label></td>';
		$line++;
		if($line>=3){
			$playerChecklist.="</tr><tr>";
			$line = 0;
		}
	}
	$playerChecklist.="</tr></table>";
	
	//Get a table of all the points given this game
	$pointsLog = '<table border="1">';
	$ret = mysql_query("SELECT GROUP_CONCAT(CONCAT(fname,' ',lname)) names, pointsGiven, reason FROM `long_points` JOIN users ON long_points.playerID=users.UID WHERE gameID='$curGame' GROUP BY timeAdded, pointsGiven, reason");

	$ctr = 0;
	while($cur = mysql_fetch_assoc($ret)){
		$pointsLog.="<tr><td>{$cur['names']}</td><td>{$cur['pointsGiven']}</td><td>{$cur['reason']}</td></tr>";
		$ctr++;
	}
	
	if($ctr == 0)
	{
		$pointsLog.="<tr><td>NONE YET</td></tr>";
	}
	$pointsLog.="</table>";
	
	//Get the point totals for humans and zombies
	$ret = mysql_query("SELECT SUM(pointsGiven) points FROM `long_points` NATURAL JOIN `long_players` WHERE gameID='$curGame' AND state>0 GROUP BY SIGN(state)");
	$ret = mysql_fetch_assoc($ret);
	$humanPoints = ($ret == NULL) ? 0 : $ret['points'];
	
	$ret = mysql_query("SELECT SUM(pointsGiven) points FROM `long_points` NATURAL JOIN `long_players` WHERE gameID='$curGame' AND state<0 GROUP BY SIGN(state)");
	$ret = mysql_fetch_assoc($ret);
	$zombiePoints = ($ret == NULL) ? 0 : $ret['points'];
	
}
?>