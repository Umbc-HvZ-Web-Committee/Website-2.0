<?php
require_once('includes/util.php');
load_config('config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('includes/loginUpdate.php');
$settings = get_settings();

//Prints non-retired achievements that are not set as hidden
//Hidden achievements include individual variations of gold star and stuff like that
//Retired achievements are defined by the class attribute
function printAchievementDatabase() {
	
	$basic = array();
	$recruit = array();
	$veteran = array();
	$legendary = array();
	
	$sql = "SELECT name, description, class, alignment, image FROM `achievements_new` WHERE `isHidden`=0 ORDER BY alignment";
	$ret = mysql_query($sql);
	while($row = mysql_fetch_assoc($ret)) {
		switch($row['class']) {
			case "e":
				array_push($basic, $row);
				break;
			case "m":
				array_push($recruit, $row);
				break;
			case "h":
				array_push($veteran, $row);
				break;
			case "l":
				array_push($legendary, $row);
				break;
		}
	}
	
	echo "<h2><i>~Basic~</i></h2></br>";
	echo "<table border='1' cellspacing='1' cellpadding='3'>";
	echo "<tr bgcolor='#800000'><th><font color='white'>Achievement</font></th><th><font color='white'>Description</font></th><th><font color='white'>Class</font></th><th><font color='white'>Affiliation</font></th><th><font color='white'>Image</font></th></tr>";
	
	foreach($basic as $val) {
		printLine($val);
	}
	echo "</table><br/><br/>";
	
	echo "<h2><i>~Recruit~</i></h2></br>";
	echo "<table border='1' cellspacing='1' cellpadding='3'>";
	echo "<tr bgcolor='#800000'><th><font color='white'>Achievement</font></th><th><font color='white'>Description</font></th><th><font color='white'>Class</font></th><th><font color='white'>Affiliation</font></th><th><font color='white'>Image</font></th></tr>";
	
	foreach($recruit as $val) {
		printLine($val);
	}
	echo "</table><br/><br/>";
	
	echo "<h2><i>~Veteran~</i></h2></br>";
	echo "<table border='1' cellspacing='1' cellpadding='3'>";
	echo "<tr bgcolor='#800000'><th><font color='white'>Achievement</font></th><th><font color='white'>Description</font></th><th><font color='white'>Class</font></th><th><font color='white'>Affiliation</font></th><th><font color='white'>Image</font></th></tr>";
	
	foreach($veteran as $val) {
		printLine($val);
	}
	echo "</table><br/><br/>";
	
	echo "<h2><i>~Legendary~</i></h2></br>";
	echo "<table border='1' cellspacing='1' cellpadding='3'>";
	echo "<tr bgcolor='#800000'><th><font color='white'>Achievement</font></th><th><font color='white'>Description</font></th><th><font color='white'>Class</font></th><th><font color='white'>Affiliation</font></th><th><font color='white'>Image</font></th></tr>";
	
	foreach($legendary as $val) {
		printLine($val);
	}
	echo "</table><br/><br/>";

}

function printLine(array $values){
	
	switch($values['class']) {
		case "e":
			$values['class'] = "Basic";
			break;
		case "m":
			$values['class'] = "Recruit";
			break;
		case "h":
			$values['class'] = "Veteran";
			break;
		case "l":
			$values['class'] = "Legendary";
			break;
	}
	
	switch($values['alignment']) {
		case "h":
			$values['alignment'] = "Human";
			break;
		case "z":
			$values['alignment'] = "Zombie";
			break;
		case "n":
			$values['alignment'] = "Neutral";
			break;
		case "m":
			$values['alignment'] = "Moderator";
			break;
	}
	$image = $values['image'];
	$values['image'] = "<center><img class=\"smallImg\" src=\"$image\"></img></center>";
	
	echo "<tr bgcolor='#FFFFFF' align='center'>";
	foreach($values as $cur){
		echo "<td style=\"position: relative\">$cur</td>";
	}
	echo "</tr>";
}

//Prints retired achievements that are not hidden
//Retired achievements are defined by the class attribute
function printRetiredAchievements() {
	
$retired = array();
	
	$sql = "SELECT name, description, class, alignment, image FROM `achievements_new` WHERE `isHidden`=0 AND `class`='r' ORDER BY alignment";
	$ret = mysql_query($sql);
	while($row = mysql_fetch_assoc($ret)) {
		array_push($retired, $row);
	}
	
	//echo "<h2><i>~Retired~</i></h2></br>";
	echo "<table border='1' cellspacing='1' cellpadding='3'>";
	echo "<tr bgcolor='#800000'><th><font color='white'>Achievement</font></th><th><font color='white'>Description</font></th><th><font color='white'>Class</font></th><th><font color='white'>Affiliation</font></th><th><font color='white'>Image</font></th></tr>";
	
	foreach($retired as $val) {
		printRetiredLine($val);
	}
	echo "</table><br/><br/>";
	
}

function printRetiredLine(array $values){
	
	$values['class'] = "Retired";
	//$values['alignment'] = "Retired"; WHO THE FUCK WROTE THIS LINE WHY WOULD YOU OVERRIDE DATABASE DATA YOU STUPID
	switch($values['alignment']) {
		case "h":
			$values['alignment'] = "Human";
			break;
		case "z":
			$values['alignment'] = "Zombie";
			break;
		case "n":
			$values['alignment'] = "Neutral";
			break;
		case "m":
			$values['alignment'] = "Moderator";
			break;
	}
	$image = $values['image'];
	$values['image'] = "<center><img class=\"smallImg\" src=\"$image\"></img></center>";
	
	echo "<tr bgcolor='#FFFFFF' align='center'>";
	foreach($values as $cur){
		echo "<td style=\"position: relative\">$cur</td>";
	}
	echo "</tr>";
}



?>
