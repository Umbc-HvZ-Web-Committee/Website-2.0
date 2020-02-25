<?php
require_once('includes/load_config.php');
require_once('includes/quick_con.php');
load_config('config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) include $_SERVER['DOCUMENT_ROOT'].$config['folder']."includes/loginUpdate.php";

$data = array();
$letters = array();
$schools = array();
$sids = array();
$extra="";
if(array_key_exists("sid", $_GET)) $extra = "AND `users`.`SID`='".preg_replace("/[^0-9]/", "", $_GET['sid'])."'";
$sql = "SELECT `fname`, `lname`, `name`, `users`.`SID` AS SID "
."FROM `users`, `schools` "
."WHERE `users`.`SID`=`schools`.`SID`$extra "
."ORDER BY `lname`, `fname`;";
$query = mysql_query($sql);
while($ret = mysql_fetch_assoc($query)){
	$data[] = $ret;
	$letter = strtoupper($ret['lname'][0]);
	$school = $ret['name'];
	if(!isset($letters[$letter])) $letters[$letter]=true;
	if(!isset($schools[$school])) $schools[$school]=1;
	else $schools[$school]+=1;
	$sids[$school] = $ret['SID'];
}
?>
<html>
<head><?php placeTabIcon(); ?><?php include_once 'includes/htmlHeader.php';?></head>
<body><?php include_once 'includes/header.php';?><br>
<?php echo sizeof($data);?> registered employees from <?php
if(array_key_exists("sid", $_GET)){
	$sid = preg_replace("/[^0-9]/", "", $_GET['sid']);
	$name = mysql_oneline("SELECT `name` FROM `schools` WHERE `SID`='$sid';");
	$name = $name['name'];
	echo " from $name. Click <a href=\"directory.php\">here</a> to reset";
}else{
	echo '<a href="directory.php?mode=schools">'.sizeof($schools)." schools</a>";
}
?>.<br>
<div class="content" style="text-align: center;"><br>
<div><?php
if(array_key_exists("mode", $_GET)){
	ksort($schools);
	foreach(array_keys($schools) as $school){
		echo '<a href="?sid='.$sids[$school].'">'.$school.": ".$schools[$school]."</a><br>";
	}
}else{
	foreach(array_keys($letters) as $letter){
		echo '<a href="#'.$letter.'">'.$letter.'</a> ';
	}
?></div>
<hr>
<table>
<?php
$curLetter = "";
foreach($data as $ret){
	$lname = $ret['lname'];
	$fname = $ret['fname'];
	$school = $ret['name'];
	$sid = $ret['SID'];
	$letter = strtoupper($lname[0]);
	if($curLetter != $letter){
		if($curLetter!="") echo "<tr><td>&nbsp;</td><td></td></tr>\n";
		echo "<tr><td><a name=\"$letter\">$letter</td><td></td></tr>\n";
		$curLetter=$letter;
	}
	echo "<tr><td></td><td>$lname, $fname, from <a href=\"?sid=$sid\">".htmlspecialchars($school, ENT_QUOTES)."</a></td></tr>\n";
}
?>
</table><?php }?>
</div><?php include_once 'includes/footer.php';?></body>
</html>