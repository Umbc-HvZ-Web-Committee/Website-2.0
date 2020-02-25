<?php
require_once('../pageIncludes/admin/index.inc.php');
$playerData = mysql_oneline("SELECT * FROM users WHERE UID='{$_SESSION['uid']}'");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php placeTabIcon(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>UMBC HvZ - Admin Panel</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="/style.css" rel="stylesheet" type="text/css" media="all" />
<link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css' />
<?php htmlHeader(); ?>
</head>
<body>
<div id="wrapper">
	<?php pageHeader(); ?>
	<div id="page" class="container">
		<div id="content">
		<?php 
		$display = false;
		
		if($_SESSION['isAdmin'] == 2){ 
			$display = true;
			?>
			<h2>Admin Panel</h2><br/>
			<h3><a href="meetings.php">Meeting utilities (sign in/creation/resolution)</a></h3>
			<h3><a href="blogPost.php">Create a blog post.</a></h3>
			<h3><a href="awardAchieves.php">Award an Achievement</a></h3>
			<h3><a href="equipmentManagement.php">Equipment Management</a></h3>
			<h3><a href="addfaq.php">FAQ Editor</a></h3>
			<h3><a href="pollManagement.php">Poll Editor</a></h3>
			<h3><a href="viewVotingMembers.php">View Voting Members</a></h3>
			<br/>
			<?php
		}
		
		if($_SESSION['isAdmin'] == 1){ 
			$display = true;
			?>
			<h2>Admin Panel</h2><br/>
			<h3><a href="meetings.php">Meeting utilities (sign in/creation/resolution)</a></h3>
			<h3><a href="viewVotingMembers.php">View Voting Members</a></h3>
			<br/>
			<?php
		}
			
		if($playerData['isLongGameAuthed'] == 2){
			$display = true;
			?>
			<h2>Long Game Utilities</h2><br/>
			<h3><a href="longGameRegister.php">Long game utilities (registration/creation)</a></h3>
			<h3><a href="points.php">Long game points utilities (registration/creation)</a></h3>		
			<h3><a href="ozSelect.php">OZ viewing &amp; selection</a></h3>
			<?php 
		}
		
		if($playerData['isLongGameAuthed'] == 1){
			$display = true;
			?>
			<h2>Long Game Utilities</h2><br/>
			<h3><a href="longGameRegister.php">Long game utilities (registration/creation)</a></h3>
			<h3><a href="points.php">Long game points utilities (registration/creation)</a></h3>		
			<h3><a href="ozSelect.php">OZ viewing &amp; selection</a></h3>
			<?php 
		}
		?>
		
			
		<? if(!$display) { ?>
			<h2>Hey, you're not an admin, get out of here!</h2>
		<? }else{
			echo "<br/><br/>";
			echo "Your basic admin level is ";
			echo $_SESSION['isAdmin'];
			echo "<br/>Your long game admin level is ";
			echo $playerData['isLongGameAuthed'];
			echo "<br/>";
		}?>
		</div>
		<div id="sidebar">
			<div class="section1">
				<?php displayLoginForm();?>
			</div>
		</div>
	</div>
	<div id="footer" class="container">
		<?php printFooter(); ?>
	</div>
</div>
</body>
</html>