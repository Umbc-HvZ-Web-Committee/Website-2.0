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

	echo <<<CSS
<style>
.kill-timeline {
    width: 100%;
    margin-bottom: 30px;
    border: 1px solid #ccc;
    border-radius: 4px;
    overflow: hidden;
}

.kill-day-section {
    position: relative;
    display: flex;
    align-items: flex-start;
    min-height: 120px;
    overflow: hidden;
}

.kill-day-sidebar {
    position: relative;
    width: 72px;
    min-height: 100%;
    flex-shrink: 0;
    background: rgba(139, 0, 0, 0.07);
    border-right: 2px dashed #999;
    display: flex;
    align-items: center;
    justify-content: center;
    align-self: stretch;
    z-index: 2;
}

.kill-day-sidebar span {
    writing-mode: vertical-rl;
    text-orientation: mixed;
    transform: rotate(180deg);
    font-size: 13px;
    font-weight: bold;
    color: #8E0000;
    text-transform: uppercase;
    letter-spacing: 3px;
    white-space: nowrap;
}

.kill-day-watermark {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 80px;
    font-weight: 900;
    color: rgba(139, 0, 0, 0.04);
    pointer-events: none;
    z-index: 0;
    text-transform: uppercase;
    white-space: nowrap;
    user-select: none;
}

.kill-day-content {
    position: relative;
    z-index: 1;
    flex: 1;
    padding: 16px;
    overflow-x: auto;
}

.kill-day-divider {
    border: none;
    border-top: 2px dashed #bbb;
    margin: 0;
}

.kill-day-content .mermaid {
    text-align: center;
}
</style>
CSS;

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

		$dayList = array_keys($killsByDay);
		$lastDay = end($dayList);

		echo "<div class=\"kill-timeline\">\n";

		foreach ($killsByDay as $day => $dayKills) {
			$abbr = isset($dayAbbr[$day]) ? $dayAbbr[$day] : substr($day, 0, 3);
			$dayEsc = htmlspecialchars($day);

			echo "  <div class=\"kill-day-section\">\n";
			echo "    <div class=\"kill-day-sidebar\"><span>$dayEsc</span></div>\n";
			echo "    <div class=\"kill-day-watermark\">$dayEsc</div>\n";
			echo "    <div class=\"kill-day-content\">\n";

			$lines = array("graph TD");

			foreach ($dayKills as $kill) {
				$killerNodeId = $abbr . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $kill['killerID']);
				$victimNodeId  = $abbr . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $kill['playerID']);

				$killerLabel = str_replace('"', "'", trim($kill['killerName']) ?: $kill['killerID']);
				$victimLabel  = str_replace('"', "'", trim($kill['victimName'])  ?: $kill['playerID']);

				$lines[] = "  {$killerNodeId}[\"{$killerLabel}\"] --> {$victimNodeId}[\"{$victimLabel}\"]";
			}

			$mermaidText = implode("\n", $lines);
			echo "      <div class=\"mermaid\">\n$mermaidText\n      </div>\n";
			echo "    </div>\n";
			echo "  </div>\n";

			if ($day !== $lastDay) {
				echo "  <hr class=\"kill-day-divider\">\n";
			}
		}

		echo "</div>\n<br/>\n";
	}
}
?>
