<?php
require_once('../includes/util.php');
require_once('../includes/achievementUpdateFunctions.php');
load_config('../config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('../includes/loginUpdate.php');
$settings = get_settings();

if(isset($_REQUEST['submit'])){
	
	$func = $_REQUEST['submit'];
	$value = $_REQUEST['achieve'];
	//echo($func);
	if($func=="Individual Achievement"){
		$playerID = requestVar('uname');
		$ret = getUID($playerID);
		if(!$ret) {
			$status = "</br><h3>Player not found.</h3>";
		} else {
				
			$uid = $ret['UID'];
			$email = $ret['email'];
			$name = $ret['fname']." ".$ret['lname'];
			
			$achieveLookupSql = "SELECT * FROM `achievements_new` WHERE `key`='$value'";
			$achieveLookupRet = mysql_oneline($achieveLookupSql);
			$achieveAID = $achieveLookupRet['AID'];
			$achieveName = $achieveLookupRet['name'];
			
			if(!giveAchieve($achieveAID, $uid))
			{
				$status = "</br><h3>Player already has that achievement.</h3>";
			} else {
				$status = "</br><h3>".$name." has been awarded the achievement ".$achieveName.".</h3>";
			}
			
			
			$sql = "SELECT * FROM `userAchieveLink_new` WHERE UID='$uid'";
			$retTwo = mysql_query($sql);
			while($row = mysql_fetch_assoc($retTwo)) {
				if($achieveAID == $row['AID']) {
					$found = true;
					break;
				}
			}
		}
	}else if($func=="Group Achievement"){ 
		$whereClause = requestVar('whereClause');
		echo $whereClause;
		
		//Check for safe query before proceeding
		
		//The condition below is equivalent to checking if either substring is in $whereClause as explained below:
		//the strpos function returns the index value of the first occurence of the second paramater in the first paramater,
		//and two characters cannot occupy the same index of a string, so for strpos() to return the same value for both,
		//that would mean neither substring is in the string $whereClause (which is what we want), 
		//which would return a value of false for both, and since false == false, that would indicate that neither
		//SQL token of interest is in the provided input, making this query safe for the level of scrutiny we provide here.
		//Thus, we may proceed.
		if(strpos($whereClause, ";") != strpos($whereClause, "--")) {
			//Bad query
			$status = "</br><h3>Illegal query provided!</h3>";
			echo "<br/>illegal query<br/>";
		} else {
			echo "<br/>legal query";
			echo "<br/>".strpos($whereClause, ";")." ".strpos($whereClause, "--");
			$whereClause = str_replace("\'", "'", $whereClause);
			echo "<br/>SELECT `uname` FROM `users` WHERE ".$whereClause;
			$players = mysql_query("SELECT `uname` FROM `users` WHERE $whereClause;");
			$status = "";
			
			foreach($players as $uname) {
				$playerID = $uname['uname'];
				echo "<br/>iterating over player ".$playerID;
				$ret = getUID($playerID);
				if(!$ret) {
					$status = $status."</br><h3>Player ".$playerID." not found.</h3>";
				} else {
						
					$uid = $ret['UID'];
					$email = $ret['email'];
					$name = $ret['fname']." ".$ret['lname'];
					
					$achieveLookupSql = "SELECT * FROM `achievements_new` WHERE `key`='$value'";
					$achieveLookupRet = mysql_oneline($achieveLookupSql);
					$achieveAID = $achieveLookupRet['AID'];
					$achieveName = $achieveLookupRet['name'];
					
					if(!giveAchieve($achieveAID, $uid))
					{
						$status = $status."</br><h3>Player ".$name." already has that achievement.</h3>";
						echo "<br/>Player has achievement";
					} else {
						$status = $status."</br><h3>".$name." has been awarded the achievement ".$achieveName.".</h3>";
						echo "<br/>Player does not have achievement";
					}
					
					
					$sql = "SELECT * FROM `userAchieveLink_new` WHERE UID='$uid'";
					$retTwo = mysql_query($sql);
					while($row = mysql_fetch_assoc($retTwo)) {
						if($achieveAID == $row['AID']) {
							$found = true;
							break;
						}
					}
				}
				echo "<br/>iterated";
			}
			echo "end loop";
			if($status = "") {
				$status = "</br><h3>Provided WHERE clause does not describe any players</h3>";
			}else {
				echo "<br/>".$status;
			}
		}
	}
} else {
	$status = "";
}


function generateList() {
	echo "<optgroup label='--- Basic ---'>";
	$sql = "SELECT * FROM `achievements_new` WHERE class='e';";
	$ret = mysql_query($sql);
	while($row = mysql_fetch_assoc($ret)) {
		$value = $row['key'];
		$name = $row['name'];
		echo "<option value='$value'>$name</option>";
	}
	echo "<option disabled></option></optgroup>";

	echo "<optgroup label='--- Recruit ---'>";
	$sql = "SELECT * FROM `achievements_new` WHERE class='m';";
	$ret = mysql_query($sql);
	while($row = mysql_fetch_assoc($ret)) {
		$value = $row['key'];
		$name = $row['name'];
		echo "<option value='$value'>$name</option>";
	}
	echo "<option disabled></option></optgroup>";
		
	echo "<optgroup label='--- Veteran ---'>";
	$sql = "SELECT * FROM `achievements_new` WHERE class='h';";
	$ret = mysql_query($sql);
	while($row = mysql_fetch_assoc($ret)) {
		$value = $row['key'];
		$name = $row['name'];
		echo "<option value='$value'>$name</option>";
	}
	echo "<option disabled></option></optgroup>";
	
	if($_SESSION['isAdmin'] >= 2) {
		echo "<optgroup label='--- Legendary ---'>";
		$sql = "SELECT * FROM `achievements_new` WHERE class='l';";
		$ret = mysql_query($sql);
		while($row = mysql_fetch_assoc($ret)) {
			$value = $row['key'];
			$name = $row['name'];
			echo "<option value='$value'>$name</option>";
		}
	}
	echo "</optgroup>";
}



?>