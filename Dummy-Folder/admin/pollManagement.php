<?php
require_once('../pageIncludes/admin/pollManagement.inc.php');
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
		<?php if($_SESSION['isAdmin'] >= 2){
			echo "<br/>";
			if($GLOBALS['submitMessage']!="") echo "<h3>${GLOBALS['submitMessage']}</h3>" ?>
			
			<h2>View results for a poll</h2>
			<form action="" method="post">		
				<select name="questionview">
				<?php displayQuestionList(); ?>
				</select>
				<br/>
				<input type="submit" name="view" value="View"/>
			</form><br/>
			
			<h2>Add options to existing question</h2>
			<form action="" method="post">
				<select name="questionforoptions">
				<?php displayQuestionList(); ?>
				</select>
				<br/>
				Option to add: <input type="text" name="optiontoadd"></input>
				<br/>
				<input type="submit" name="addAnswers" value="Add Option"/>
			</form><br/>
			
			<h2>Set active question</h2>
			<form action="" method="post">
			<select name="questionforactive">
			<?php displayQuestionList(); ?>
			</select>
			<br/>
			<input type="submit" name="setActive" value="Set as Active"/>
			</form>
			<br/>
			
			<h2>Create a new question</h2>
			<i>*Note that you need to add options using the above form*</i>
			<form action="" method="post">
				Question to add: <input type="text" name="questiontoadd"></input>
				<input type="submit" name="addQuestion" value="Add Question"/>
			</form><br/>

			<?php echo "$html";?>
			<br/>
		<? }else{ ?>
			<h2>Hey, you're not an admin, get out of here!</h2>
		<? } ?>
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
<?php

?>
