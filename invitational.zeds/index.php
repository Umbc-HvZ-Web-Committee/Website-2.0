<?php
require_once('includes/util.php');
load_config('config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) include "includes/loginUpdate.php";
?>
<html>
<head><?php placeTabIcon(); ?><?php include_once 'includes/htmlHeader.php';?></head>
<body><?php include_once 'includes/header.php';?><div class="content">
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-like-box" data-href="https://www.facebook.com/zeds413" data-width="400" data-height="395" data-show-faces="true" data-stream="true" data-header="false" style="float: left;"></div>
<div style="width:380px; margin:10px; float:right;">
Welcome potential troubleshooters!<br>
<br>
In an attempt to correct the mistakes of a previous expedition, ZEDs is recruiting troubleshooters. If you wish to apply, please register above.<br>
<br>
Specific rules for the event can be found <a href="http://umbchvz.wordpress.com/invitational/invitational-rules/">here.</a><br>
<br>
Tentative schedule:<br>
<br> 
Friday, April 26th:<br>
7-11 pm – Check-in and Orientation<br>
<br>
Saturday, April 27th:<br>
8 am – Check-in and mini-orientation for late arrivals<br>
10 am – Invitational begins<br>
12 pm - 1:30 pm – Break for lunch<br>
6 pm – Estimated end time; actual time dependent upon individual performance<br>
<br>
Check-in and initial release will be from the <b>Meyerhoff Chemistry Building, Lecture Hall 2</b>.  You can find a map of campus <a href="http://www.umbc.edu/aboutumbc/campusmap/pdf/2012Map_General.pdf">here</a>; Meyerhoff is near the center of campus, right by the library pond.
</div>
</div><?php include_once 'includes/footer.php';?></body>
</html>