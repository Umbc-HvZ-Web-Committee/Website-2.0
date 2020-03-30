<?php
//The following generates and/or validates $salt.  This is important for validating the password submitted by the user in a secure manner.
//Essentially, the user's password is hashed, the salt is appended to the end, and the whole thing is hashed again.  This means that since
//the salt changes every time you load the page, and the odds of getting the same salt twice is incredibly low, even if someone is listening
//to you logging into the server, they can't just play back the same thing you said and log in.  Hooray security!

if(!isset($_SESSION)) session_start();

if(array_key_exists("inv_salt",$_SESSION)){
	if(array_key_exists("salt", $_POST)){
		if(!$_SESSION['inv_salt']==$_POST['salt']){
			//If the salts match then all is right witht the world, continue with that saved salt
			//If the salts don't match then someone is trying to screw with the server.
			return false;
		}
	}else{
		//If the session has one, but you didn't just submit this page, reset the salt
		$_SESSION['inv_salt'] = uniqid();
	}
}else{
	if(array_key_exists("salt", $_POST)){
		//If the session doesn't have a salt, but the post does, someone is screwing with the server again
		return false;
	}else{
		//If there isn't a salt, make a new salt up.
		$_SESSION['inv_salt'] = uniqid();
	}
}
?>