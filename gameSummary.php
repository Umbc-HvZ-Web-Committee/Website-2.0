<?php
require_once('../pageIncludes/gameSummary.inc.php'); //MAKE SURE THIS FILE PATH IS ACTUALLY CORRECT
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php placeTabIcon(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>UMBC HvZ - Game Summaries</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="/style.css" rel="stylesheet" type="text/css" media="all" />
<link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'/>
<?php htmlHeader(); ?>
</head>
<body>
<a name="top"></a>
<div id="wrapper">
	<?php pageHeader(); ?>
	<div id="page" class="container">
		<div id="content">
		
		<?php if($_SESSION['isAdmin'] >= 1 || $_SESSION['isBetaTester'] >= 1) { ?>
			<h2>Long Game Logs</h2><br/><br/>
			
			Select a long game from the menu below to view the log of that game. 
			Due to a bug involving previous legacy mechanics, data on the time of kills from 2016-2021
			is very likely innaccurate and possibly missing. For player status changes that have accurate 
			time log data, the time recorded is the time that it was logged on this website, 
			not necessarily the time that this change occured within the game.<br/>
			
			<?php
			echo '<select name="longGameSelect" id="sel"/>';
			$qret = mysql_query("SELECT * FROM long_games WHERE 1 ORDER BY startDate DESC;");
			while($ret = mysql_fetch_assoc($qret)){
				$id = $ret['gameID'];
				$name = $ret['title'];
				echo "<option value=\"$id\">$name</option>";
			}
			echo '</select>';
			?>
			
			</div>
			<?php printSidebar(); 
			?>
		
		<?php } ?>
		<div class="clearfix">&nbsp;</div>
	</div>
	<div id="footer" class="container">
		<?php printFooter(); ?>
	</div>
</div>
</body>
</html>