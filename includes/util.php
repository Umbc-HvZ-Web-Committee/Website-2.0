<?php
//Constants
$killChars = "3479QWERTYPAFHJKLXCNM";

//Configuration loading
function my_quick_con($config){
	$u = $config['mysql_user'];
	$p = $config['mysql_pass'];
	$d = $config['mysql_db'];
	if($config['debug']) $con = mysql_connect("umbchvz.com", $u, $p);
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
	while($ret = mysql_fetch_assoc($result)){
		$settings[$ret['key']]=$ret['value'];
	}
	return $settings;
}

function set_setting($key, $value){
	mysql_query("UPDATE `settings` SET `value`='$value' WHERE `key`='$key';");
	return get_settings();
}

//Equivalent to mysql_query but with error checking
function mysql_oneline($query){
	$tmp = mysql_query($query);
	if($tmp != null){
		return mysql_fetch_assoc($tmp);
	}
	else 
	{
		return false;
	}
}


//Used to generate random IDs based on a sql table, column in the table, a 2-char prefix, and useable chars
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

//Used to generate ordered IDs based on a sql table, column in the table, and a 2-char prefix
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

//Used to get user data given a umbc email belonging to the account or account's username
function getUID($playerID){
	$emailVersion=$playerID;
	if(!strstr($emailVersion, "@")){
		$emailVersion = $emailVersion."@umbc.edu";
	}
	$ret = mysql_query("SELECT * FROM users WHERE uname='$playerID' OR email='$emailVersion'");
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

function getCurrentSemester(){
	return mysql_oneline("SELECT * FROM semesters WHERE CURRENT_TIMESTAMP < `endDate` AND CURRENT_TIMESTAMP > `startDate` LIMIT 1");
}

function getLastSemester(){
	return mysql_oneline("SELECT * FROM semesters WHERE CURRENT_TIMESTAMP > `endDate` ORDER BY `endDate` DESC LIMIT 1");
}

function getSemester($date){
	return mysql_oneline("SELECT * FROM semesters WHERE $date < `endDate` AND $date > `startDate` LIMIT 1");
}

function getAllSemesters() {
	return mysql_query("SELECT * FROM semesters WHERE CURRENT_TIMESTAMP > `startDate` DESC;");
}

//Currently irrelevant since semesters table is not used as of 1/22/2020
function getMembershipDateRange(){
	return mysql_oneline("SELECT MIN(startDate) AS startDate, MAX(endDate) AS endDate FROM (SELECT * FROM semesters WHERE startDate < CURRENT_TIMESTAMP ORDER BY startDate DESC LIMIT 2) AS temp");
}

function getMeetingsAttendedInMembershipRange($uid){
	$ret = mysql_oneline("SELECT * FROM `users` WHERE `UID`='$uid'");
	return $ret['appearancesThisTerm'] + $ret['appearancesLastTerm'];
}

function canVote($uid){
	$ret = mysql_oneline("SELECT * FROM `users` WHERE `UID`='$uid'");
	return $ret['appearancesThisTerm'] + $ret['appearancesLastTerm'] >= 5;
}

function updateAttendance($uid) {
	$thisSemester = getCurrentSemester();
	$lastSemester = getLastSemester();
	
	$thisSemesterStart = $thisSemester['startDate'];
	$thisSemesterEnd = $thisSemester['endDate'];
	$lastSemesterStart = $lastSemester['startDate'];
	$lastSemesterEnd = $lastSemester['endDate'];
	
	//echo $uid;
	//echo " ";
	$totalCount = mysql_oneline("SELECT COUNT(*) AS cnt FROM `meeting_log` NATURAL JOIN `meeting_list` WHERE `UID` = '$uid' AND `meetingType` != '3';");
	$totalCount = $totalCount['cnt'];
	//echo $totalCount;
	//echo " ";
	$termCount = mysql_oneline("SELECT COUNT(*) AS cnt FROM `meeting_log` NATURAL JOIN `meeting_list` WHERE `UID` = '$uid' AND `creationDate` > '$thisSemesterStart';");
	$termCount = $termCount['cnt'];
	//echo $termCount;
	//echo " ";
	$lastTermCount = mysql_oneline("SELECT COUNT(*) AS cnt FROM `meeting_log` WHERE `UID` = '$uid' AND `creationDate` > '$lastSemesterStart' AND `creationDate` < '$lastSemesterEnd';");
	$lastTermCount = $lastTermCount['cnt'];
	//echo $lastTermCount;
	//echo " ";
	$humanCount = mysql_oneline("SELECT COUNT(*) AS cnt FROM `meeting_log` WHERE `UID` = '$uid' AND `startState` = '1';");
	$humanCount = $humanCount['cnt'];
	//echo $humanCount;
	//echo " ";
	$zombieCount = mysql_oneline("SELECT COUNT(*) AS cnt FROM `meeting_log` WHERE `UID` = '$uid' AND (`startState` < '0' OR `startState` = '2');");
	$zombieCount = $zombieCount['cnt'];
	//echo $zombieCount;
	//echo " ";
	$modCount = mysql_oneline("SELECT COUNT(*) AS cnt FROM `meeting_log` WHERE `UID` = '$uid' AND `startState` = '4';");
	$modCount = $modCount['cnt'];
	//echo $modCount;
	//echo " ";
	$adminCount = mysql_oneline("SELECT COUNT(*) AS cnt FROM `meeting_log` NATURAL JOIN `meeting_list` WHERE 
		`UID` = '$uid' AND `meetingType` = '1';");
	$adminCount = $adminCount['cnt'];
	//echo $adminCount;
	//echo " ";
	$adminTerm = mysql_oneline("SELECT COUNT(*) AS cnt FROM `meeting_log` NATURAL JOIN `meeting_list` WHERE 
		`UID` = '$uid' AND `meetingType` = '1' AND `creationDate` > '$thisSemesterStart';");
	$adminTerm = $adminTerm['cnt'];
	//echo $adminTerm;
	//echo "<br/>";
	
	mysql_query("UPDATE `users` SET `zombieStartsTotal`='$zombieCount' WHERE `UID` = '$uid';");
	mysql_query("UPDATE `users` SET `humanStartsTotal`='$humanCount' WHERE `UID` = '$uid';");
	mysql_query("UPDATE `users` SET `gamesModdedTotal`='$modCount' WHERE `UID` = '$uid';");
	
	mysql_query("UPDATE `users` SET `adminMeetingsTotal`='$adminCount' WHERE `UID` = '$uid';");
	mysql_query("UPDATE `users` SET `adminMeetingsThisTerm`='$adminTerm' WHERE `UID` = '$uid';");
	
	mysql_query("UPDATE `users` SET `appearancesTotal`='$totalCount' WHERE `UID` = '$uid';");
	mysql_query("UPDATE `users` SET `appearancesThisTerm`='$termCount' WHERE `UID` = '$uid';");
	mysql_query("UPDATE `users` SET `appearancesLastTerm`='$lastTermCount' WHERE `UID` = '$uid';");

	//Human, zombie, mod counts again, but just for current term
	$humanCount = mysql_oneline("SELECT COUNT(*) AS cnt FROM `meeting_log` NATURAL JOIN `meeting_list` WHERE `UID` = '$uid' AND `startState` = '1' AND `UID` = '$uid' AND `creationDate` > '$thisSemesterStart' AND `isResolved` = '1' ORDER BY `meetingID` DESC;");
	$humanCount = $humanCount['cnt'];
	//echo $humanCount;
	//echo " ";
	$zombieCount = mysql_oneline("SELECT COUNT(*) AS cnt FROM `meeting_log` NATURAL JOIN `meeting_list` WHERE `UID` = '$uid' AND (`startState` < '0' OR `startState` = '2') AND `UID` = '$uid' AND `creationDate` > '$thisSemesterStart' AND `isResolved` = '1' ORDER BY `meetingID` DESC;");
	$zombieCount = $zombieCount['cnt'];
	//echo $zombieCount;
	//echo " ";
	$modCount = mysql_oneline("SELECT COUNT(*) AS cnt FROM `meeting_log` NATURAL JOIN `meeting_list` WHERE `UID` = '$uid' AND `startState` = '4' AND `UID` = '$uid' AND `creationDate` > '$thisSemesterStart' AND `isResolved` = '1' ORDER BY `meetingID` DESC;");
	$modCount = $modCount['cnt'];
	//echo $modCount;
	//echo "<br/>";
	mysql_query("UPDATE `users` SET `zombieStartsThisTerm`='$zombieCount' WHERE `UID` = '$uid';");
	mysql_query("UPDATE `users` SET `humanStartsThisTerm`='$humanCount' WHERE `UID` = '$uid';");
	mysql_query("UPDATE `users` SET `gamesModdedThisTerm`='$modCount' WHERE `UID` = '$uid';");
}

function denumerate($enum, $number){
	switch($enum) {
		case 'vaccineStatus':
			$full = mysql_oneline("SELECT MAX(`vaccineStatus`) AS `full` FROM users WHERE 1");
			$full = $full['full'];
			switch($number) {
				case 0:
					return "UNVACCINATED";
				case $full:
					return "Fully-Vaccinated";
				default:
					return "PARTIALLY-VACCINATED";
			}
		break;
		case 'waiverStatus':
			switch($number) {
				case 0:
					return "NOT CLEARED";
				case 1:
					return "Cleared";
				default:
					return "UNABLE TO DENUMERATE VALUE $number";
			}
		break;
		case 'gameState':
			switch($number) {
				case -3:
					return "Weeklong Player";
				case -2:
					return "OZ";
				case -1:
					return "Zombie";
				case 0:
					return "Visitor";
				case 1:
					return "Human";
				case 2:
					return "Disguised OZ";
				case 3:
					return "Medkit";
				case 4:
					return "Moderator";
				default:
					return "UNABLE TO DENUMERATE VALUE $number";
			}
		break;
		case 'adminLevel':
			switch($number) {
				case 0:
					return "Non-Administrator/Non-technical Subofficer";
				case 1:
					return "Technical Subofficer";
				case 2:
					return "Officer";
				case 3:
					return "Web Committee";
				default:
					return "UNABLE TO DENUMERATE VALUE $number";
			}
		break;
		case 'weeklongModerator':
			switch($number) {
				case 0:
					return "Non-Administrator";
				case 1:
					return "Secondary Moderator";
				case 2:
					return "Officer/Primary Moderator";
				case 3:
					return "Web Committee";
				default:
					return "UNABLE TO DENUMERATE VALUE $number";
			}
		break;
		case 'meetingType':
			switch($number) {
				case 0:
					return "Mission";
				case 1:
					return "Admin";
				case 2:
					return "Other";
				case 3:
					return "Nominal";
				default:
					return "UNABLE TO DENUMERATE VALUE $number";
			}
		break;
		case 'meetingWinner':
			switch($number) {
				case 0:
					return "Other";
				case 1:
					return "Human";
				case 2:
					return "Zombie";
				default:
					return "UNABLE TO DENUMERATE VALUE $number";
			}
		break;
	}
	return NULL;
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

function printSidebar() {
	echo "<div id=\"sidebar\">";
	echo "<div class=\"section1\">";
	displayLoginForm();
	echo "</div><br /><div class=\"section1\">";
	displayVotingLink();
	//echo "</div><br /><div class=\"section1\">";
	//displayActivePoll();
	echo "<br/></div><div class=\"section4\">";
	retrieveSlides();
	echo "</div></div>";
}

function printFooter() {
	echo "<p>UMBC HvZ REVISION: Art assets borrowed from <a href=\"http://www.nodethirtythree.com/\">nodethirtythree</a>.-Vs</p>";
}

//Page displaying shortcuts
function htmlHeader(){
	global $config;
	include $_SERVER['DOCUMENT_ROOT'].$config['folder']."/includes/htmlHeader.php";
}

function displayLoginForm(){
	global $config;
	include $_SERVER['DOCUMENT_ROOT'].$config['folder'].'/includes/loginForm.php';
}

function displayVotingLink() {
	echo "<h2>Elections</h2>";
	
	$settings = get_settings();
	$voteLink = $settings['showVotingLink'];
	if($voteLink == "soonOpen" or $voteLink == "soonClosed") {
		echo "<center><font size='4'><b>Voting opening soon</b></font></center>";
	} else if($voteLink == "open") {
		echo "<center><font size='3'><b>Voting is now open!</b></font></center>";
	}
	
	if($voteLink == "soonOpen" or $voteLink == "open") {
		echo "<center><a href='https://umbchvz.com/voting.php'>Voting page</a></center>";
	}else if($voteLink == "closed" or $voteLink == "soonClosed") {
		echo "<center><font size='4'><b>No elections in progress</b></font></center>";
	} else { //Aaaaaaaaaaaaaaaaaa
		echo "<center><font size='4'><b>Unknown status of elections</b></font></center>";
	}
}

function displayActivePoll(){
	global $config;
	echo "<h2>Current Poll</h2>";
	include $_SERVER['DOCUMENT_ROOT'].$config['folder'].'/includes/sidebarPollForm.php';
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
				echo '><a href="/home.php">Home</a></li>';
				
				echo '<li';
				if($page=="/rules.php"){
					echo ' class="active"';
				}
				echo '><a href="/rules.php">The Rules</a></li>';
				
				echo '<li';
				if($page=="/news.php"){
					echo ' class="active"';
				}
				echo '><a href="/news.php">News</a></li>';
				
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
				echo '><a href="/about.php">FAQs</a></li>';
				
				echo '<li';
				if($page=="/missionTools.php"){
					echo ' class="active"';
				}
				echo '><a href="/missionTools.php">Mission Toolkit</a></li>';
				
				echo '<li';
				if($page=="/contact.php"){
					echo ' class="active"';
				}
				echo '><a href="/contact.php">Meet the Admins</a></li>';
				
				echo '<li';
				if($page=="/achievements.php"){
					echo ' class="active"';
				}
				echo '><a href="/achievements.php">Achievements</a></li>';
				?>
			</ul>
		</div>
		<div id="logo">
			<a href="/home.php"><image src="/images/hvzLogo.png" style="height:150px; width:150px; margin-top:20px;"></image></a>
		</div>
	</div>
	<?php
}

//Used to check *unresolved* meetings in order to sign-in players
//Ordered chronologically by creation date
function meetingSelect(){
	$rand = rand();
	?>
	<script type="text/javascript">
	function updateStats<?php echo $rand; ?>(){
		var sel = document.getElementById('sel<?php echo $rand; ?>');
		var selVal = sel.options[sel.selectedIndex].value;
		
		var request = new XMLHttpRequest();
		request.open("GET", "https://umbchvz.com/api/meetingStats.php?meeting="+selVal, true);
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

//Used to check *all* meetings in order to view attendance records
//Ordered chronologically by creation date
function meetingSelect2(){
	$rand = rand();
	?>
	<script type="text/javascript">
	function updateStats<?php //echo $rand; ?>(){
		var sel = document.getElementById('sel<?php //echo $rand; ?>');
		var selVal = sel.options[sel.selectedIndex].value;
		
		var request = new XMLHttpRequest();
		request.open("GET", "https://umbchvz.com/api/meetingPlayerList.php?meeting="+selVal, true);
		request.onreadystatechange=function(){
			if (request.readyState==4 && request.status==200){
				document.getElementById("stats<?php //echo $rand; ?>").innerHTML=request.responseText;
			}
		}
		request.send();
	}
	</script>
	<?php
	echo '<select name="meetingSelect" id="sel'.$rand.'" onchange="updateStats'.$rand.'()"/>';
	$qret = mysql_query("SELECT * FROM meeting_list ORDER BY creationDate DESC;");
	while($ret = mysql_fetch_assoc($qret)){
		$id = $ret['meetingID'];
		$name = $ret['meetingName']." - ".$ret['creationDate'];
		echo "<option value=\"$id\">$name</option>";
	}
	?>
	</select>
	<div id="stats<?php echo $rand; ?>"></div>
	<?php echo '<script type="text/javascript">updateStats'.$rand.'()</script>';
}

//Checking for unresolved long games ordered by start date
//Will likely show only 1 or 0 games since weeklongs don't overlap
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

function retrieveSlides() {
	//displayWeather();
	
	//Load slide headings
	$mainHeading = mysql_oneline("SELECT * FROM mission_slide_headings WHERE headingTitle = 'mainHeading'");
	$mainHeading = $mainHeading['headingName'];
	$firstHeading = mysql_oneline("SELECT * FROM mission_slide_headings WHERE headingTitle = 'firstSlides'");
	$firstHeading = $firstHeading['headingName'];
	$secondHeading = mysql_oneline("SELECT * FROM mission_slide_headings WHERE headingTitle = 'secondSlides'");
	$secondHeading = $secondHeading['headingName'];
	$thirdHeading = mysql_oneline("SELECT * FROM mission_slide_headings WHERE headingTitle = 'thirdSlides'");
	$thirdHeading = $thirdHeading['headingName'];
	
	//Load slide URLs
	$mondaySlides = mysql_oneline("SELECT * FROM mission_slides WHERE name = 'mondayMission'");
	$mondaySlide = $mondaySlides['url'];
	$mondaySlide = $mondaySlide."embed?start=false&loop=false&delayms=10000&slide=";
	$mondaySlide = $mondaySlide.$mondaySlides['startingSlideNumber'];
	
	$thursdaySlides = mysql_oneline("SELECT * FROM mission_slides WHERE name = 'thursdayMission'");
	$thursdaySlide = $thursdaySlides['url'];
	$thursdaySlide = $thursdaySlide."embed?start=false&loop=false&delayms=10000&slide=";
	$thursdaySlide = $thursdaySlide.$thursdaySlides['startingSlideNumber'];
	
	echo "<h2>".$mainHeading."</h2>";
	echo "<h3>".$firstHeading."</h3>";
	echo "<iframe src=\"$mondaySlide\" frameborder=\"0\" width=\"330\" height=\"330\" allowfullscreen=\"true\" mozallowfullscreen=\"true\" webkitallowfullscreen=\"true\"></iframe><br/>";
	echo "<br/><h3>".$secondHeading."</h3>";
	echo "<iframe src=\"$thursdaySlide\" frameborder=\"0\" width=\"330\" height=\"330\" allowfullscreen=\"true\" mozallowfullscreen=\"true\" webkitallowfullscreen=\"true\"></iframe><br/>";

	
	if($thirdHeading != NULL) {
		//Load info for third set of slides
		$pointSlides = mysql_oneline("SELECT * FROM mission_slides WHERE name = 'pointSlide'");
		$pointSlide = $pointSlides['url'];
		$pointSlide = $pointSlide."embed?start=false&loop=false&delayms=10000&slide=";
		$pointSlide = $pointSlide.$pointSlides['startingSlideNumber'];
		
		echo "<br/><h3>".$thirdHeading."</h3>";
		echo "<iframe src=\"$pointSlide\" frameborder=\"0\" width=\"330\" height=\"330\" allowfullscreen=\"true\" mozallowfullscreen=\"true\" webkitallowfullscreen=\"true\"></iframe><br/>";
	
	}
	
}

/*
//Weather part of the slides. Currently integrated into retrieveSlides() but will not be in retrieveSlidesNew()
function displayWeather() {
	$weather = "https://forecast.io/embed/#lat=39.254755&lon=-76.710972&name=UMBC";
	echo "<br/><h2>Today's Weather</h2>";
	echo "<iframe src=\"$weather\" frameborder=\"0\" height=\"200\" width=\"330\"></iframe>";
}
*/

function placeTabIcon() {
	echo '<link rel="icon" type="image/png" href="https://umbchvz.com/images/hvzLogo.png"/>';
}

function getVotingResults() {
	//If user is an officer/web committee, show a complete, anonymized count of votes
	//I hope you like this vote counter, because I stayed up late during Thanksgiving vacation in Florida to make it work
	//With this counter, web committee does NOT need to actually look at who voted for who to do their job
	//This counter also makes it harder to fix an election, since multiple have continuous access to its live results
	//YOU SHOULD BE SUSPICIOUS OF ANYONE WHO WANTS/TRIES TO REMOVE THIS FUNCTIONALITY FOR ANY REASON
	//IF ANYONE TRIES TO KILL THIS FUNCTIONALITY, LET ALL (OTHER) OFFICERS, (OTHER) WEB COMMITTE MEMBERS, AND HICCUP KNOW RIGHT AWAY 
	//ALSO MAYBE TELL ME (KYLE) SO I CAN COME BACK AND YELL AT SOMEONE
	
	$fullResults = "";
	
	//Default value used for dummy votes to create a candidate
	$nullUID = $settings['nullUID']; 
		
	//load these three arrays
	$curVote = array();
	$positions = array();
	$candidates = array();
	$nullUID = $settings['nullUID'];
						
	//Prepare this array for displaying actual positions to vote ON
	$qury = mysql_query("SELECT position FROM election_votes GROUP BY position ORDER BY position ASC;");
	while($ret = mysql_fetch_assoc($qury)){
		$curVote[$ret['position']] = "";
		$positions[] = $ret['position'];
	}
			
	//Prepare this array for displaying actual "candidates" to vote FOR
	$qury = mysql_query("SELECT position, voteFor AS name FROM election_votes GROUP BY position, voteFor;");
	while($ret = mysql_fetch_assoc($qury)){
		if(!array_key_exists($ret['position'], $candidates)) $candidates[$ret['position']] = array();
		$candidates[$ret['position']][] = $ret['name'];
	}
			
	$blankVotes = array();
	$qury = mysql_query("SELECT position, voteFor AS name FROM election_votes WHERE uid = '' OR uid = '$nullUID';");
	while($ret = mysql_fetch_assoc($qury)){
		if(!array_key_exists($ret['position'], $blankVotes)) $blankVotes[$ret['position']] = array();
			$blankVotes[$ret['position']][] = $ret['name'];
	}
    foreach($positions as $curPos){
        $fullResults = $fullResults."<u>$curPos</u><br>";
        $escapedCurPos = mysql_real_escape_string($curPos);
        foreach($blankVotes[$curPos] as $curCan) {
            $escapedCurCan = mysql_real_escape_string($curCan);
            $numVotes = mysql_oneline("SELECT COUNT(*) cnt FROM election_votes WHERE position = '$escapedCurPos' AND voteFor = '$escapedCurCan';");
			$numVotes = $numVotes['cnt'] - 1; //Don't count the dummy as a vote
			if($numVotes != 1) {
				$fullResults = $fullResults."'".$curCan."' has ".$numVotes." votes";
			}else {
				$fullResults = $fullResults."'".$curCan."' has 1 vote";
			}
			$fullResults = $fullResults."<br>";
		}
		$fullResults = $fullResults."<br><br>";
	}
	$fullResults = $fullResults."<br><br>";
			
	$numVoters = mysql_oneline("SELECT COUNT(*) as cnt FROM (SELECT uid FROM `election_votes` WHERE 1 GROUP BY `uid`) as voters WHERE `uid` != '$nullUID' AND `uid` != '';");
	$numVoters = $numVoters['cnt'];
			
	$totalVotes = mysql_oneline("SELECT COUNT(*) as cnt FROM election_votes WHERE `uid` != '$nullUID' AND `uid` != '';");
	$totalVotes = $totalVotes['cnt'];
			
	$fullResults = $fullResults."Total number of unique voters: ".$numVoters."<br><br>";
	$fullResults = $fullResults."Total number of votes cast: ".$totalVotes."<br><br>";
			
	$fullResults = $fullResults."<br><br>";
	
	return $fullResults;
}

//MySQL compatability fix for PHP 7
$MYSQL_MOST_RECENT_CON = null;
$MYSQL_MOST_RECENT_RET = null;
function mysql_connect($host, $user, $pass){
	global $MYSQL_MOST_RECENT_CON;
	$MYSQL_MOST_RECENT_CON = new mysqli($host, $user, $pass);
	return $MYSQL_MOST_RECENT_CON;
}
function mysql_select_db($db, $con){
	$con->select_db($db);
	return;
}
function mysql_close(){
	global $MYSQL_MOST_RECENT_CON;
	$MYSQL_MOST_RECENT_CON->close();
	return;
}
function mysql_query($sql){
	global $MYSQL_MOST_RECENT_CON;
	global $MYSQL_MOST_RECENT_RET;
	$MYSQL_MOST_RECENT_RET = $MYSQL_MOST_RECENT_CON->query($sql);
	return $MYSQL_MOST_RECENT_RET;
}

function mysql_fetch_assoc($ret){
	$ret = $ret->fetch_array(MYSQLI_ASSOC);
	return $ret;
}
function mysql_fetch_array($ret){
	return $ret->fetch_array(MYSQLI_NUM);
}
function mysql_fetch_row($ret){
	return $ret->fetch_array(MYSQLI_BOTH);
}

function mysql_affected_rows(){
	global $MYSQL_MOST_RECENT_CON;
	return $MYSQL_MOST_RECENT_CON->affected_rows;
}
function mysql_num_rows($ret){
	global $MYSQL_MOST_RECENT_RET;
	return $MYSQL_MOST_RECENT_RET->num_rows;
}
function mysql_insert_id(){
	global $MYSQL_MOST_RECENT_CON;
	return $MYSQL_MOST_RECENT_CON->insert_id;
}
function mysql_error(){
	global $MYSQL_MOST_RECENT_CON;
	return $MYSQL_MOST_RECENT_CON->error;
}

function mysql_real_escape_string($str){
	global $MYSQL_MOST_RECENT_CON;
	return $MYSQL_MOST_RECENT_CON->real_escape_string($str);
}



?>
