<?php
require_once('pageIncludes/killTree.inc.php');
if (isset($_SESSION['UID'])) {
    $UID = $_SESSION['UID'];
    $playerData = mysql_oneline("SELECT * FROM `users` WHERE `UID`='$UID'");
} else {
    $UID = null;
    $playerData = array();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php placeTabIcon(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>UMBC HvZ - Kill Trees</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="/style.css" rel="stylesheet" type="text/css" media="all" />
<link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'/>
<script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
<?php htmlHeader(); ?>
</head>
<body>
<div id="wrapper">
	<?php pageHeader(); ?>
	<div id="page" class="container">
		<div id="content">
			<h1 style="text-align:center; margin-top: 10px;"><b>Kill Trees</b></h1>
			<p style="text-align:center;"><a href="/playerList.php">&larr; Back to Player List</a></p>
			<br/>
			<?php printKillTrees(); ?>
		</div>
		<div id="sidebar">
			<div class="section1">
				<?php displayLoginForm();?>
			</div>
			<br />
			<div class="section4">
				<?php retrieveSlides();?>
			</div>
		</div>
		<div class="clearfix">&nbsp;</div>
	</div>
	<div id="footer" class="container">
		<?php printFooter(); ?>
	</div>
</div>
<script>mermaid.initialize({ startOnLoad: true });</script>
</body>
</html>
