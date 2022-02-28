<?php

function debug_to_console( $data ) {
	$output = $data;
	if ( is_array( $output ) )
		$output = implode( ',', $output);

	echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
}

//require_once('../includes/load_config.php');
//require_once('../includes/quick_con.php');
require_once('../includes/util.php');
load_config('config.txt');
my_quick_con($config);




$user = mysql_real_escape_string($_POST['username']);
$pass = mysql_real_escape_string($_POST['password']);
$fname = preg_replace("/[^A-Za-z -]/","",$_POST['fname']);
$fname[0] = strtoupper($fname[0]);
$lname = preg_replace("/[^A-Za-z -]/","",$_POST['lname']);
$lname[0] = strtoupper($lname[0]);
$email = mysql_real_escape_string($_POST['email']);
$phone = preg_replace("/[^0-9]/","",$_POST['phone']);
$medical = mysql_real_escape_string($_POST['medicalConcerns']);

if(array_key_exists("schoolSelect", $_POST)){
	$isExistingSchool = true;
	$schoolID = mysql_real_escape_string($_POST['schoolSelect']);
}else{
	$isExistingSchool = false;
	$schoolName = preg_replace("/[^A-Za-z -]/","",$_POST['otherSchool']);
}

$isOld = $_POST['age']=="old";
$isHousing = mysql_real_escape_string($_POST['housing']);
$hasTurnedInWaiver = $_POST['waiver']=="yes";

if($isHousing!="no"){
	//roommate stuff
	$isHousing = mysql_real_escape_string($_POST['housingDuration']);
	$roommates = mysql_real_escape_string($_POST["roommateRequest"]);
}else{
	$roommates = "";
}

//emergency contact info
$emergencyName = mysql_real_escape_string($_POST['emergencyName']);
$emergencyRelation = mysql_real_escape_string($_POST['emergencyRelation']);
$emergencyPhone = mysql_real_escape_string($_POST['emergencyPhone']);

//generate UID
//YES THE FOLLOWING LINES ARE LONG AND MESSY DEAL WITH IT UNLESS YOU KNOW HOW TO FIX IT.
$chars = array();
array_push($chars, "-");
for($i=0; $i<10; $i++){
	array_push($chars, "$i");
}
for($i=0, $c='A'; $i<26; $i++, $c++){
	array_push($chars, "$c");
}

$ret = mysql_query("SELECT MAX(`UID`) AS uid FROM `users`;");
$ret = mysql_fetch_assoc($ret);
$ouid = $ret['uid'];
$uid = "";
if(is_null($ouid)){
	//No users.
	$uid = "PUIV00-";
}else{
	//Take the highest UID, add one, then make a new ID from that.
	$id = str_split(substr($ouid, 4));
	$curCharPos = 2;
	while($curCharPos>-1){
		$curChar = $id[$curCharPos];
		if($curChar != $chars[36]){
			$charIndex = array_search($curChar, $chars);
			$charIndex += 1;
			$id[$curCharPos] = $chars[$charIndex];
			break;
		}else{
			$id[$curCharPos] = $chars[0];
			//and continue to the next one
			$curCharPos -= 1;
		}
	}
	$uid = "PUIV".implode($id);
}

//Hash the password for security
$pass = hash("sha256", $pass);

//convert bools into numbers
$isOld = ($isOld?1:0);
$hasTurnedInWaiver = ($hasTurnedInWaiver?1:0);

//if necessary add a new school and set the school ID
if(!$isExistingSchool){
	mysql_query("INSERT INTO `schools` (`name`) VALUES ('$schoolName');");
	$schoolID = mysql_insert_id();
}

$displayName = $fname." ".$lname;

$sql = "INSERT INTO `users` (`UID`, `uname`, `passwd`, `email`, `isAdmin`, `fname`, `lname`, `displayName`, `SID`, `isOver18`, `hasTurnedInWaiver`, `phoneNumber`, `housing`, `medicalConcerns`, `roommateRequest`, `emergencyContactName`, `emergencyContactRelation`, `emergencyContactPhone`) VALUES ('$uid', '$user', '$pass', '$email', '0', '$fname', '$lname', '$displayName', '$schoolID', '$isOld', '$hasTurnedInWaiver', '$phone', '$isHousing', '$medical', '$roommates', '$emergencyName', '$emergencyRelation', '$emergencyPhone');";
debug_to_console($sql);
//actually save all this to DB
mysql_query($sql);

//Make mods not count as players
mysql_query("UPDATE `users` SET `isAdmin`='1' WHERE `SID` = '0';");

echo mysql_error();
?>
<html><head><?php placeTabIcon(); ?><script type="text/javascript">parent.finalPage('<?php echo $uid; ?>');</script></head></html>