<?php
global $config;
$ret = mysql_oneline("SELECT CURRENT_TIMESTAMP;");
$now = date_create($ret['CURRENT_TIMESTAMP']);
?>
<script type="text/javascript">
var today = new Date();
today.setHours(<?php echo date_format($now, "G");?>);
today.setMinutes(<?php echo date_format($now, "i");?>);
today.setSeconds(<?php echo date_format($now, "s");?>);

function startTime(){
	t=setTimeout('startTime()',1000);
	var h=today.getHours();
	var m=today.getMinutes();
	var s=today.getSeconds();
	// add a zero in front of numbers<10
	var mt=	checkTime(m);
	var st=checkTime(s);
	document.getElementById('timeTxt').innerHTML="Current approximate server time: "+h+":"+mt+":"+st;
	s+=1;
	today.setSeconds(s);
}

function checkTime(i){
	if (i<10) i="0" + i;
	return i;
}
</script>
<script type="text/javascript"src="<?php echo $config['folder'];?>includes/sha256.js"></script>
<script type="text/javascript">
function submitLogin(){
	salt = document.getElementById("salt").value;
	pf = document.getElementById("loginPasswordTxt");
	document.getElementById("loginPassword").value = SHA256(salt+SHA256(pf.value));
	document.getElementById("loginForm").submit();
}
</script>