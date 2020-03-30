<?php
function getVCard($UID){
	$ret = mysql_oneline("SELECT * FROM users WHERE UID='$UID'");
	$lname = $ret['lname'];
	$fname = $ret['fname'];
	$isAdmin = $ret['isAdmin'];
	$email = $ret['email'];
	$pic = $ret['profilePicture'];
	$type = preg_split('/\./', $pic);
	$type = $type[count($type)-1];
	$type = strtoupper($type);
	
	$retval = "";
	
	$retval .= "BEGIN:VCARD\n";
	$retval .= "VERSION:3.0\n";
	$retval .= "N:$lname;$fname\n";
	$retval .= "FN:$fname $lname\n";
	$retval .= "ORG:UMBC HvZ\n";
	$retval .= "TITLE:".($isAdmin?"Admin":"Player")."\n";
	$retval .= "PHOTO;VALUE=URL;TYPE=$type:$pic\n";
	//$retval .= "TEL;TYPE=cell,text,voice:$phoneNumber\n";
	$retval .= "EMAIL;TYPE=PREF,INTERNET:$email\n";
	$retval .= "REV:".date('Y-m-d\TH:i:s\Z')."\n";
	$retval .= "END:VCARD";
	
	return $retval;
}
?>