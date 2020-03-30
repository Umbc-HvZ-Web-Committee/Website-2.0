<?php
function my_quick_con($config){
	$u = $config['mysql_user'];
	$p = $config['mysql_pass'];
	$d = $config['mysql_db'];
	if($config['debug']) $con = mysql_connect("colacadstink.getmyip.com", $u, $p);
	else $con = mysql_connect("localhost", $u, $p);
	mysql_select_db($d, $con);
	return $con;
}

function mysql_oneline($query){
	return mysql_fetch_assoc(mysql_query($query));
}

function isLoggedIn(){
	return (isset($_SESSION['inv_uid']) && $_SESSION['inv_uid']!="");
}
?>
