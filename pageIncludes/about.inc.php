<?php
require_once('includes/util.php');
load_config('config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('includes/loginUpdate.php');
$settings = get_settings();

$questions = array();
$q = mysql_query("SELECT * FROM faq ORDER BY number ASC");
while($ret = mysql_fetch_assoc($q)){
	$questions[] = $ret;
}
?>