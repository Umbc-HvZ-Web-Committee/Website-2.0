<?php
header("Access-Control-Allow-Origin: *");
require_once('../includes/util.php');
load_config('../config.txt');
my_quick_con($config);

$id = requestVar("meeting");
$ret = mysql_oneline("SELECT a.cnt+b.cnt AS cnt FROM (SELECT COUNT(*) AS cnt FROM `meeting_unregistered_log` WHERE meetingID='$id') AS a, (SELECT COUNT(*) AS cnt FROM `meeting_log` WHERE meetingID='$id') AS b;");
$total = $ret['cnt'];
$ret = mysql_oneline("SELECT a.cnt+b.cnt AS cnt FROM (SELECT COUNT(*) AS cnt FROM `meeting_unregistered_log` WHERE meetingID='$id' AND startState=1) AS a, (SELECT COUNT(*) AS cnt FROM `meeting_log` WHERE meetingID='$id' AND startState=1) AS b;");
$human= $ret['cnt'];
$ret = mysql_oneline("SELECT a.cnt+b.cnt AS cnt FROM (SELECT COUNT(*) AS cnt FROM `meeting_unregistered_log` WHERE meetingID='$id' AND startState=-1) AS a, (SELECT COUNT(*) AS cnt FROM `meeting_log` WHERE meetingID='$id' AND startState=-1) AS b;");
$zed = $ret['cnt'];
$other = $total-$human-$zed;

echo "$human humans, $zed zombies, and $other unknown - $total total.";
?>