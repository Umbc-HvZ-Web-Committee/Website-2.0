<?php
function sendMessage($to, $from, $message){
	/* The way this works is, it's based off of laziness. When sending a message, actually send the email,
	 * and keep it logged in the database as having been sent. Then, allow people to view a backlog of the last few
	 * messages that they are currently allowed to see. Thus when switching to zed they can catch up on the backlog some!
	 */
	
	//First off, add it to the message list for later pulling
	mysql_query("INSERT INTO messages(from, to, msg) VALUES ('$from','$to','$message')");
	
	//Next, pull the list of people to send to
	if($to=="a"){
		//TODO
	}elseif($to=="z"){
		//TODO
	}elseif($to=="h"){
		//TODO
	}elseif(startsWtih($to,"LI")){
		//TODO is a mailing list
	}elseif(startsWith($to, "PU")){
		//TODO is a person
	}
	
	//Check if message can be sent without getting myself banned
	$ret = mysql_oneline("SELECT SUM(numMsgs) AS cnt FROM `messageLog` WHERE messageSentTime>(NOW()-INTERVAL 1 HOUR);");
	if($ret['cnt']+count($toList) < 475){ //MAGIC NUMBER
		//If it can, insert it immediately and send it out
		mysql_query("INSERT INTO messageLog(numMsgs) VALUES (".count($toList).");");
		foreach($toList as $to){
			mail($to, "", $message);
		}
		
		return true;
	}else{
		//Otherwise, add it to the queue and alert the caller that it hasn't sent
		return false;
	}
}

function startsWith($haystack, $needle){
	$length = strlen($needle);
	return (substr($haystack, 0, $length) === $needle);
}
?>