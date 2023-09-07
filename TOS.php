<html><body><?php
require_once('includes/load_config.php');
require_once('includes/quick_con.php');
load_config('config.txt');
my_quick_con($config);
$ret = mysql_oneline("SELECT `value` FROM `settings` WHERE `key`='TOS';");
echo "test";
echo $ret['value'];
?>
</body></html>
