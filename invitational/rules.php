<?php header("Location: https://www.marylandfoamalliance.com/umbchvzinvi", true, 301);
exit();
?>
<?php
require_once('../includes/util.php');
load_config('config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) include "includes/loginUpdate.php";
?>
<html>
<head><?php placeTabIcon(); ?><?php include_once 'includes/htmlHeader.php';?></head>
<body><?php include_once 'includes/header.php';?><div class="content">

<div class="entry">

<ul style="list-style-type:none">
<li><b><h3>Schedule of Events</h3></b></li>
<li><b><h4>Due to COVID precautions, we are asking that individuals <a href="https://www.signupgenius.com/go/10C0E4CAFA62FA2F8C43-umbc" target="_blank">sign up for a time slot</a> for briefing to control the amount of individuals within our lecture hall. Please bear in mind that these time slots are subject to change depending on anticipated attendance. UMBC STUDENTS/ALUMNI: If possible, PLEASE attend one of our Friday evening safety briefings. </h4></b></li>
<li><b>Friday, April 29th:</b>
    <ul style="list-style-type:none">
		<li>6:00pm<ul style="list-style-type:none"> Registration / First Pre-game Briefing / Check-in in Meyerhoff 030</ul></li>
		<li>7:00pm<ul style="list-style-type:none"> Registration / Second Pre-game Briefing / Check-in in Meyerhoff 030</ul></li>
	</ul>
</li>
</ul>
<br/>
<ul style="list-style-type:none">
<li><b>Saturday, April 30th:</b>
    <ul style="list-style-type:none; text-align:">
    <li>Starting at 7:20am, 7:50am, and 8:20am <ul style="list-style-type:none;"> Day-of Registration / Check-in / Pre-game briefing in Meyerhoff 030</ul></li>
    <li>9:00am - Game Start <ul style="list-style-type:none;"> First Mission Briefing (at Check-in location)</ul></li></ul>
<b><h3> The final mission should end tentatively around 5pm</h3></b>

<p>The schedule and these rules are subject to change as the date of the invitational draws closer. If you have questions, concerns, or suggestions, please email them to <a href="mailto:umbchvzofficers@gmail.com">umbchvzofficers@gmail.com</a>.</p>
<ul style="list-style-type:none">
	<li><strong>Rule #1 - Don&#39;t be a douchebag!</strong></li>
	<li>Rule #2 - Use common sense; think before you act</li>
	<li>Rule #3 - Ask if you have questions; don't assume</li>
	<li>Rule #4 - No realistic-looking weaponry</li>
	<li>Rule #5 - Don&#39;t use blasters indoors</li>
	<li>Rule #6 - No vehicles during gameplay</li>
</ul>
<p>
&nbsp;</p>

<h3><span style="text-decoration:underline;">UMBC Coronavirus Pandemic Rules</span>:</h3>
<div>
	<p>Please be aware of UMBC's COVID policies, available in full <a href = https://covid19.umbc.edu>here</a></p>
	<p>In order to participate in our Invitational, we ask all players to read and understand UMBC's COVID-19 
	guidelines and abide by them while playing our game. These requirements were last updated as of 
	April 5, 2022 and are likely to change between now and the day of the event. Please check back here 
	closer to the event for updated information.</p>
	<p>Despite UMBC relaxing its restrictions, We are still requiring the following for the sake of the safety of all players.</p>
	<ul>
		<li>All individuals on campus must be fully immunized against COVID-19 at this time.</li>
	<ul>
		<li>Please bring your vaccination card or an easily-readable photograph of it for the moderator team to check your vaccination status
		<li>If you are unable to receive the vaccine, please contact the officers at the email address listed above <strong>as soon as possible</strong></li>
	</ul>
		<li>KN95 or equivalent masks are required in any indoor space unless eating or drinking.</li>
		<li>Double surgical masks, N95s, and KF94s are considered KN95 equivalent.</li>
		<li>While indoors, individuals must social distance as much as possible.</li>
		<li>The maximum density for indoor club meetings is 50 people.</li>
		<li>If you are experiencing flu-like symptoms, please stay home.</li>
	</ul>
</div>

<h3><span style="text-decoration:underline;">Required Equipment</span>:</h3>
<div>
	<ul>
		<li>Bandanna (any contrasting color permitted)</li>
		<li>Foam darts/disks/rounds, dart/disk/round launcher and/or socks</li>
		<li>ID card/Team Card (provided at check-in)</li>
	</ul>
</div>
<p>&nbsp;</p>
<h3><span style="text-decoration:underline;">Blaster and Ammo Requirements</span>:</h3>
<div>
	<ul style="padding-left:30px;">
		<li>All blasters and other foam dart/disk launchers are permitted, provided:
			<ul>
				<li>The dart/disk is fired through mechanical means (this does not include blowguns)</li>
				<li>The blaster is brightly colored with an orange tip.
					<ul>
						<li>Tip: When painting stock blasters, try to make them brighter than the original color.</li>
						<li>See Rule #4 -- No realistic-looking weaponry</li>
					</ul>
				</li>
				<li>The projectile fired from the blaster does not travel faster than 120 feet per second. We have a chronograph that we will use for testing this.</li>
			</ul>
		</li>
		<li>Acceptable ammo types include:
			<ul>
				<li>Nerf/Buzz Bee/Worker Darts</li>
				<li>Nerf Rival Balls</li>
				<li>Nerf Vortex Discs</li>
				<li>Rolled and taped socks
					<ul>
						<li>"Socks" are defined as continuous pieces of cloth rolled and taped/folded accordingly in a ball-like fashion (minimum 1 inch in diameter) 
						<li>If socks are unable to retain ball-like form without tape, tape is required</li>
					</ul>
				</li>
				<li>"Other throwables" 
					<ul>
						<li>Must fit in a 4 inch x 4 inch x 4 inch area</li>
						<li>Must be a minimum 1 inch in diameter in at least 2 perpendicular directions</li>
						<li>Must be made of closed cell foam or cloth</li>
						<li>Can be bound by tape, pursuant that you can still see the material underneath</li>
					</ul>
				</li>
				
			</ul>
		</li>
		<li>Unacceptable equipment includes:
			<ul>
				<li>FVJ Darts and any other "hard-tip" dart with a tip that does not compress</li>
				<ul>
					<li>Voberry and similar dart types such as Little Valentines are technically 
					allowed but are <strong>strongly</strong> discouraged</li>
				</ul>
				<li>Hyper rounds and other similar and smaller ammo types</li>
				<li>Homemade blaster ammo</li>
				<li>Melee weapons, such as foam NERF swords.</li>
				<li>Blowguns</li>
				<li>CO2 and HPA blasters</li>
				<li>Any blaster/ammo related to Airsoft or Paintball</li>
			</ul>
		</li>
		<li>Darts may only be modified by cutting the foam of the dart.
			<ul>
				<li>The foam end of the dart must be at least twice the length of the tip of the dart.</li>
				<li>Other dart modifications are not allowed</li>
			</ul>
		
		<li>All blasters and ammo MUST be approved at check-in.</li>
	</ul>
</div>
<p>&nbsp;</p>
<h3><span style="text-decoration:underline;">Area of Play</span>:</h3>
<ul>
<li>All outdoor areas within Hilltop Circle. Players are only allowed inside buildings to use the bathroom, to get water/snacks, if they become injured/sick, or during announced breaks.</li>
<li><strong>Only socks and throwing darts</strong>&nbsp;may be used in Erickson Courtyard and Harbor Courtyard. Blasters MAY NOT BE USED.</li>
<li>For a human to stun a zombie, or a zombie to tag a human, both parties must have both feet outside of a safe zone.</li>
<li>Do NOT shoot out of or into buses and moving cars.</li>
<li><strong>15-Foot Rule</strong> (An optional method to prevent stand-offs):&nbsp;If a human is leaving a safe zone and is aware a zombie is by the door, the human may ask the zombie(s) to step 15 feet away from the door. &nbsp;This is to prevent stand-offs and so both parties are able to start from a level playing field. &nbsp;If you ask the zombies to step away from the door, you must follow through exiting the specified door. &nbsp;Not doing so is a violation of rule #1.</li>
</ul>
<p>&nbsp;</p>
<h3><span style="text-decoration:underline;">Bandannas</span>:</h3>
<div>
<ul>
<li><strong>Humans</strong>:
<ul>
<li>Worn around the upper arm, between the elbow and the shoulder</li>
</ul>
</li>
<li><strong>Zombies</strong>:
<ul>
<li>Worn around the head when unstunned (active)</li>
<li>Worn around the neck when stunned</li>
</ul>
</li>
<li><strong>Moderators</strong>:
<ul>
<li>Worn on both arms if general moderator</li>
<li>Worn on both arms <b>and</b> around the head if a Super-Zombie</li>
<li>Worn on both arms <b>and</b> around the neck if a stunned Super-Zombie</li>
</ul>
</li>
</ul>
<ul>
<li>Bandannas must be clearly visible from 10 feet on all sides.</li>
<li>In a safe zone, you may either continue wearing your bandanna as if you were in play, or not at all.</li>
<!---<li>If directly asked if you or your group is untagged commonly phrased as &#39;clean&#39;, &nbsp;you must tell the truth.</li> -->
<li><b>You may not wear your bandanna in a way that is misleading. Your bandanna should be clearly visible from as many angles as possible</b></li>
</ul>
</div>
<p>&nbsp;</p>
<h3><span style="text-decoration:underline;">Stunning</span>:</h3>
<ul>
<li>When hit with a dart, disk or sock, a zombie is stunned
<ul>
<li>Humans may only throw one dart or disk at a time.</li>
<li>Humans may throw more than one sock at a time</li>
<li>A zombie is stunned if a dart or sock hits anywhere, even loose clothing, hair, and anything the zombie might be carrying, like a backpack.</li>
</ul>
</li>
<li>Stunned zombies may NOT interact with the game in any way.
<ul>
<li>Stunned zombies may not intentionally shield other zombies from darts and socks.</li>
<li>Stunned zombies may stay in the same spot they were stunned, or move away from humans.</li>
<li>Stunned zombies may not follow humans, but they may follow groups of unstunned zombies, as long as the group of unstunned zombies is larger than the group of stunned zombies.</li>
</ul>
</li>
<li><strong>Re-stuns</strong>: If a stunned zombie is hit again, their timer is reset.</li>
<li>Once the stun timer is up, the Zombie may unstun</li>
</ul>
<p>&nbsp;</p>
<h3><span style="text-decoration:underline;">Tagging</span>:</h3>
<div>
<ul>
<li>A tag is a firm touch to any part of a human. Blaster tags do <b>NOT</b> count.</li>
<li>You cannot make a tag as a zombie if you are visibly carrying a blaster.</li>
<li>Zombies DO NOT become stunned after tagging a human</li>
</ul>
</div>
<p><strong>- When Tagged by a Zombie</strong></p>
<ul>
<li>You become a stunned zombie</li>
</ul>
<!--- <p><strong>- Super-humans</strong></p>
<ul>
<li>Once tagged, a Human becomes a super-human. The same rules apply as for a regular human with the following exception:
<ul>
<li><strong>The Double-tap Rule</strong>:&nbsp;If a zombie tags a super-human, the super-human becomes a stunned zombie for the remainder of their death timer.</li>
</ul>
</li>
<li>You may use your time as a super-human to stun as many zombies as you can find, but don&#39;t forget rule #1 and that these zombies will soon be your teammates.</li>
</ul> -->
<p>&nbsp;</p>
<h3><span style="text-decoration:underline;">Warning System</span>:</h3>
<p><strong>Accidental</strong>:<br>
The result of your actions was completely unforeseeable. e.g. Tripping over a ledge and falling onto someone.</p>
<p><strong>Warning</strong>:<br>
Foreseeable result of your actions. e.g. Running up to somebody near stairs and losing control of momentum, causing the person to fall down the stairs.<br>
<strong></strong></p>
<p><strong>Removal</strong>:<br>
INTENTIONAL USE OF EXCESSIVE FORCE.</p>
<p><strong>EXCESSIVE FORCE INCLUDES</strong>:</p>
<ul>
<li>Full-body tackles or body checks</li>
<li>Anything that knocks a person off their feet</li>
<li>Shots intentionally aimed or strikes at sensitive areas (Head, Crotch, etc.)
<ul><li>Hits to these areas still count as hits but should be strongly avoided</li></ul>
</li>
</ul>
<p><strong>PLEASE REPORT ANY VIOLENT INCIDENTS.</strong><br>
&nbsp;</p>
<p><strong>PLEASE REMEMBER RULE 1... DON'T BE A DOUCHEBAG!</strong><br>
&nbsp;</p>
<!-- <h3><span style="text-decoration:underline;">Special Rules</span>:</h3>
<p><strong>Players Using Power Chairs:</strong></p>
<p>If a human is hit by a sock specially marked as a zombie sock, although the specific wording may be slightly different, then that human is frozen for 10 seconds. &nbsp;These socks are only usable by players who need to use a power chair for mobility purposes.</p> -->
</div></div></body>
<body><?php include_once 'includes/footer.php';?>
<?php 
echo '<footer><div>';
echo '<p style="text-align:center; font-size:75%; color:black;">All characters, events, and organizations appearing in this work are fictitious. Any resemblance to real persons, living or dead, real events, real organizations, or real partnerships made by UMBC are purely coincidental.</p>';
echo '</div></footer>';?>
</html>
