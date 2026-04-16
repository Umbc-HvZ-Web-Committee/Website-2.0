<?php
require_once('includes/util.php');
load_config('config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('includes/loginUpdate.php');
$settings = get_settings();

function printKillTrees() {
	$dayAbbr = array(
		'Sunday'    => 'Sun',
		'Monday'    => 'Mon',
		'Tuesday'   => 'Tue',
		'Wednesday' => 'Wed',
		'Thursday'  => 'Thu',
		'Friday'    => 'Fri',
		'Saturday'  => 'Sat',
	);

	$ret = mysql_query(
		"SELECT DISTINCT lp.gameID, COALESCE(lg.title, lp.gameID) AS title
		 FROM long_players lp
		 LEFT JOIN long_games lg ON lp.gameID = lg.gameID
		 WHERE lp.killerID IS NOT NULL AND lp.killerID != ''
		   AND lp.deathTime != '0000-00-00 00:00:00'
		 ORDER BY lp.gameID"
	);

	if (!$ret) {
		echo "<p>Error retrieving game data.</p>";
		return;
	}

	$games = array();
	while ($row = mysql_fetch_assoc($ret)) {
		$games[] = $row;
	}

	if (empty($games)) {
		echo "<p>No kill data found.</p>";
		return;
	}

	foreach ($games as $game) {
		$gameID = $game['gameID'];
		$title  = htmlspecialchars($game['title']);

		echo "<h2>Game: $title</h2>\n";

		$kills = mysql_query("
			SELECT
				lp.playerID,
				lp.killerID,
				DAYNAME(lp.deathTime)    AS dayName,
				DAYOFWEEK(lp.deathTime)  AS dayNum,
				TRIM(CONCAT(COALESCE(ku.fname,''), ' ', COALESCE(ku.lname,''))) AS killerName,
				TRIM(CONCAT(COALESCE(pu.fname,''), ' ', COALESCE(pu.lname,''))) AS victimName
			FROM long_players lp
			LEFT JOIN users ku ON lp.killerID = ku.UID
			LEFT JOIN users pu ON lp.playerID = pu.UID
			WHERE lp.gameID = '$gameID'
			  AND lp.killerID IS NOT NULL
			  AND lp.killerID != ''
			  AND lp.deathTime != '0000-00-00 00:00:00'
			ORDER BY DAYOFWEEK(lp.deathTime), lp.deathTime
		");

		$killsByDay = array();
		while ($row = mysql_fetch_assoc($kills)) {
			$day = $row['dayName'];
			if (!isset($killsByDay[$day])) {
				$killsByDay[$day] = array();
			}
			$killsByDay[$day][] = $row;
		}

		if (empty($killsByDay)) {
			echo "<p>No kills recorded in this game.</p>";
			continue;
		}

		$lines = array("graph TD");

		foreach ($killsByDay as $day => $dayKills) {
			$abbr    = isset($dayAbbr[$day]) ? $dayAbbr[$day] : substr($day, 0, 3);
			$lines[] = "  subgraph $day";

			foreach ($dayKills as $kill) {
				$killerNodeId = $abbr . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $kill['killerID']);
				$victimNodeId = $abbr . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $kill['playerID']);

				$killerLabel = str_replace('"', "'", trim($kill['killerName']) ?: $kill['killerID']);
				$victimLabel = str_replace('"', "'", trim($kill['victimName'])  ?: $kill['playerID']);

				$lines[] = "    {$killerNodeId}[\"{$killerLabel}\"] --> {$victimNodeId}[\"{$victimLabel}\"]";
			}

			$lines[] = "  end";
		}

		$mermaidText = implode("\n", $lines);

		echo "<div class=\"mermaid\">\n$mermaidText\n</div>\n<br/>\n";
	}
}
?>
