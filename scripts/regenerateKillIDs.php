<?php
require_once('../includes/util.php');
load_config('../config.txt');
my_quick_con($config);

$gameIDToReset = 'LG00002';

mysql_query("UPDATE long_players SET mainKill='', state=1 WHERE gameID='$gameIDToReset'");

$q = mysql_query("SELECT playerID FROM long_players WHERE gameID='$gameIDToReset'");
while($ret = mysql_fetch_assoc($q)){
	$playerID = $ret['playerID'];
	$mainKill = generateRandomID(array("long_players", "long_preregister"), "mainKill", "MK", $killChars);
	mysql_query("UPDATE long_players SET mainKill='$mainKill' WHERE gameID='$gameIDToReset' AND playerID='$playerID'");
}
?>