<?php
require_once('../includes/util.php');
require_once('../includes/update.php');
load_config('../config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('../includes/loginUpdate.php');
$settings = get_settings();

if($_SESSION['isAdmin'] >= 2) {
	if(isset($_REQUEST['submit'])) {
		$func = $_REQUEST['submit'];
		echo($func);
		if($func=="Set Monday Slides"){
			$mondaySlides = requestVar('mondaySlides');
			$customMondaySlides = requestVar('customMondaySlides');
			//echo "<br/>".$mondaySlides."<br/>".$customMondaySlides;
			
			if($mondaySlides != NULL && $customMondaySlides != NULL) {
				echo "Could not update slides; Multiple options selected<br/>";
			}
			else if ($mondaySlides != NULL && $customMondaySlides == NULL) {
				//Preset slides being used
				echo "Preset slides being used: ".$mondaySlides."<br/>";
			}
			else if ($mondaySlides == NULL && $customMondaySlides != NULL) {
				//Custom slides being used
				echo "Custom slides being used: ".$customMondaySlides."<br/>";
			}
			else if ($mondaySlides == NULL && $customMondaySlides == NULL) {
				//No slides were specified
				echo "Could not update slides; No option selected<br/>";
			}
			else {
				echo "Your if cases are bad and you should feel bad<br/>";
			}
		}
	}
}
?>