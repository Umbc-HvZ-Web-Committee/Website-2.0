<?php
require_once('../includes/util.php');
require_once('../includes/update.php');
load_config('../config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('../includes/loginUpdate.php');
$settings = get_settings();

if($_SESSION['isAdmin'] >= 2) {
	if(isset($_REQUEST['submit'])) {
		$func = $_REQUEST['submit'];
		//echo($func."<br/>");
		if($func=="Insert Bio"){
			$position = requestVar('position');
			$name = requestVar('candidateName');
			$bio = requestVar('candidateBio');
			echo "Acknowledged INSERT_BIO(".$position.", ".$name.", ".$bio.")<br/>";
		}
		
		if($func=="Insert Voting Option"){
			$prompt = requestVar('prompt');
			$response = requestVar('response');
			echo "Acknowledged INSERT_VOTING_OPTION(".$prompt.", ".$response.")<br/>";
		}
		
		if($func=="Update Settings"){
			$writeInThresh = requestVar('writeInThresh');
			$voteLink = requestVar('voteLink');
			$voteLock = requestVar('voteLock');
			echo "Acknowledged UPDATE_SETTINGS(".$writeInThresh.", ".$voteLink.", ".$voteLock.")<br/>";
		}
		
		if($func=="End Election"){
			echo "Acknowledged END_ELECTION<br/>";
		}
		
		if($func=="Clear Election" or $func=="End Election"){
			echo "Acknowledged CLEAR_ELECTION<br/>";
		}
		
		if($func=="Clear Votes" or $func=="Clear Election" or $func=="End Election"){
			echo "Acknowledged CLEAR_VOTES<br/>";
		}
		
		if($func=="Clear Bios" or $func=="Clear Election" or $func=="End Election"){
			echo "Acknowledged CLEAR_BIOS<br/>";
		}
	}
}

?>