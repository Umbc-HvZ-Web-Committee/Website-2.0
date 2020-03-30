<?php
/*function my_quick_con($config){
	$u = $config['mysql_user'];
	$p = $config['mysql_pass'];
	$d = $config['mysql_db'];
	if($config['debug']) $con = mysql_connect("umbchvz.com", $u, $p);
	else $con = mysql_connect("localhost", $u, $p);
	mysql_select_db($d, $con);
	return $con;
}

function mysql_oneline($query){
	return mysql_fetch_assoc(mysql_query($query));
}

function isLoggedIn(){
	return (isset($_SESSION['inv_uid']) && $_SESSION['inv_uid']!="");
}*/

function my_quick_con($config){
	$u = $config['mysql_user'];
	$p = $config['mysql_pass'];
	$d = $config['mysql_db'];
	if($config['debug'])
		$con = mysql_connect("umbchvz.com", $u, $p);
	else
		$con = mysql_connect("localhost", $u, $p);
	mysql_select_db($d, $con);
	return $con;
}

function get_settings(){
	$result = mysql_query("SELECT * FROM `settings`;");
	$settings = array();
	while($ret = mysql_fetch_array($result)){
		$settings[$ret['key']]=$ret['value'];
	}
	return $settings;
}

function set_setting($key, $value){
	mysql_query("UPDATE `settings` SET `value`='$value' WHERE `key`='$key';");
	return get_settings();
}

function mysql_oneline($query){
	return mysql_fetch_assoc(mysql_query($query));
}

function isLoggedIn(){
	return (isset($_SESSION['uid']) && $_SESSION['uid']!="");
}
?>
