<?php
/* This is outdated.
$ref = $_SERVER['HTTP_REFERER'];
$togo = array();
if(preg_match("/umbchvz\.com(.*)/", $ref, $togo)){
	if($togo[1]!="/" && $togo[1]!=""){
		header("Location: ".$togo[1]);
		exit();
	}
}
header("Location: home.php");*/
include 'home.php';
?>
