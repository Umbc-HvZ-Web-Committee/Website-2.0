<?php
require_once('../includes/util.php');
require_once('../includes/update.php');
load_config('../config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('../includes/loginUpdate.php');
$settings = get_settings();

if($_SESSION['isAdmin']==1){
	if(isset($_REQUEST['view'])){
	$questionToView = $_POST['questionview'];
	
	$optionNameArray = array();
	$optionNumberArray = array();
	
	$sql = "SELECT * FROM `poll_options` WHERE `QID`='$questionToView';";
	$ret = mysql_query($sql);
	while($row = mysql_fetch_array($ret))
	{
		array_push($optionNameArray, $row['option']);
		array_push($optionNumberArray, $row['OID']);
	}
	
	$ctr = 0;
	foreach($optionNumberArray as $option)
	{
		$sql = "SELECT COUNT(*) AS count FROM `poll_votes` WHERE `QID`='$questionToView' AND `OID`='$optionNumberArray';";
		$voteCount = mysql_fetch_array($ret);
		$voteCount = $voteCount['count'];
		
		//echo "$optionNameArray[$ctr] - $voteCount votes<br/>";
				 
		$ctr++;
	}
	
		
	}elseif(isset($_REQUEST['addQuestion'])){
		$questionToInsert = $_POST['questiontoadd'];
		$questionToInsert = mysql_real_escape_string($questionToInsert);
		$sql = "INSERT INTO `poll_questions`(`question`, `isOpen`, `isActive`) VALUES ('$questionToInsert', 1, 0);";
		mysql_oneline($sql);
		
		$GLOBALS['submitMessage'] = "Question added";	
	
	}elseif (isset($_REQUEST['addAnswers'])) {
		$optionToInsert = $_POST['optiontoadd'];
		$QID = $_POST['questionforoptions'];
		$optionToInsert = mysql_real_escape_string($optionToInsert);
		
		$sql = "INSERT INTO `poll_options`(`QID`, `option`) VALUES ('$QID','$optionToInsert');";
		mysql_oneline($sql);
		
		$GLOBALS['submitMessage'] = "Option added";
	
	}elseif (isset($_REQUEST['setActive'])) {
		$QID = $_POST['questionforactive'];
		$sql = "UPDATE `poll_questions` SET `isActive`=1 WHERE `QID`='$QID';";
		mysql_oneline($sql);
		
		$GLOBALS['submitMessage'] = "Question set as active";
	}
}

function displayQuestionList() {
	$sql = "SELECT * FROM `poll_questions` ORDER BY `QID` ASC;";
	$ret = mysql_query($sql);
	while($row = mysql_fetch_array($ret)) {
		$value = $row['QID'];
		$name = $row['question'];
		echo "<option value='$value'>$name</option>";
	}
}

?>
