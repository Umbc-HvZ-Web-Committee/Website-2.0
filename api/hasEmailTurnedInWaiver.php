<?php
header("Access-Control-Allow-Origin: *");
require_once('../includes/util.php');
load_config('../config.txt');
my_quick_con($config);

$email = $_GET['email'];
$ret = mysql_oneline("SELECT `hasTurnedInWaiver` AS ans FROM `users` WHERE `email`='$email';");
if($ret['ans']==1) echo "OK";
else echo "NO";
?>