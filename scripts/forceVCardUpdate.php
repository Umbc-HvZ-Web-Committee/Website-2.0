<?php
require_once '../includes/util.php';
load_config('../config.txt');
my_quick_con($config);
require_once '../includes/vcard.php';

echo "INSERT INTO ofVCard(username, vcard) VALUES \n";

$q = mysql_query("SELECT uname, UID FROM users");
while($ret = mysql_fetch_assoc($q)){
	$uid = $ret['UID'];
	$username = $ret['uname'];
	$vcard = getVCard($uid);
	echo "('$username', '$vcard'),\n";
}

?>