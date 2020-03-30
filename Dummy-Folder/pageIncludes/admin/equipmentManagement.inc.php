<?php
require_once('../includes/util.php');
load_config('../config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('../includes/loginUpdate.php');
$settings = get_settings();

if(isset($_REQUEST['returnEquipment'])){
	$eid = requestVar("type").requestVar("eid");

	$ret = mysql_oneline("SELECT CONCAT(fname,' ',lname) AS name, loanedTo FROM equipment LEFT JOIN users ON loanedTo=UID WHERE EID='$eid';");
	$name = $ret['name'];
	$loanedTo = $ret['loanedTo'];

	if($loanedTo != null && substr($loanedTo, 0, 2)!="US"){
		$name = "'".$loanedTo."'";
	}

	mysql_query("UPDATE equipment SET loanedTo='IHAVEIT' WHERE EID='$eid';");

	$GLOBALS['equipMessage']="$eid has been returned from $name to its owner.";
}

if(isset($_REQUEST['equipmentStatus'])){
	$eid = requestVar("eid");

	$ret = mysql_oneline("SELECT CONCAT(fname,' ',lname) AS name, loanedTo FROM equipment LEFT JOIN users ON loanedTo=UID WHERE EID='$eid';");
	$name = $ret['name'];
	$loanedTo = $ret['loanedTo'];

	if($loanedTo != null && substr($loanedTo, 0, 2)!="US"){
		$name = "'".$loanedTo."'";
	}

	$GLOBALS['equipMessage']="$eid is currently loaned to $name.";
}

if(isset($_REQUEST['playerEquipment'])){
	$playerID = requestVar('playerID');
	
	//get UID, assuming you can
	$udata = getUID($playerID);
	if($udata){
		//found user
		$name = $udata['fname']." ".$udata['lname'];
		$qury = mysql_query("SELECT EID FROM equipment WHERE loanedTo='{$udata['UID']}';");
		$equipment = "";
		while($ret = mysql_fetch_assoc($qury)){
			$equipment .= $ret['EID']."<br/>";
		}
		if($equipment=="") $equipment = "Nothing";
		$GLOBALS['equipMessage']="$name has borrowed:<br/>$equipment";
	}else{
		$GLOBALS['equipMessage']="ID unknown.";
	}
}
?>