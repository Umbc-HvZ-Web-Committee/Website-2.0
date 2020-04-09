<?php
require_once('../pageIncludes/admin/editVoting.inc.php'); //MAKE SURE THIS FILE PATH IS ACTUALLY CORRECT
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php placeTabIcon(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>UMBC HvZ</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="/style.css" rel="stylesheet" type="text/css" media="all" />
<link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'/>
<?php htmlHeader(); ?>
</head>
<body>
<a name="top"></a>
<div id="wrapper">
	<?php pageHeader(); ?>
	<div id="page" class="container">
		<div id="content">

		<?php if($_SESSION['isAdmin'] >= 2){ ?>
			<h2>Election Editor</h2>
			<br/><br/>
			
			<h4><b>WARNING: This page is not operational. Do not touch anything on this page if you don't know what it does!</b></h4>
			<br/><br/>
		
			<h3>Inserting Bios</h3>
			<br/><br/>
			<form action="" method="post">
			<select name=\"positions\">
			<option value=\"pres\">President</option>
			<option value=\"vp\">VP</option>
			<option value=\"sec\">Secretary</option>
			<option value=\"tr\">Treasurer</option>
			<option value=\"pr\">PR</option>
			<option value=\"web\">Web Committee</option>
			</select><br/>
			Name: <input type="text" name="candidateName" id="candidateName"/></br>
			Bio: <input type="text" name="candidateBio" id="candidateBio"/></br>
			<input type="submit" name="submit" value="Insert Bio"/></br><br/>
			</form><br/>
			<br/>
			
			<h3>Inserting Voting Options (Candidates)</h3>
			<br/><br/>
			<form action="" method="post">
			Voting Prompt (Position): <input type="text" name="votePrompt" id="candidateName"/></br><br/>
			Voting Response (Candidate): <input type="text" name="voteReponse" id="candidateBio"/></br><br/>
			<input type="submit" name="submit" value="Insert Voting Option"/></br><br/>
			</form><br/>
			<br/>
			
			<h3>Voting Settings</h3>
			<form action="" method="post">
			Write-In Threshold (Minimum number of votes to count write-in options): <input type="text" name="writeInThresh" id="writeInThresh"/></br><br/>
			Voting Link: <br/>
			<label for="voteLink"><input type="radio" name="voteLink" value="closed"/>No elections in progress</label></br>
			<label for="voteLink"><input type="radio" name="voteLink" value="soonClosed"/>Election happening soon</label></br>
			<label for="voteLink"><input type="radio" name="voteLink" value="soonOpen"/>Elections happening soon (show link)</label></br>
			<label for="voteLink"><input type="radio" name="voteLink" value="open"/>Election in progress (show link)</label></br>
			<br/>
			Vote Locking: <br/>
			<label for="voteLock"><input type="radio" name="voteLock" value="lock"/>Lock the election</label></br>
			<label for="voteLock"><input type="radio" name="voteLock" value="unlock"/>Unlock the election</label></br>
			<br/>
			<input type="submit" name="submit" value="Update Settings"/></br>
			</form><br/>
			
			<h3>Fun Buttons<h3>
			<input type="submit" name="clearBios" value="Clear All Bios"/>
			<input type="submit" name="clearVotes" value="Clear All Votes"/>
			<input type="submit" name="clear" value="Clear Election"/>
			<input type="submit" name="end" value="End Election"/>
			</br/><br/><p>"Clear Election" will clear all bios and all votes. "End Election" will do that and will email the results to the officers</p>
			
		<?php }else{ ?>
			<h2>Hey, you're not an admin, get out of here!</h2>
		<?php } ?>
		</div>
		<?php printSidebar(); 
		?>
		<div class="clearfix">&nbsp;</div>
	</div>
	<div id="footer" class="container">
		<?php printFooter(); ?>
	</div>
</div>
</body>
</html>