<style type="text/css">
h2{
	text-align: center;

}
body{
	width: 800px;
	text-align: center;
	margin: 0px auto;
	margin-bottom: 100px;
	font-size: 18px;
	font-family: sans-serif;
	color: #000000;
	background-color: #eeeeee;
	/*background: url(/invitational/images/board2.jpg);*/
}
a:link{
	color: ##FF33F0;
}
a:visited{
	color: ##FF33F0;
}
a:hover{
	color: #ffcc66;
}
a.internal{
	color: #FFFFFF;
	text-decoration: none;
}
a:hover.internal{
	color: #888888;
}
a:link.profile{
	color: #4d4d4d;
}
a:visited.profile{
	color: #4d4d4d;
}
a:hover.profile{
	color: #999999;
}
a:link.redLink{
	color: #7D744B;
}
a:visited.redLink{
	color: #8b3029;
}
a:link.blueLink{
	color: #16324A;
}
a:visited.blueLink{
	color: #0a6895;
}
a:link.greenLink{
	color: #8ac52f;
}
a:visited.greenLink{
	color: #6c9524;
}
a:link.purpleLink{
	color: #691669;
}
a:visited.purpleLink{
	color: #691669;
}
.notification{
	font-size: 125%;
	color: red;
}
div.headerTxt{
	text-align: center;
}
div.clearanceBadge{
	width: 9px;
	height: 9px;
	border-style: solid;
	border-width: 2px;
	display: inline-block;
}
div.clearanceBadgeSmall{
	width: 9px;
	height: 9px;
	border-style: solid;
	border-width: 2px;
	display: inline-block;
}
.overlay{
   background-color: rgba(0,0,0,0.7);
   position: absolute;
   top: 0;
   left: 0;
   width: 100%;
   height: 100%;
   z-index: 10;
}
.content{
	text-align: left;
	border-radius: 25px;
	width: 100%;
	background-color: #fff385;
	padding: 10px 10px 10px 10px;
}
.overlayBody{
	background-color: #6F6F6F;
	border-style: solid;
	border-width: 2px;
	border-color: black;
	opacity: 1;
	filter: alpha(opacity=100);
	position: absolute;
	top: 0;
	left: 0;
	width: auto;
	height: auto;
	display: inline-block;
	z-index: 15;
	padding: 20px;
}

.legal {
	text-align: right;
	font-size: 50%;
	color: #000000;
}

input[type="text"] { width: 202px; }
input[type="password"] { width: 202px; }
select { width: 202px; }
</style>

<title>UMBC HvZ</title>

<script type="text/javascript"src="<?php echo $config['folder'];?>includes/sha256.js"></script>
<?php if(!array_key_exists("inv_uid", $_SESSION)){
$over18WaiverURL = "https://docs.google.com/document/d/1rur0XeMYxvhORjugJGy1C6gWof98h58P8t1HZ8vFHhw/edit";
$under18WaiverURL = "https://docs.google.com/document/d/1pjNzcmGGVdREZN8mS7pKExztxGcQrAEuHMY8tygySE4/edit";
?>
<script type="text/javascript">
function submitLogin(){
	salt = document.getElementById("salt").value;
	pf = document.getElementById("loginPasswordTxt");
	document.getElementById("loginPassword").value = SHA256(salt+SHA256(pf.value));
}

function register() {
	var username = document.getElementById("loginUsername").value;
	if(username=="Username") username="";
	var password = document.getElementById("loginPasswordTxt").value;
	if(password=="password") password="";
	
	var overlay = document.createElement("div");
	overlay.setAttribute("id","overlay");
	overlay.setAttribute("class", "overlay");
	overlay.innerHTML = '\
<span class="overlayBody" id="overlayBody"><div id="notification" class="notification"></div>\
<form style="margin:0"><table style="margin:0;">\
<tr><td>Username:</td><td colspan="2"><input type="text" id="username" name="username" value="'+username+'" onkeyup="updateOne();"></td></tr>\
<tr><td>Password:</td><td colspan="2"><input type="password" id="pass1" name="pass1" value="'+password+'" onkeyup="updateOne();"></td></tr>\
<tr><td>(again):</td><td colspan="2"><input type="password" id="pass2" name="pass2" value="" onkeyup="updateOne();"></td></tr>\
<tr><td>&nbsp;</td><td style="text-align: center">First</td><td style="text-align: center">Last</td></tr>\
<tr><td>Name:</td><td style="text-align: center; width: 100px; border: 0px;"><input id="fname" name="fname" style="width: 100px;" onkeyup="updateOne();"></td><td style="text-align: center; width: 100px; border: 0px;"><input id="lname" name="lname" style="width: 100px;" onkeyup="updateOne();"></td></tr>\
<tr><td>Email:</td><td colspan="2"><input type="text" id="email" name="email" onkeyup="updateOne();"></td></tr>\
<tr><td>Phone number:<br>(optional, but encouraged)</td><td colspan="2"><input type="text" id="phone" name="phone" onkeyup="updateOne();"></td></tr>\
<tr style="height: 28px;"><td>School/Group:</td><td id="schoolTD" colspan="2"><select id="schoolSelect" name="school" onchange="schoolUpdate(); updateOne();">\
<option value="1">UMBC</option><?php

$query = mysql_query("SELECT * FROM `schools` WHERE `SID`!='1' AND `name` != '' ORDER BY `name`;");
//echo mysql_error();
while($ret = mysql_fetch_assoc($query)){
	echo '<option value="'.$ret['SID'].'">'.htmlspecialchars($ret['name'], ENT_QUOTES).'</option>';
}
?><option value="other">Other...</other></select></td></tr>\
<tr><td>Age:</td><td colspan="2"><label for="old"><input type="radio" name="age" id="old" value="old" onchange="updateOne();">Over or of 18 years of age</label><br><label for="young"><input type="radio" name="age" id="young" value="young" onchange="updateOne();">Under 18 years of age</label></td></tr>\
<!-- <tr><td>Will you need housing? (Note: even if you are<br>already planning on staying with one of our players,<br>please check this box.)</td><td colspan="2"><label for="yes"><input type="radio" name="housing" id="yes" value="yes" onchange="updateOne();">Yes</label><br><label for="no"><input type="radio" name="housing" id="no" value="no" onchange="updateOne();">No</label></td></tr> -->\
</table><table>\
<tr><td style="vertical-align:top;"><input type="checkbox" id="alreadySigned" name="alreadySigned" value="yes"></td><td><label for="alreadySigned">I have already signed a waiver this school year<br>for UMBC HvZ (make sure you are using<br>your @umbc.edu email address above)</label></td></tr>\
</table></form><button type="button" id="continue" disabled="disabled" onclick="disableContinue(); continueOne();">Continue</button><button type="button" onclick="restore();">Cancel</button></span>';
	document.body.appendChild(overlay);
	
	centerOverlayBody();
	
	document.getElementById("loginForm").onsubmit="return false;";
}

function continueOne(){
	
	//check if username and email are unique
	/*var unique = getFile("https://umbchvz.com/invitational/api/uniqueUserEmailCheck.php?email="+document.getElementById("email").value+"&user="+document.getElementById("username").value);
	if(unique!="OK"){
		document.getElementById("notification").innerHTML=unique;
		return;
	}*/
	
	var isYoung = document.getElementById("young").checked;
	var isAlreadySigned = document.getElementById("alreadySigned").checked;
	var isHousing = false; //document.getElementById("yes").checked;
	
	// WORK IN PROGRESS===============================================================
	/*
	$query = mysql_query("SELECT `isAdmin` FROM `hvz`.`users` WHERE  `email` = '"+document.getElementById("email").value+"';");
	
	while($row = mysql_fetch_array($query)) {
		alert($row['isAdmin']);
	}*/
	
	//make the iframe for the form to post to
	var frame = document.createElement("iframe");
	frame.setAttribute("id","hiddenIFrame");
	frame.setAttribute("name","hiddenIFrame");
	frame.setAttribute("style","visibility:hidden;");
	document.body.appendChild(frame);
	
	var newInnerHTML='\
<span class="overlayBody" id="overlayBody" style="text-align: left;"><div id="notification" class="notification"></div>\
<form id="regForm" target="hiddenIFrame" action="register.php" method="post" style="margin:0;">\
<input type="hidden" name="username" id="username" value="'+document.getElementById("username").value+'">\
<input type="hidden" name="password" value="'+document.getElementById("pass1").value+'">\
<input type="hidden" name="fname" value="'+document.getElementById("fname").value+'">\
<input type="hidden" name="lname" value="'+document.getElementById("lname").value+'">\
<input type="hidden" name="email" value="'+document.getElementById("email").value+'">\
<input type="hidden" name="phone" value="'+document.getElementById("phone").value+'">\
<input type="hidden" '+
/*school shenanigans - either schoolSelect = id or otherSchool = text*/
(document.getElementById("schoolSelect")!=null?'name="schoolSelect" value="'+document.getElementById("schoolSelect").value+'"':'name="otherSchool" value="'+document.getElementById("otherSchool").value+'"')
+'>\
<input type="hidden" name="age" value="'+
/*age shenanigans - age = either old or young*/
(isYoung?'young':'old')
+'">\
<input type="hidden" name="housing" value="'+
/*housing shenanigans - housing = either yes or no*/
(isHousing?'yes':'no')
+'">\
';
	//print correct waiver information
	
	
	var waiverText = "";
	if(!isYoung){
		waiverText += '<label for="waiver"><input type="checkbox" id="waiver" name="waiver" value="yes" onchange="updateTwo();">I state that I have read <a target="_blank" href="<?php echo $over18WaiverURL;?>">this waiver</a> and agree to it fully.</label><br>\n';
	}else{
		waiverText += '<label for="waiver"><input type="checkbox" id="waiver" name="waiver" value="no" onchange="updateTwo();">I promise to print out <a target="_blank" href="<?php echo $under18WaiverURL;?>">this waiver</a> and have it signed<br>by my '+
		'legal parent or guardian before the event,<br>and remember to bring it with me to the event.<br>I understand that <b>if I forget this waiver, I will not<br>be permitted to '
		+'play!</label><br><br>\n';
	}
	if(isAlreadySigned){
		var waiverStatus = getFile("https://umbchvz.com/api/hasEmailTurnedInWaiver.php?email="+document.getElementById("email").value);
		if(waiverStatus=="OK"){
			//you're ok, so print that
			newInnerHTML += '<input type="hidden" id="waiver" name="waiver" value="yes"><input type="checkbox" checked="checked" disabled="disabled">I have already turned in a waiver!<br>';
		}else{
			//you're not OK, so print an appropriate warning and the above waiverText
			newInnerHTML += 'Sorry, we couldn\'t confirm that you have already turned in<br>a waiver.  Please fill out a new one below:<br>\n\n'+waiverText;
		}
	}else{
		newInnerHTML += waiverText;
	}

	
	//print roomate request if appropriate
	/*if(isHousing){
		newInnerHTML += '<br>Will you need housing<br><input type="radio" name="housing" id="yes" value="yes" disabled="disabled" checked="checked">Yes</label><br><label for="no"><input type="radio" name="housing" id="no" value="no" disabled="disabled">No<br><br>I will need housing:<br><label for="short"><input type="radio" id="short" name="housingDuration" value="short" onchange="updateTwo();"> Friday night - Leaving Saturday night.</label><br>\
<label for="short2"><input type="radio" id="short2" name="housingDuration" value="short2" onchange="updateTwo();"> Saturday night - Leaving Sunday morning.</label><br>\
<label for="long"><input type="radio" id="long" name="housingDuration" value="long" onchange="updateTwo();"> Both Friday and Saturday night - Leaving Sunday morning.</label><br><br>';
		newInnerHTML += 'If you would like to request roommate(s), please enter<br>their names and schools here, one per line:<br><textarea cols="40" rows="5" name="roommateRequest" onkeyup="updateTwo();"></textarea><br>\n';
	} else {
		newInnerHTML += '<br>Will you need housing<br><input type="radio" name="housing" id="yes" value="yes" disabled="disabled">Yes</label><br><label for="no"><input type="radio" name="housing" id="no" value="no" disabled="disabled" checked="checked">No<br>';
	}*/
	//print emergency contact information
	newInnerHTML += '<br>In case of an emergency, please provide us with<br>the name and phone number of someone to contact for you:<br><table>\
<tr><td>Name:</td><td><input type="text" id="emergencyName" name="emergencyName" onkeyup="updateTwo();"></td></tr>\
<tr><td>Relation:</td><td><input type="text" id="emergencyRelation" name="emergencyRelation" onkeyup="updateTwo();"></td></tr>\
<tr><td style="vertical-align:top;">Phone number:</td><td><input type="text" id="emergencyPhone" name="emergencyPhone" onkeyup="updateTwo();"></td></tr></table>';
	
	newInnerHTML += '<br>Do you have any dietary (e.x. vegetarian, vegan)<br/>or medical concerns we should be aware of?<br/>(e.x. asthma with an inhaler, any allergies)\
<br/><textarea cols=40 rows=5 name="medicalConcerns"></textarea><br/>\
';
	
	newInnerHTML += '</form><button type="button" id="continue" disabled="disabled" onclick="disableContinue(); continueTwo();">Continue</button><button type="button" onclick="restore();">Cancel</button></span>';

	document.getElementById("overlay").innerHTML = newInnerHTML;
	
	centerOverlayBody();
}

function updateTwo(){

	//if(document.getElementById("yes").checked && !document.getElementById("short").checked && !document.getElementById("short2").checked && !document.getElementById("long").checked) return disableContinue();
	if(!document.getElementById("waiver").checked && document.getElementById("waiver").type!="hidden") return disableContinue();
	if(document.getElementById("emergencyName").value=="") return disableContinue();
	if(document.getElementById("emergencyRelation").value=="") return disableContinue();
	if(document.getElementById("emergencyPhone").value=="") return disableContinue();
	return enableContinue();
}

function finalPage(uid){
	//is called by the registration page after it finishes saving your data
	//displays the cell phone data page
	var newInnerHTML = '\
<span class="overlayBody" id="overlayBody" style="text-align: left; width: 500px;"><div id="notification" class="notification"></div>\
Thank you for confirming your attendance. We look forward to seeing you.<br>\
<button type="button" onclick="restore();">Finish</button>\
</span>';
//Your UID is <b>'+uid+'</b>.<br>\
	
	document.getElementById("overlay").innerHTML = newInnerHTML;
	
	centerOverlayBody();
}

function centerOverlayBody(){
	var overlayBody = document.getElementById("overlayBody");
	var blankWidth = overlay.clientWidth - overlayBody.clientWidth;
	var blankHeight = overlay.clientHeight - overlayBody.clientHeight;
	overlayBody.style.left = blankWidth/2;
	overlayBody.style.top = blankHeight/2;
}

function getFile(url) {
	var AJAX;
	if(window.XMLHttpRequest){              
		AJAX=new XMLHttpRequest();              
	}else{                                  
		AJAX=new ActiveXObject("Microsoft.XMLHTTP");
	}
	if(AJAX){
		AJAX.open("GET", url, false);                         
		AJAX.send(null);
		return AJAX.responseText;
	}else{
		return false;
	}                                             
}

function continueTwo(){
	document.getElementById("regForm").submit();
	finalPage();
}

function updateOne(){
	//Oh boy. Check if everything's filled in, and if it is continue.
	//Do passwords first so we can warn about non-matching passwords quickly
	if(document.getElementById("pass1").value=="") return disableContinue();
	if(document.getElementById("pass2").value=="") return disableContinue();
	//Check password matching
	if(document.getElementById("pass1").value!=document.getElementById("pass2").value){
		document.getElementById("notification").innerHTML = "These passwords don't match!";
		return disableContinue();
	}else{
		document.getElementById("notification").innerHTML = "";
	}
	//Next school cuz it's weird code
	if(document.getElementById("schoolSelect")==null && document.getElementById("otherSchool").value=="") return disableContinue();
	//Age is also weird.
	if(!document.getElementById("young").checked && !document.getElementById("old").checked) return disableContinue();
	//And so is housing for the same reason.
	//if(!document.getElementById("yes").checked && !document.getElementById("no").checked) return disableContinue();
	if(document.getElementById("username").value=="") return disableContinue();
	if(document.getElementById("fname").value=="") return disableContinue();
	if(document.getElementById("lname").value=="") return disableContinue();
	if(document.getElementById("email").value=="") return disableContinue();
	return enableContinue();
}

function disableContinue(){
	document.getElementById("continue").disabled="disabled";
}

function enableContinue(){
	document.getElementById("continue").disabled="";
}

function schoolUpdate(){
	var td = document.getElementById("schoolTD");
	var sel = document.getElementById("schoolSelect");

	if(sel.options[sel.selectedIndex].value=="other"){
		td.innerHTML = '<input type="text" id="otherSchool" name="otherSchool" onkeypress="updateOne();">';
		var other = document.getElementById("otherSchool");
		other.focus();
	}
}

function resetPassword(){
	var overlay = document.createElement("div");
	overlay.setAttribute("id","overlay");
	overlay.setAttribute("class", "overlay");
	overlay.innerHTML = "<iframe src=\"passwordReset.php\" style=\"width: 50%; height: 50%;\"></iframe>";
	document.body.appendChild(overlay);
	
	var overlayBody = document.getElementById("overlayBody");
	var blankWidth = overlay.clientWidth - overlayBody.clientWidth;
	var blankHeight = overlay.clientHeight - overlayBody.clientHeight;
	overlayBody.style.left = blankWidth/2;
	overlayBody.style.top = blankHeight/2;
	
	document.getElementById("loginForm").onsubmit="return false;";
}

function restore() {
	document.body.removeChild(document.getElementById("overlay"));
}
</script>
<?php }?>
