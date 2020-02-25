<?php
require_once('../pageIncludes/admin/equipmentManagement.inc.php');
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
<script type="text/javascript">
function load(){
	document.getElementById("returnField").focus();
}
</script>
</head>
<body onload="load();">
<div id="wrapper">
	<?php pageHeader(); ?>
	<div id="page" class="container">
		<div id="content">
		<?php if($_SESSION['isAdmin'] >= 2){ ?>
			<?php if(isset($GLOBALS['equipMessage'])){ ?><h2><?php echo $GLOBALS['equipMessage']; ?></h2><br/><?php }?>
			<h2>Return Equipment</h2><br/>
			<form action="" method="post"><p>
			<input type="text" id="returnField" name="eid"/>
			<label for="typeEQ"><input type="radio" name="type" value="EQ" id="typeEQ"/>EQ</label>
			<label for="typeEQBA"><input type="radio" name="type" value="EQBA" id="typeEQBA" checked="checked"/>EQBA</label>
			<label for="typeEQBAL"><input type="radio" name="type" value="EQBAL" id="typeEQBAL"/>EQBAL</label>
			<label for="typeNone"><input type="radio" name="type" value="" id="typeNone"/>Other</label>
			<input type="submit" name="returnEquipment" value="Return to owner"/>
			</p></form><br/>
			<h2>Equipment Status</h2><br/>
			<form action="" method="post"><p>
			<input type="text" name="eid"/><br/>
			<input type="submit" name="equipmentStatus" value="Get equipment information"/>
			</p></form><br/>
			<h2>Equipment Player Owns</h2><br/>
			<form action="" method="post"><p>
			QR Code, username, email address, or UMBC ID: <input type="text" name="playerID" id="signInID"/><br/>
			<input type="submit" name="playerEquipment" value="Get player information"/>
			</p></form>
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