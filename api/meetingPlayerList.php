<?php
header("Access-Control-Allow-Origin: *");
require_once('../includes/util.php');
load_config('../config.txt');
my_quick_con($config);

$id = requestVar("meeting");
$q = mysql_query("SELECT CONCAT(fname,' ',lname) name, startState FROM `meeting_log` NATURAL JOIN users WHERE meetingID='$id';");

while($ret = mysql_fetch_assoc($q)){
	$name = $ret['name'];
	$state = $ret['startState'];
	
	if($state==0) $state = "n/a";
	elseif($state>0) $state = "human";
	elseif($state<0) $state = "zombie";
	echo "$name - $state<br/>";
}
?>