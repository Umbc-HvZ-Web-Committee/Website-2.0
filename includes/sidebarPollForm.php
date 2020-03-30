<?php
global $config;
//Login script.
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) include $_SERVER['DOCUMENT_ROOT'].$config['folder']."/includes/loginUpdate.php";

$ret = mysql_oneline("SELECT * FROM `poll_questions` WHERE `isActive`=1;");
if(!$ret)
{
	echo "<center><h3><b>No active polls</b></h3></center>";

}
else
{
	$QID = $ret['QID'];
	$question = $ret['question'];
	$isOpen = $ret['isOpen'];
	
	if(!isLoggedIn())
	{
		echo"<center><h3><i>You must be logged in to vote</i></h3></center>";
	}
	else
	{
		$playerData = mysql_oneline("SELECT * FROM users WHERE UID='$uid'");
		
		$curVote = -1;
		$options = array();
		$voted = false;
		
		$query = mysql_query("SELECT `option`, `OID` FROM poll_options WHERE `QID`=$QID ORDER BY OID ASC;");
		while($ret = mysql_fetch_assoc($query)){
			$tempArray = array();	
			array_push($tempArray, $ret['OID']);
			array_push($tempArray, $ret['option']);
			array_push($options, $tempArray);
		}			
		
		$ret = mysql_oneline("SELECT COUNT(*) AS cnt FROM `poll_votes` WHERE `UID`='$uid' AND `QID`=$QID;");
		if($ret['cnt']!=0)
		{
			$query = mysql_query("SELECT `OID` FROM poll_votes WHERE UID='$uid';");
			while($ret = mysql_fetch_assoc($query))
			{
				$curVote = $ret['OID'];
			}
		}
		
		//save any vote submission data, if it exists
		if(array_key_exists("submit", $_POST)){	
			$OID = $_POST["$QID"];
			$optionName = "";
			for($i = 0; $i < sizeof($options); $i++)
			{
				if($options[$i][0] == $OID)
				{
					$optionName = $options[$i][1];
				}
			}
			$name = $playerData['fname']." ".$playerData['lname'];
			if($curVote != -1)
			{
				mysql_query("UPDATE `poll_votes` SET `OID`=$OID, `option`='$optionName' WHERE `UID`='$uid' AND `QID`='$QID';");
			}
			else
			{
                mysql_query("INSERT INTO `poll_votes`(`QID`, `OID`, `option`, `UID`, `name`) VALUES ('$QID','$OID','$optionName','$uid','$name');");
			}
			$voted = true;
		}
		
		$ret = mysql_oneline("SELECT COUNT(*) AS cnt FROM `poll_votes` WHERE `UID`='$uid' AND `QID`=$QID;");
		if($ret['cnt']!=0)
		{
			$query = mysql_query("SELECT `OID` FROM poll_votes WHERE UID='$uid';");
			while($ret = mysql_fetch_assoc($query))
			{
				$curVote = $ret['OID'];
			}
		}
		
		//present voting options
		echo "<b>$question</b>";
		echo "<br/><br/>";
		if($isOpen)
		{
			echo '<form method="post" action="">';
			for($i = 0; $i < sizeof($options); $i++)
			{
				if($curVote == $options[$i][0])
				{
					echo "<label for='".$options[$i][1]."'><input type='radio' id='".$options[$i][0]."' name='".$QID."' value='".$options[$i][0]."' checked='checked'>".$options[$i][1]."</label></br>";		
				}
				else 
				{
					echo "<label for='".$options[$i][1]."'><input type='radio' id='".$options[$i][0]."' name='".$QID."' value='".$options[$i][0]."'>".$options[$i][1]."</label></br>";
				}
			}
			echo '</br><input type="submit" name="submit" value="Submit vote"></form>';
			
			if($voted)
			{
				echo "<br/><h4 style=\"text-align: center\">Vote saved! </h4>";
			}
		}
		else
		{
			echo "<center><h3><b>Voting is closed</b></h3></center>";
		}
	}
}