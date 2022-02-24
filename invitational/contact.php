<?php
require_once('../includes/util.php');
load_config('config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) include $_SERVER['DOCUMENT_ROOT'].$config['folder']."includes/loginUpdate.php";
?>
 
<html>
<head><?php placeTabIcon(); ?><?php include_once 'includes/htmlHeader.php';?></head>
<body><?php include_once 'includes/header.php';?><div class="content">
<body><?php include_once 'includes/footer.php';?>
The UMBC HvZ Club<br/>
1000 Hilltop Circle<br/>
Baltimore, MD 21250<br/>
<br/>
<br/>
For help, email the UMBC HvZ officers at <a href="mailto:umbchvzofficers@gmail.com">umbchvzofficers@gmail.com</a><br/>
<br/>

</div></body>
<?php echo '<footer><div>';
echo '<p style="text-align:center; font-size:75%; color:black;">All characters, events, and organizations appearing in this work are fictitious. Any resemblance to real persons, living or dead, real events, real organizations, or real partnerships made by UMBC are purely coincidental.</p>';
echo '</div></footer>';?>
</html>