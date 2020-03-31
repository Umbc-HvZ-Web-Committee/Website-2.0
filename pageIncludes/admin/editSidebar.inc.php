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
		if($func=="Set First Slides"){
			$mondaySlides = requestVar('mondaySlides');
			$customMondaySlides = requestVar('customMondaySlides');
			$startingSlideNumber = requestVar('startingSlideNumber');
			if($startingSlideNumber == "") {
				$startingSlideNumber = 1;
			}
			//echo "<br/>".$mondaySlides."<br/>".$customMondaySlides;
			
			if($mondaySlides != NULL && $customMondaySlides != NULL) {
				echo "Could not update slides; Multiple options selected<br/>";
			}
			else if (($mondaySlides != NULL && $customMondaySlides == NULL) || ($mondaySlides == NULL && $customMondaySlides != NULL)) {
				if($mondaySlides == NULL && $customMondaySlides != NULL) {
					//Custom slides being used
					//echo "Custom slides being used: ".$customMondaySlides."<br/>";
					
					mysql_query("UPDATE mission_slides SET url = '$customMondaySlides', startingSlideNumber = '$startingSlideNumber' WHERE name = 'mondayMission'");
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
					mysql_query("UPDATE mission_slides SET url = '$url', startingSlideNumber = '$startingSlideNumber' WHERE name = 'mondayMission'");
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
		
		if($func=="Set Second Slides"){
			$thursdaySlides = requestVar('thursdaySlides');
			$customThursdaySlides = requestVar('customThursdaySlides');
			$startingSlideNumber = requestVar('startingSlideNumber');
			if($startingSlideNumber == "") {
				$startingSlideNumber = 1;
			}
			//echo "<br/>".$thursdaySlides."<br/>".$customthursdaySlides;
			
			if($thursdaySlides != NULL && $customThursdaySlides != NULL) {
				echo "Could not update slides; Multiple options selected<br/>";
			}
			else if (($thursdaySlides != NULL && $customThursdaySlides == NULL) || ($thursdaySlides == NULL && $customThursdaySlides != NULL)) {
				if($thursdaySlides == NULL && $customThursdaySlides != NULL) {
					//Custom slides being used
					//echo "Custom slides being used: ".$customThursdaySlides."<br/>";
					
					mysql_query("UPDATE mission_slides SET url = '$customThursdaySlides', startingSlideNumber = '$startingSlideNumber' WHERE name = 'thursdayMission'");
				}else {
					//Preset slides being used
					
					//THIS QUERY DOESN'T WORK BECAUSE STUPID YAY
					//$slides_row = mysql_query("SELECT * FROM `mission_slides` WHERE `name` = '$thursdaySlides';");
					switch($thursdaySlides) {
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
					mysql_query("UPDATE mission_slides SET url = '$url', startingSlideNumber = '$startingSlideNumber' WHERE name = 'thursdayMission'");
				}
			}
			else if ($thursdaySlides == NULL && $customThursdaySlides == NULL) {
				//No slides were specified
				echo "Could not update slides; No option selected<br/>";
			}
			else {
				echo "Your if cases are bad and you should feel bad<br/>";
			}
		}
		
		if($func=="Set Third Slides"){
			$pointSlides = requestVar('pointSlides');
			$customPointSlides = requestVar('customPointSlides');
			$startingSlideNumber = requestVar('startingSlideNumber');
			if($startingSlideNumber == "") {
				$startingSlideNumber = 1;
			}
			//echo "<br/>".$thursdaySlides."<br/>".$customthursdaySlides;
			
			if($thursdaySlides != NULL && $customThursdaySlides != NULL) {
				echo "Could not update slides; Multiple options selected<br/>";
			}
			else if (($pointSlides != NULL && $customPointSlides == NULL) || ($pointSlides == NULL && $customPointSlides != NULL)) {
				if($pointSlides == NULL && $customPointSlides != NULL) {
					//Custom slides being used
					//echo "Custom slides being used: ".$customPointSlides."<br/>";
					
					mysql_query("UPDATE mission_slides SET url = '$customPointSlides', startingSlideNumber = '$startingSlideNumber' WHERE name = 'pointSlide'");
				}else {
					//Preset slides being used
					
					//THIS QUERY DOESN'T WORK BECAUSE STUPID YAY
					//$slides_row = mysql_query("SELECT * FROM `mission_slides` WHERE `name` = '$pointSlide';");
					switch($pointSlides) {
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
					mysql_query("UPDATE mission_slides SET url = '$url', startingSlideNumber = '$startingSlideNumber' WHERE name = 'pointSlide'");
				}
			}
			else if ($pointSlides == NULL && $customPointSlides == NULL) {
				//No slides were specified
				echo "Could not update slides; No option selected<br/>";
			}
			else {
				echo "Your if cases are bad and you should feel bad<br/>";
			}
		}
		
		if($func == "Update Headings") {
			$mainHeading = requestVar('mainHeading');
			$firstSlides = requestVar('firstSlides');
			$secondSlides = requestVar('secondSlides');
			$thirdSlides = requestVar('thirdSlides');
			
			if($mainHeading != NULL) {
				mysql_query("UPDATE mission_slide_headings SET headingName = '$mainHeading' WHERE headingTitle = 'mainHeading';");
			}
			if($firstSlides != NULL) {
				echo $firstSlides;
				mysql_query("UPDATE mission_slide_headings SET headingName = '$firstSlides'; WHERE headingTitle = 'firstSlides'");
			}
			if($secondSlides != NULL) {
				echo $secondSlides;
				mysql_query("UPDATE mission_slide_headings SET headingName = '$secondSlides'; WHERE headingTitle = 'secondSlides'");
			}
			if($thirdSlides == "IGNORE") {
				mysql_query("UPDATE mission_slide_headings SET headingName = NULL WHERE headingTitle = 'thirdSlides';");
			}else if($thirdSlides != NULL) {
				mysql_query("UPDATE mission_slide_headings SET headingName = '$thirdSlides' WHERE headingTitle = 'thirdSlides';");
			}
		}
	}
}
?>