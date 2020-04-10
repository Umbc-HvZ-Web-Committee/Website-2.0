<?php
require_once('../includes/util.php');
require_once('../includes/update.php');
load_config('../config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('../includes/loginUpdate.php');
$settings = get_settings();

$blankBio = "NULL";

if($_SESSION['isAdmin'] >= 2) {
	if(isset($_REQUEST['submit'])) {
		$func = $_REQUEST['submit'];
		//echo($func."<br/>");
		if($func=="Insert Bio"){
			$position = requestVar('position');
			$name = requestVar('candidateName');
			$bio = requestVar('candidateBio');
			
			if($position == "none") {
				$GLOBALS['submitMessage'] = "Cannot insert bio for an unspecified position";
			} else if($name == "") {
				$GLOBALS['submitMessage'] = "Cannot insert bio for an unspecified candidate";
			} else if($bio == "") {
				$GLOBALS['submitMessage'] = "Cannot insert bio left blank. Enter \"".$blankBio."\" to give candidate a blank bio";
			} else {
				if($bio == $blankBio) {
					mysql_query("INSERT INTO `election_candidates` (`position`, `name`, `bio`) VALUES ('$position', '$name', '');");
					$GLOBALS['submitMessage'] = "Added candidate with a blank bio";
				} else {
					mysql_query("INSERT INTO `election_candidates` (`position`, `name`, `bio`) VALUES ('$position', '$name', '$bio');");
					$GLOBALS['submitMessage'] = "Added candidate and bio";
				}
			}
		}
		
		if($func=="Insert Voting Option"){
			$prompt = requestVar('votePrompt');
			$response = requestVar('voteResponse');
			
			echo $prompt." ".$response;
			
			if($prompt == "" || $response == "") {
				$GLOBALS['submitMessage'] = "Cannot insert voting option. Voting prompt and/or response was left blank";
			} else {
				$nullUID = $settings['nullUID'];
				mysql_query("INSERT INTO `election_votes` (`uid`, `position`, `voteFor`) VALUES ('$nullUID', '$prompt', '$response');");
				$GLOBALS['submitMessage'] = "Inserted voting option";
			}
		}
		
		if($func=="Update Settings"){
			$writeInThreshold = requestVar('writeInThreshold');
			$voteLink = requestVar('voteLink');
			$voteLock = requestVar('voteLock');
			
			if ($voteLock == "") { $voteLock = $settings['lockVoting']; }
			if ($voteLink == "") { $voteLink = $settings['showVotingLink']; }
			if ($writeInThreshold == "") { $writeInThreshold = $settings['writeInThreshold']; }
			
			mysql_query("UPDATE `settings` SET `value` = '$writeInThreshold' WHERE `key` = 'writeInThreshold';");
			mysql_query("UPDATE `settings` SET `value` = '$voteLink' WHERE `key` = 'showVotingLink';");
			mysql_query("UPDATE `settings` SET `value` = '$voteLock' WHERE `key` = 'lockVoting';");
			
			$GLOBALS['submitMessage'] = "Updated settings";
		}
		
		if($func == "Send Election Results"){
			$fullResults = "";
			
			//load these three arrays
			$curVote = array();
			$positions = array();
			$candidates = array();
						
			//Prepare this array for displaying actual positions to vote ON
			$qury = mysql_query("SELECT position FROM election_votes GROUP BY position ORDER BY position ASC;");
			while($ret = mysql_fetch_assoc($qury)){
				$curVote[$ret['position']] = "";
				$positions[] = $ret['position'];
			}
			
			//Prepare this array for displaying actual "candidates" to vote FOR
			$qury = mysql_query("SELECT position, voteFor AS name FROM election_votes GROUP BY position, voteFor;");
			//$qury = mysql_query("SELECT position FROM election_candidates GROUP BY position, name ORDER BY RAND();");
			while($ret = mysql_fetch_assoc($qury)){
				if(!array_key_exists($ret['position'], $candidates)) $candidates[$ret['position']] = array();
				$candidates[$ret['position']][] = $ret['name'];
			}
			
			$blankVotes = array();
			$qury = mysql_query("SELECT position, voteFor AS name FROM election_votes WHERE uid = '' OR uid = '$nullUID';");
			while($ret = mysql_fetch_assoc($qury)){
				if(!array_key_exists($ret['position'], $blankVotes)) $blankVotes[$ret['position']] = array();
					$blankVotes[$ret['position']][] = $ret['name'];
			}
			foreach($positions as $curPos){
				$fullResults = $fullResults."<u>$curPos</u><br>";
				foreach($blankVotes[$curPos] as $curCan) {
					$numVotes = mysql_oneline("SELECT COUNT(*) cnt FROM election_votes WHERE position = '$curPos' AND voteFor = '$curCan';");
					$numVotes = $numVotes['cnt'] - 1; //Don't count the dummy as a vote
					if($numVotes != 1) {
						$fullResults = $fullResults."'".$curCan."' has ".$numVotes." votes";
					}else {
						$fullResults = $fullResults."'".$curCan."' has 1 vote";
					}
					$fullResults = $fullResults."<br>";
				}
				$fullResults = $fullResults."<br><br>";
			}
			$fullResults = $fullResults."<br><br>";
			
			echo $fullResults;
			
			$uid = $_SESSION['uid'];
			echo $uid." ";
			$user = mysql_oneline("SELECT * FROM `users` WHERE `UID` = '$uid';");
			echo $user['uname']." ";
			$name = $user['fname']." ".$user['lname'];
			echo $name;
			
			$msg = <<<EOF
Hello officer board!
		
This is a test of the new tool in the admin panel to send election results to the officer board. Make sure to change the address to send from this to the officer email so they can get the real thing in the future.
 		
$name has triggered a request to send the results of the current election to the officers. Below you can find the total count of votes casted for each candidate, including write-in options that passed the threshold to be counted. If this is the end of the election, you should check the "Election Editor" page on the admin panel to make sure that $name or some other administrator has not already locked voting so that votes cannot be changed anymore. 

If for any reason any of you suspect that there may have been foul play with the election, whether it be website tampering or something else, make sure to consider the following: 

- Make sure that NOBODY clears the election results until this situation has been resolved
- Your best chance at finding answers is to work with everyone that has website access
- Accusations of tampering with the election or any other foul play are very serious and should not be made without evidence
- Emergency access-revoke for web committee exists for a reason. Any individual officer can call for this.

Here are the results for the election:

$fullResults

Happy officering!

~ The Website ~
THIS IS AN AUTOMATED MESSAGE.
EOF;
			
			mail("mosier1@umbc.edu", "Election Results", $msg);
		}
		
		if($func == "Clear Election"){
			mysql_query("UPDATE `settings` SET `value` = 'lock' WHERE `key` = 'lockVoting';");
			mysql_query("UPDATE `settings` SET `value` = 'closed' WHERE `key` = 'showVotingLink';");
		}
		
		if ($func == "Clear All Votes" || $func == "Clear Election"){
			mysql_query("DELETE FROM `election_votes` WHERE 1");
		}
		
		if ($func == "Clear All Bios" || $func == "Clear Election"){
			mysql_query("DELETE FROM `election_candidates` WHERE 1");
		}
	}
}

?>