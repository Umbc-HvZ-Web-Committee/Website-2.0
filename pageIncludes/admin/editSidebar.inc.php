<?php
require_once('../includes/util.php');
require_once('../includes/update.php');
load_config('../config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('../includes/loginUpdate.php');
$settings = get_settings();

if($_SESSION['isAdmin']>=2) {
	if(isset($_REQUEST['mondaySlides'])) {
		$mondaySlides = requestVar('mondaySlides');
		$customMondaySlides = requestVar('customMondaySlides');
		echo $mondaySlides."<br/>".$customMondaySlides;
	}
}
?>