<?php
require_once('pageIncludes/passwordRecovery.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php placeTabIcon(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>UMBC HvZ - Password Recovery</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="/style.css" rel="stylesheet" type="text/css" media="all" />
<link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'/>
<?php htmlHeader(); ?>
</head>
<body>
<div id="wrapper">
	<?php pageHeader(); ?>
	<div id="page" class="container">
		<div id="content">
			<h2><?php echo $result;?></h2>
			<table>
				<tr>
					<td><h3>I know my username, but forgot my password</h3></td>
					<td><h3>I forgot my username and password</h3></td>
				</tr><tr>
					<td><form method="post" action="">
						Username:<br/>
						<input type="text" name="username"/><br/>
						<input type="submit" name="reset" value="Reset password"/>
					</form></td>
					<td><form method="post" action="">
						Email address:<br/>
						<input type="text" name="email"/><br/>
						<input type="submit" name="reset" value="Recover account"/>
					</form></td>
				</tr>
			</table>
			<h3>I have a password reset code already</h3>
			<div><form method="post" action="">
				Reset code: <input type="text" name="code"<?php if(array_key_exists("code", $_GET)) echo " value=\"{$_GET['code']}\""; ?>/><br/>
				New password: <input type="password" name="password"/><br/>
				<input type="submit" value="Reset password"/>
			</form></div>
		</div>
		<div id="sidebar">
			<div class="section1">
				<?php displayLoginForm();?>
			</div>
			<br />
			<div class="section4">
				<?php retrieveSlides();?>
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
