<?php
require_once('pageIncludes/register.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php placeTabIcon(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>UMBC HvZ - Registration</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="/style.css" rel="stylesheet" type="text/css" media="all" />
<link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'/>
<script type="text/javascript">
function verifyAndSubmit(){
	text1 = document.getElementById("pwd1").value;
	text2 = document.getElementById("pwd2").value;
	if(text1!=text2){
		alert("Passwords do not match, please retry.");
		return false;
	}else if(document.getElementById("fname").value==""){
		alert("First name is required.");
		return false;
	}else if(document.getElementById("lname").value==""){
		alert("Last name is required.");
		return false;
	}else if(document.getElementById("username").value==""){
		alert("Username is required.");
		return false;
	}else if(document.getElementById("pwd1").value==""){
		alert("A password is required.");
		return false;
	}else if(document.getElementById("email").value==""){
		alert("An email address is required.");
		return false;
// 	}else if(document.getElementById("qrCode").value=="" || document.getElementById("qrCode").value=="PU"){
// 		alert("A QR code is required; please contact an admin for a QR code.");
// 		return false;
	}else if(!document.getElementById("tosAgree").checked){
		alert("You must accept the TOS to register.");
		return false;
	}else{	
		document.getElementById("registerForm").submit();
		return true;
	}
	return false;
}
function checkEmail(field){
	if(field.value.match(/.*@umbc.edu/)==null && field.value!=""){
		alert("You're not using a MyUMBC email address, and will not be able to play long games. If you have a MyUMBC email, please use that; if not, you can continue to register.");
	}
}
function genQR(){
	var qr = document.getElementById("qrCode");
	var text = "PU_";
	var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_";

	for(var i=0; i<4; i++) text += possible.charAt(Math.floor(Math.random() * possible.length));
	qr.value = text;
}
</script>
<?php htmlHeader(); ?>
</head>
<body>
<div id="wrapper">
	<?php pageHeader(); ?>
	<div id="page" class="container">
		<div id="content">
			<?php
			if(isLoggedIn()) echo "<h3>Note: You are already logged in. If this is intentional, feel free to ignore this.</h3>";
			if($notification!="") echo "<h3>$notification</h3>";
			?>
			<form method="post" action="" name="registerForm" id="registerForm" onsubmit="return verifyAndSubmit();">
				<table style="text-align: left; border: 0px"><tbody>
					<tr>
						<td>First name:</td>
						<td><input id="fname" name="fname"<?php if($fname != "") echo " value=\"$fname\"";?>/></td>
					</tr>
					<tr>
						<td>Last name:</td>
						<td><input id="lname" name="lname"<?php if($lname != "") echo " value=\"$lname\"";?>/></td>
					</tr>
					<tr>
						<td>Email address:</td>
						<td><input id="email" name="email"<?php if($email != "") echo " value=\"$email\"";?> onblur="checkEmail(this);"/></td>
					</tr>
					<tr>
						<td>Username:</td>
						<td><input id="username" name="username"<?php if($username != "") echo " value=\"$username\"";?>/></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><input name="password" id="pwd1" type="password"/></td>
					</tr>
					<tr>
						<td>Password (again):</td>
						<td><input name="password" id="pwd2" type="password"/></td>
					</tr>
					<!-- <tr>
						<td>ID card code:</td>
						<td><input id="qrCode" maxlength="7" name="qrCode"<?php if($qrCode != "") echo " value=\"$qrCode\""; else echo ' value=PU'?>/></td>
						<td>Don't have an ID card?<button type="button" onclick="genQR()">Generate a temporary QR code</button></td>
					</tr> -->
				</tbody></table>
			<label for="tosAgree"><input id="tosAgree" type="checkbox" name="tosAgree" value=1/>I have read and agree to the <a href="TOS.php" target="_blank">Terms of Service</a> for this website.</label><br/>
			<input type="submit" name="submit" value="Submit"/>
			</form>
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