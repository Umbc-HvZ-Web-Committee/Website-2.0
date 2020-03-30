<?php
//Constants
$killChars = "23456789QWERTYUPASDFGHJKZXCVBNM";

//Configuration loading
function my_quick_con($config){
	$u = $config['mysql_user'];
	$p = $config['mysql_pass'];
	$d = $config['mysql_db'];
	if($config['debug']) $con = mysql_connect("colacadstink.getmyip.com", $u, $p);
	else $con = mysql_connect("localhost", $u, $p);
	mysql_select_db($d, $con);
	return $con;
}

function load_config($config_file_name) {
	global $config;
	$config_file_stream = fopen($config_file_name, "r")
	or die("Couldn't open configuration file -- $config_file_name");
	$config = array();
	while(!feof($config_file_stream)) {
		$buf = trim(fgets($config_file_stream));
		if(!(substr($buf,0,1)=='#') && strlen($buf) > 0) {
			$pre = explode("=", $buf, 2);
			$config[trim($pre[0])] = trim($pre[1]);
		}
	}
	fclose($config_file_stream);
}

//MySQL shortcuts
function get_settings(){
	$result = mysql_query("SELECT * FROM `settings`;");
	$settings = array();
	while($ret = mysql_fetch_array($result)){
		$settings[$ret['key']]=$ret['value'];
	}
	return $settings;
}

function set_setting($key, $value){
	mysql_query("UPDATE `settings` SET `value`='$value' WHERE `key`='$key';");
	return get_settings();
}

function mysql_oneline($query){
	$tmp = mysql_query($query);
	if($tmp) return mysql_fetch_assoc($tmp);
	else return false;
}

function generateRandomID($table, $column, $prefix, $characters){
	if(!is_array($table)){
		$table = array($table);
	}
	$done = false;
	while(!$done){
		$done = true;
		//build string
		$str = $prefix;
		while(strlen($str)<7){
			$charPos = rand(0, strlen($characters)-1);
			$char = $characters[$charPos];
			$str .= $char;
		}
		
		//test if it exists
		if(is_array($column)){
			$where = "";
			$firstLoop = true;
			foreach($column as $col){
				if($firstLoop){
					$firstLoop = false;
				}else{
					$where .= " OR ";
				}
				$where .= "$col='$str'";
			}
		}else{
			$where = "$column='$str'";
		}
		foreach($table as $curTable){
			$retval = mysql_oneline("SELECT COUNT(*) AS cnt FROM $curTable WHERE $where");
			if($retval['cnt']!=0){
				echo "SELECT COUNT(*) AS cnt FROM $curTable WHERE $where";
				$done = false;
				continue 2;
			}
		}
	}
	
	return $str;
}

function getNextHighestID($table, $column, $prefix){
	$chars = array();
	array_push($chars, "-");
	for($i=0; $i<10; $i++){
		array_push($chars, "$i");
	}
	for($i=0, $c='a'; $i<26; $i++, $c++){
		array_push($chars, "$c");
	}
	
	$ret = mysql_query("SELECT MAX(`$column`) AS $column FROM `$table`;");
	$ret = mysql_fetch_assoc($ret);
	$ouid = $ret[$column];
	$uid = "";
	if(is_null($ouid)){
		//No users.
		$uid = $prefix."0000-";
	}else{
		//Take the highest UID, add one, then make a new ID from that.
		$id = str_split(substr($ouid, 2));
		$curCharPos = 4;
		while($curCharPos>-1){
			$curChar = $id[$curCharPos];
			if($curChar != $chars[36]){
				$charIndex = array_search($curChar, $chars);
				$charIndex += 1;
				$id[$curCharPos] = $chars[$charIndex];
				break;
			}else{
				$id[$curCharPos] = $chars[0];
				//and continue to the next one
				$curCharPos -= 1;
			}
		}
		$uid = $prefix.implode($id);
		//This creates over a billion possibilities.  If you have more people than that, I congratulate you, as you have more people playing than live in the USA.
	}
	
	return $uid;
}

function getUID($playerID){
	$emailVersion=$playerID;
	if(!strstr($emailVersion, "@")){
		$emailVersion = $emailVersion."@umbc.edu";
	}
	$ret = mysql_query("SELECT * FROM users WHERE uname='$playerID' OR publicQR='$playerID' OR email='$emailVersion'");
	if($ret){
		return mysql_fetch_assoc($ret);
	}else{
		return false;
	}
}

function getCurrentLongGame(){
	return mysql_oneline("SELECT * FROM long_games WHERE CURRENT_TIMESTAMP < `endDate` AND CURRENT_TIMESTAMP > `startDate` LIMIT 1");
}

function getNextLongGame(){
	return mysql_oneline("SELECT * FROM long_games WHERE CURRENT_TIMESTAMP < `endDate` ORDER BY endDate DESC LIMIT 1");
}

//Misc. programming shortcuts
function isLoggedIn(){
	return (isset($_SESSION['uid']) && $_SESSION['uid']!="");
}

function requestVar($str){
	return mysql_real_escape_string($_REQUEST[$str]);
}

function secondsToHumanReadable($posted){
	if($posted<60){
		$posted = intval($posted);
		if($posted == 1) $posted = "just a second ago";
		else $posted = $posted . " seconds ago";
	}else{
		$posted = $posted / 60;
		if($posted<60){
			$posted = intval($posted);
			if($posted == 1) $posted = "a minute ago";
			else $posted = $posted . " minutes ago";
		}else{
			$posted = $posted / 60;
			if($posted<24){
				$posted = intval($posted);
				if($posted == 1) $posted = "an hour ago";
				else $posted = $posted . " hours ago";
			}else{
				$posted = $posted / 24;
				if($posted < 30){
					$posted = intval($posted);
					if($posted == 1) $posted = "a day ago";
					else $posted = $posted . " days ago";
				}else{
					$posted = $posted / 30;
					if($posted < 12){
						$posted = intval($posted);
						if($posted == 1) $posted = "about a month ago";
						else $posted = "about ".$posted." months ago";
					}else{
						$posted = intval($posted / 12);
						if($posted == 1) $posted = "about a year ago";
						else $posted = "about ".$posted." years ago";
					}
				}
			}
		}
	}
	
	return $posted;
}

//Page displaying shortcuts
function htmlHeader(){
	include $_SERVER['DOCUMENT_ROOT'].$config['folder']."/includes/htmlHeader.php";
}

function displayLoginForm(){
	include $_SERVER['DOCUMENT_ROOT'].$config['folder'].'/includes/loginForm.php';
}

function pageHeader(){
	?>
	<div id="header" class="container">
		<div id="menu">
			<ul>
				<?php
				$page = $_SERVER['PHP_SELF'];
				echo '<li';
				if($page=="/home.php"){
					echo ' class="active"';
				}
				echo '><a href="/">News</a></li>';
				
				echo '<li';
				if($page=="/myProfile.php"){
					echo ' class="active"';
				}
				echo '><a href="/myProfile.php">My Profile</a></li>';
				
				echo '<li';
				if($page=="/playerList.php"){
					echo ' class="active"';
				}
				echo '><a href="/playerList.php">Players</a></li>';
				
				echo '<li';
				if($page=="/about.php"){
					echo ' class="active"';
				}
				echo '><a href="/about.php">The Club</a></li>';
				
				echo '<li';
				if($page=="/missionTools.php"){
					echo ' class="active"';
				}
				echo '><a href="/missionTools.php">Mission Toolkit</a></li>';
				?>
				<li><a href="/about.php#Q8">Contact Us</a></li>
			</ul>
		</div>
		<div id="logo">
			<h1><a href="/home.php">UMBC HvZ</a></h1>
			<!-- Replace with http://www.dafont.com/silent-hill.font -->
		</div>
	</div>
	<?php
}

function meetingSelect(){
	$rand = rand();
	?>
	<script type="text/javascript">
	function updateStats<?php echo $rand; ?>(){
		var sel = document.getElementById('sel<?php echo $rand; ?>');
		var selVal = sel.options[sel.selectedIndex].value;
		
		var request = new XMLHttpRequest();
		request.open("GET", "http://colacadstink.getmyip.com:800/api/meetingStats.php?meeting="+selVal, true);
		request.onreadystatechange=function(){
			if (request.readyState==4 && request.status==200){
				document.getElementById("stats<?php echo $rand; ?>").innerHTML=request.responseText;
			}
		}
		request.send();
	}
	</script>
	<div id="stats<?php echo $rand; ?>"></div>
	<?php
	echo '<select name="meetingSelect" id="sel'.$rand.'" onchange="updateStats'.$rand.'()"/>';
	$qret = mysql_query("SELECT * FROM meeting_list WHERE isResolved = 0 ORDER BY creationDate DESC;");
	while($ret = mysql_fetch_assoc($qret)){
		$id = $ret['meetingID'];
		$name = $ret['meetingName']." - ".$ret['creationDate'];
		echo "<option value=\"$id\">$name</option>";
	}
	echo '</select>';
	echo '<script type="text/javascript">updateStats'.$rand.'()</script>';
}

function longGameSelect(){
	echo '<select name="longGameSelect"/>';
	$qret = mysql_query("SELECT * FROM long_games WHERE CURRENT_TIMESTAMP < `endDate` ORDER BY startDate DESC;");
	while($ret = mysql_fetch_assoc($qret)){
		$id = $ret['gameID'];
		$name = $ret['title'];
		echo "<option value=\"$id\">$name</option>";
	}
	echo '</select>';
}
?>