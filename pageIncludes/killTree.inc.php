<?php
require_once('includes/util.php');
load_config('config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('includes/loginUpdate.php');
$settings = get_settings();

function printKillTrees() {
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
				DAYNAME(lp.deathTime)  AS dayName,
				DATE(lp.deathTime)     AS killDate,
				TRIM(CONCAT(COALESCE(ku.fname,''), ' ', COALESCE(ku.lname,''))) AS killerName,
				TRIM(CONCAT(COALESCE(pu.fname,''), ' ', COALESCE(pu.lname,''))) AS victimName
			FROM long_players lp
			LEFT JOIN users ku ON lp.killerID = ku.UID
			LEFT JOIN users pu ON lp.playerID = pu.UID
			WHERE lp.gameID = '$gameID'
			  AND lp.killerID IS NOT NULL
			  AND lp.killerID != ''
			  AND lp.deathTime != '0000-00-00 00:00:00'
			ORDER BY lp.deathTime
		");

		// $players: UID => ['name', 'deathDate', 'dayName']
		// $days:    killDate => dayName  (in chronological order)
		// $edges:   [ [killerUID, victimUID], ... ]
		$players = array();
		$days    = array();
		$edges   = array();

		while ($row = mysql_fetch_assoc($kills)) {
			$vid  = $row['playerID'];
			$kid  = $row['killerID'];
			$date = $row['killDate'];

			// Victim gains a death record (first kill wins if somehow duplicated)
			if (!isset($players[$vid])) {
				$players[$vid] = array(
					'name'      => trim($row['victimName'])  ?: $vid,
					'deathDate' => $date,
					'dayName'   => $row['dayName'],
				);
			}

			// Killer may not have died yet — record them as a survivor for now
			if (!isset($players[$kid])) {
				$players[$kid] = array(
					'name'      => trim($row['killerName']) ?: $kid,
					'deathDate' => null,
					'dayName'   => null,
				);
			}

			// Track dates in the order first seen (already sorted by deathTime)
			if (!isset($days[$date])) {
				$days[$date] = $row['dayName'];
			}

			$edges[] = array($kid, $vid);
		}

		if (empty($players)) {
			echo "<p>No kills recorded in this game.</p>";
			continue;
		}

		// Group victims by death date for subgraph placement
		$byDate = array();
		foreach ($players as $uid => $p) {
			if ($p['deathDate'] !== null) {
				$byDate[$p['deathDate']][] = $uid;
			}
		}

		$lines = array("graph TD");

		// Survivors (never died) declared above all subgraphs so they sit at the top
		foreach ($players as $uid => $p) {
			if ($p['deathDate'] === null) {
				$nid   = preg_replace('/[^a-zA-Z0-9]/', '_', $uid);
				$label = str_replace('"', "'", $p['name']);
				$lines[] = "  {$nid}[\"{$label}\"]";
			}
		}

		// One subgraph per calendar date — each victim node lives in the day they died.
		// Using the date string as the subgraph ID avoids collisions when the same
		// weekday name occurs in multiple weeks.
		foreach ($days as $date => $dayName) {
			$sgId    = 'day_' . str_replace('-', '_', $date);
			$sgLabel = str_replace('"', "'", $dayName);
			$lines[] = "  subgraph {$sgId}[\"{$sgLabel}\"]";

			if (!empty($byDate[$date])) {
				foreach ($byDate[$date] as $uid) {
					$nid   = preg_replace('/[^a-zA-Z0-9]/', '_', $uid);
					$label = str_replace('"', "'", $players[$uid]['name']);
					$lines[] = "    {$nid}[\"{$label}\"]";
				}
			}

			$lines[] = "  end";
		}

		// Kill edges — node IDs are global so chains flow continuously across subgraphs
		foreach ($edges as $edge) {
			$kn = preg_replace('/[^a-zA-Z0-9]/', '_', $edge[0]);
			$vn = preg_replace('/[^a-zA-Z0-9]/', '_', $edge[1]);
			$lines[] = "  {$kn} --> {$vn}";
		}

		$mermaid = implode("\n", $lines);
		echo "<div class=\"mermaid\">\n{$mermaid}\n</div>\n<br/>\n";
	}
}
?>
