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
		//echo($func."<br/>");
		if($func=="Set Monday Slides"){
			$mondaySlides = requestVar('mondaySlides');
			$customMondaySlides = requestVar('customMondaySlides');
			echo "<br/>".$mondaySlides."<br/>".$customMondaySlides;
			
			if($mondaySlides != NULL && $customMondaySlides != NULL) {
				echo "Could not update slides; Multiple options selected<br/>";
			}
			else if (($mondaySlides != NULL && $customMondaySlides == NULL) || ($mondaySlides == NULL && $customMondaySlides != NULL)) {
				if($mondaySlides == NULL && $customMondaySlides != NULL) {
					//Custom slides being used
					echo "Custom slides being used: ".$customMondaySlides."<br/>";
					
					mysql_query("UPDATE mission_slides SET url = '$customMondaySlides' WHERE name = 'mondayMission'");
				}else {
					//Preset slides being used
					
					//THIS QUERY DOESN'T WORK BECAUSE STUPID YAY
					//$slides_row = mysql_query("SELECT * FROM `mission_slides` WHERE `name` = '$mondaySlides';");
					switch($mondaySlides) {
						case "hvz101":
						$slides_row = mysql_oneline("SELECT * FROM `mission_slides` WHERE `name` = 'hvz101'");
						break;
						case "hvz102":
						$slides_row = mysql_oneline("SELECT * FROM `mission_slides` WHERE `name` = 'hvz102'");
						break;
						case "hvz202":
						$slides_row = mysql_oneline("SELECT * FROM `mission_slides` WHERE `name` = 'hvz202'");
						break;
						case "underConstruction":
						$slides_row = mysql_oneline("SELECT * FROM `mission_slides` WHERE `name` = 'underConstruction'");
						break;
						case "endSemester":
						$slides_row = mysql_oneline("SELECT * FROM `mission_slides` WHERE `name` = 'endSemester'");
						break;
						case "fiveNight":
						$slides_row = mysql_oneline("SELECT * FROM `mission_slides` WHERE `name` = 'fiveNight'");
						break;
					}
					//echo " - ID#".$slides_row['id']." - URL = ".$slides_row['url'];
					//echo "END<br/>";
					
					$url = $slides_row['url'];
					mysql_query("UPDATE mission_slides SET url = '$url' WHERE name = 'mondayMission'");
				}
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