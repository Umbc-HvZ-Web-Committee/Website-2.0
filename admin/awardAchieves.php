<?php
require_once('../pageIncludes/admin/awardAchieves.inc.php');
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
		<?php if($_SESSION['isAdmin'] >= 3){ ?>

			<h1 style="text-align:center">Award an Achievement to an individual player</h1><br/>
			<form action="" method="post">
			Username or Email: <input type="text" name="uname"></input><br/>
			Achievement: 
				<select name="achieve">
					<?php 
					generateList();
					?>
				</select><br/>
			<input type="submit" name="submit" value="Individual Achievement"></input>
			</form>
			
			<h1 style="text-align:center">Award an Achievement to a group (Web Committee only)</h1><br/>
			<p>Write the WHERE clause of a SQL query that defines a group which you want to give the same achievement to in the "Group definition" box. Test this SQL query in PHPMyAdmin or other SQL enviornment before running here to ensure that it is correct. Since this involves directly writing a major portion of a SQL query, out of an adundance of caution, all input strings containing the SQL query terminal token (semicolon) and/or the SQL query comment token (double dash) will be rejected.</p>
			<p>Example test query: <strong>SELECT `uid`, `uname`, `fname`, `lname` FROM `users` WHERE (YOUR INPUT GOES HERE WITHOUT PARANTHESES);</strong></p>
			<form action="" method="post">
			Group definition: <input type="text" name="whereClause"></input><br/>
			Achievement: 
				<select name="achieve">
					<?php 
					generateList();
					?>
				</select><br/>
			<input type="submit" name="submit" value="Group Achievement"></input>
			</form>
			
			<?php 
			echo $status;
				
		} else if($_SESSION['isAdmin'] >= 1){ ?>

			<h1 style="text-align:center">Award an Achievement to an individual player</h1><br/>
			<form action="" method="post">
			Username or Email: <input type="text" name="uname"></input><br/>
			Achievement: 
				<select name="achieve">
					<?php 
					generateList();
					?>
				</select><br/>
			<input type="submit" name="submit" value="Individual Achievement"></input>
			</form>
			
			<?php 
			echo $status;
				
		} else{ ?>
			<h2>Hey, you're not an admin, get out of here!</h2>
		<?php } ?>
		</div>
		<div id="sidebar">
			<div class="section1">
				<?php displayLoginForm();?>
			</div>
		</div>
		<div class="clearfix">&nbsp;</div>
	</div>
	<div id="footer" class="container">
		<?php printFooter(); ?>
	</div>
</div>
</body>
</html>