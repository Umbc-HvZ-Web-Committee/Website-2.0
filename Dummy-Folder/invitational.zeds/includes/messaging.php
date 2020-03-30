<?php
define( "ACCOUNT_SID", 'ACda9f0ba7390345e49029055bf14e6b71' );
define( "AUTH_TOKEN", 'b6898b85610c2f097e5fdd5b3c42c055' );
define( "FROM_NUMBER", '2404286224' );

require dirname(__FILE__).'/Twilio.php';

function sendMessage(array $toList, $message){
	$client = new Services_Twilio(ACCOUNT_SID, AUTH_TOKEN);
	
	foreach($toList as $to) $client->account->sms_messages->create(FROM_NUMBER, $to, $message);
	
	return "Message sent.";
}
?>