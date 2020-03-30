<?php
require_once('includes/util.php');
load_config('config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) include "includes/loginUpdate.php";
?>

<html>
<head><?php placeTabIcon(); ?><?php include_once 'includes/htmlHeader.php';?></head>
<body><div class="content">
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<!-- div class="fb-like-box" data-href="https://www.facebook.com/zeds413" data-width="400" data-height="395" data-show-faces="true" data-stream="true" data-header="false" style="float: left;"></div -->
<!-- div style="width:380px; margin:10px; float:right;" -->
<div class="content"> <!--style="width:850px; margin-left:0px;"-->

<p><h2>Site Under Construction</h2></p>
<center><h3>Check back soon!</h3></center>
</div>
</body>

<?php printFooter();?>
</html>