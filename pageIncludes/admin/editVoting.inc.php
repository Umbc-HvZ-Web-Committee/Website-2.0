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
			$prompt = requestVar('votePrompt');
			$response = requestVar('voteResponse');
			echo "Acknowledged INSERT_VOTING_OPTION(".$prompt.", ".$response.")<br/>";
		}
		
		if($func=="Update Settings"){
			$writeInThresh = requestVar('writeInThresh');
			$voteLink = requestVar('voteLink');
			$voteLock = requestVar('voteLock');
			echo "Acknowledged UPDATE_SETTINGS(".$writeInThresh.", ".$voteLink.", ".$voteLock.")<br/>";
		}
	}
	if($_REQUEST["End Election"]){
		echo "Acknowledged END_ELECTION<br/>";
	}
	
	if($_REQUEST["Clear Election"] or $_REQUEST["End Election"]){
		echo "Acknowledged CLEAR_ELECTION<br/>";
	}
	
	if($_REQUEST["Clear Votes"] or $_REQUEST["Clear Election"] or $_REQUEST["End Election"]){
		echo "Acknowledged CLEAR_VOTES<br/>";
	}
	
	if($_REQUEST["Clear Bios"] or $_REQUEST["Clear Election"] or $_REQUEST["End Election"]){
		echo "Acknowledged CLEAR_BIOS<br/>";
	}
}

?>