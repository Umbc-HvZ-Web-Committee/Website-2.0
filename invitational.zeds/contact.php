<?php
require_once('includes/load_config.php');
require_once('includes/quick_con.php');
load_config('config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) include $_SERVER['DOCUMENT_ROOT'].$config['folder']."includes/loginUpdate.php";
?>
<html>
<head><?php placeTabIcon(); ?><?php include_once 'includes/htmlHeader.php';?></head>
<body><?php include_once 'includes/header.php';?><div class="content">
For more information, please contact the office of Cassandra Glass:<br/><br/>
<br/>
Z.E.D.S<br/>
c/o Cassandra Glass<br/>
1000 Hilltop Circle<br/>
Baltimore, MD 21250<br/>
<br/>
<a href="mailto:zeds413@gmail.com">zeds413@gmail.com</a><br/>
443-599-9236
</div><?php include_once 'includes/footer.php';?></body>
</html>