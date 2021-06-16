<?php
require_once('../pageIncludes/admin/editVoting.inc.php'); //MAKE SURE THIS FILE PATH IS ACTUALLY CORRECT
$settings = get_settings();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php placeTabIcon(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>UMBC HvZ - Admin Panel</title>
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
			
			<h4>WARNING: This page is not well-tested for input validation and is still kinda buggy. Please be careful not to blow anything up while interacting with this page.</h4>
			<br/><br/>
		
			<?php if($GLOBALS['submitMessage']!="") echo "<h4><b>${GLOBALS['submitMessage']}</b></h4><br/><br/>" ?>
			
			<h3>Inserting Bios</h3>
			<br/><br/>
			
			<form action="" method="post">
			Position: <select name="position" id="position">
			<option value='none'></option>
			<option value="President">President</option>
			<option value="Vice President">Vice President</option>
			<option value="Secretary">Secretary</option>
			<option value="Treasurer">Treasurer</option>
			<option value="Recruitment & Advertising/PR">Recruitment & Advertising/PR</option>
			<option value="Web Committee">Web Committee</option>
			</select><br/><br/>
			Name: <input type="text" name="candidateName" id="candidateName"/></br><br/>
			Bio: <input type="text" name="candidateBio" id="candidateBio"/></br><br/>
			<input type="submit" name="submit" value="Insert Bio"/></br><br/>
			</form><br/>
			<br/>
			
			<h3>Inserting Voting Options (Candidates)</h3>
			<br/><br/>
			
			<form action="" method="post">
			Voting Prompt (Position): <input type="text" name="votePrompt" id="votePrompt"/></br><br/>
			Voting Response (Candidate): <input type="text" name="voteResponse" id="voteResponse"/></br><br/>
			<input type="submit" name="submit" value="Insert Voting Option"/></br><br/>
			</form><br/>
			<br/>
			
			<h3>Voting Settings</h3>
			<br/><br/>
			
			<form action="" method="post">
			Write-In Threshold:<br/> 
			Minimum votes to count write-ins (currently set to <?php echo $settings['writeInThreshold']; ?>)<br/>
			Enter number below, or leave blank for no change<br/> 
			<input type="text" name="writeInThreshold" id="writeInThreshold"/></br><br/>
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
			<br/>
			
			<h3>Fun Buttons<h3>
			<br/><br/>
			
			<form action="" method="post">
			<input type="submit" name="submit" value="Clear All Bios"/><br/><br/>
			<input type="submit" name="submit" value="Clear All Votes"/><br/><br/>
			<input type="submit" name="submit" value="Clear Election"/><br/><br/>
			<input type="submit" name="submit" value="Send Election Results"/><br/><br/>
			"Clear Election" will lock voting and clear all bios and votes.<br/><br/>
			</form>
			<br/>
			
			<h2>Voting Results</h2>
			<br/><br/>
			
			<?php
			$electionResults = getVotingResults();
			echo $electionResults;
			?>
			
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