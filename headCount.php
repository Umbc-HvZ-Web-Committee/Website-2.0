<?php
require_once('includes/util.php');
load_config('config.txt');
my_quick_con($config);

$curGame = getNextLongGame();
$curGame = $curGame['gameID'];

$ret = mysql_oneline("SELECT COUNT(*) AS cnt FROM long_players WHERE state<0 AND gameID='$curGame'");
$zombies = $ret['cnt'];
$ret = mysql_oneline("SELECT COUNT(*) AS cnt FROM long_players WHERE state>0 AND gameID='$curGame'");
$humans = $ret['cnt'];
$ret = mysql_oneline("SELECT COUNT(*) AS cnt FROM long_players WHERE state=2 AND gameID='$curGame'");
$hiddenOZ = $ret['cnt'];
echo "$humans human".($humans==1?"":"s")." and $zombies zombie".($zombies==1?"":"s");
if($hiddenOZ){
echo ", counting the ".($hiddenOZ==1?"":"$hiddenOZ ")."OZ".($hiddenOZ==1?"":"s")." as both human and zombie";
}
echo ".";
?>