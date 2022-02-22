<?php
require_once('../includes/util.php');
load_config('config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) include "includes/loginUpdate.php";
?>

<html>
<head><?php placeTabIcon(); ?><?php include_once 'includes/htmlHeader.php';?></head>
<body><?php include_once 'includes/header.php';?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

</script>
<!-- div class="fb-like-box" data-href="https://www.facebook.com/zeds413" data-width="400" data-height="395" data-show-faces="true" data-stream="true" data-header="false" style="float: left;"></div -->
<!-- div style="width:380px; margin:10px; float:right;" -->
<div class="content"> <!--style="width:850px; margin-left:0px;"-->

   

<p><h2>Scooby Doo and the Zombie Plauge</h2></p>

<p>A mysterious air has fallen over Coolsville, and the dead are rising! Join Scooby Doo and the Mystery Inc. gang as they investigate the zombie plague.</p>

<p>The game will be played at the main UMBC campus, located at 1000 Hilltop Circle, Baltimore, Maryland on Saturday, April 30th! Please arrive
by 8:00 AM. 

<br><br><i><b>Please bring your zombie-fighting equipment with you</b></i>. 
</p>

<p>To be allowed to participate, we will need a signed waiver form. For participants under 18 years old, use 
<a href="https://docs.google.com/document/d/1pjNzcmGGVdREZN8mS7pKExztxGcQrAEuHMY8tygySE4/view" target="_blank">this form.</a>
For participants aged 18 years or older, use 
<a href="https://docs.google.com/document/d/1rur0XeMYxvhORjugJGy1C6gWof98h58P8t1HZ8vFHhw/view" target="_blank">this form.</a>
</p>

<center><p style="text-align:center;"><h3>If you have not already registered, please do so at 
the top of the page. If you would like to be part of the &#39;zombie horde&#39;, please fill out 
<a href="https://docs.google.com/forms/d/e/1FAIpQLScHnzjrdWfgmJQ70ndXQgBH1hP-BzVGYY1HeXQPygfkFo8Pvg/viewform" target="_blank">this form</a> in addition to registering above.</h3></center>

</div>
</div>
</body>

<?php 
echo '<footer><div>';
echo '<p style="text-align:center; font-size:75%; color:black;">All characters, events, and organizations appearing in this work are fictitious. Any resemblance to real persons, living or dead, real events, real organizations, or real partnerships made by UMBC are purely coincidental.</p>';
echo '</div></footer>';

mysql_query("DELETE FROM `schools` WHERE `name` = '';");
?>
</html>