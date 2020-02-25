<?php
require_once('/var/www/includes/util.php');
load_config('/var/www/config.txt');
my_quick_con($config);

/*
 * Update the next event on the front page
 *
$url = "https://www.googleapis.com/calendar/v3/calendars/umbc.edu_vo141p038c4ei5dtp5dir8fm18@group.calendar.google.com/events?key=AIzaSyCab5he1uGb3ps5IpCubYUKL6FZ_2ZkOb8&singleEvents=true&orderBy=startTime&timeMin=".date("c");

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$json = curl_exec($ch);
$calData = json_decode($json);

foreach($calData->items as $nextEvent){
	if(property_exists($nextEvent->start, 'dateTime') and strtotime($nextEvent->start->dateTime)>time()) break;
}

$time = strtotime($nextEvent->start->dateTime);
$timeStr = strftime("%A, %B %e %l:%M%p", $time);

set_setting('nextEvent',"<b>Next Event</b> - ".$nextEvent->summary."<br/>$timeStr");
*/
/*
 * Check if anybody should be dead.
 *
mysql_query("UPDATE long_players SET state=0 WHERE state<0 AND deathTime!='0000-00-00 00:00:00' AND deathTime<CURRENT_TIMESTAMP;");
$modifiedCount = mysql_affected_rows();
if($modifiedCount>0){
	mail("benharris5@gmail.com", "Notice: $modifiedCount people just starved to death.", "Thought you ought to know.");
}

*/

/*
 * If it's midnight (hours in current time == 0), and we're in a long game, give hitlisted humans points, and add a human to the hitlist, if possible
 *
$gameID = getCurrentLongGame();
if($gameID){
	$gameID = $gameID['gameID'];
	if(date('G')=="0" && date('N')<6){ //midnight on a weekday
		//Give hitlist humans points
		mysql_query("INSERT INTO long_points(gameID, playerID, pointsGiven, reason) SELECT '$gameID' gameID, playerID, 1 pointsGiven, 'Surviving on the hitlist' FROM long_players WHERE gameID='$gameID' AND state>0 AND isOnHitlist=1");
		$ret = mysql_query("SELECT playerID, fname, email FROM long_players JOIN users ON users.UID=long_players.playerID WHERE gameID='$gameID' AND state>0 AND isOnHitlist=1");
		while($cur = mysql_fetch_assoc($ret)){
			mail($cur['email'], "Congrats living another day on the hitlist!", "Congrats {$cur['fname']}! For surviving today, you earned a point! ~HiccupBot");
		}

		//Make new hitlist human
		$ret = mysql_oneline("SELECT playerID, email, fname, lname FROM long_players JOIN users ON users.UID=long_players.playerID WHERE state>0 AND isOnHitlist=0 AND gameID='$gameID' ORDER BY RAND() LIMIT 1");
		echo mysql_error();
		if($ret){
			$email = $ret['email'];
			$playerID = $ret['playerID'];
			$name = $ret['fname']." ".$ret['lname'];
			mysql_query("UPDATE long_players SET isOnHitlist=1 WHERE gameID='$gameID' AND playerID='$playerID'");
			mail($email, "Congrats, you're now on the hitlist!", "You were randomly chosen by the server to be added to the zombie hitlist. Enjoy! ~HiccupBot");
			mail("umbchvzofficers@gmail.com", "$name was added to the hitlist.", "Just thought I'd let you know. ~HiccupBot");
		}
	}
}*/
?>
