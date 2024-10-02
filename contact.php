<?php 
require_once('pageIncludes/contact.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php placeTabIcon(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>UMBC HvZ - Meet The Admins</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="/style.css" rel="stylesheet" type="text/css" media="all" />
<link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'/>
<?php htmlHeader(); ?>
</head>
<body>
<div id="wrapper">
	<?php pageHeader(); ?>
	<div id="page" class="container">
		<div id="content">
			<h1 style="text-align:center; margin-top: 10px;"><b>Meet the Admins</b></h1><br/>
			<!-- Weeklong Mod list Table-->
			<!-- <h2 style="text-align:center;">For Help with the Current Weeklong</h2></br> -->
<!-- 			<table align="center" border="1" cellspacing="1" cellpadding="3"> -->

<!-- 			<tr bgcolor="#800000"><th><font color="white">Contact Regarding</font></th><th><font color="white">Person</font></th> -->
<!-- 			<th><font color="white">Email</font></th><th><font color="white">Cell Phone</font></th></tr> -->

<!-- 			<tr bgcolor="#C0C0C0" align="center"><td>Weeklong-specific Questions</td> -->
<!-- 			<td>Ryan Siegel</td> -->
<!-- 			<td>rsiegel1@umbc.edu</td> -->
<!-- 			<td>(301) 717-9754</td></tr> -->
		
<!-- 			<tr bgcolor="#FFFFFF" align="center"><td><font>Weeklong-specific Questions</font></td> -->
<!-- 			<td><font>Ryan Gutzat</font></td> -->
<!-- 			<td><font>gury1@umbc.edu</font></td> -->
<!-- 			<td><font>(302) 276-5851</font></td></tr> -->
		
<!-- 			<tr bgcolor="#C0C0C0" align="center"><td><font>General Game Questions</font></td> -->
<!-- 			<td><font>Marcus Jordan</font></td> -->
<!-- 			<td><font>jmarcus1@umbc.edu</font></td> -->
<!-- 			<td><font>(443) 488-4756</font></td></tr> -->
		
<!-- 			<tr bgcolor="#FFFFFF" align="center"><td>Website / General Game</td> -->
<!-- 			<td>Travis Amtower</td> -->
<!-- 			<td>tamtow1@umbc.edu</td> -->
<!-- 			<td>(301) 801-8848</td></tr> -->

<!--  			</table></br></br> -->
			
			
			<br/><h2 style="text-align:center;">Current Officer Board</h2></br>
			
			<!-- THIS BIG UGLY SECTION IS FOR THE TABLE CREATION -->
			
			<table align="center" border="1" cellspacing="1" cellpadding="3">

			<tr bgcolor="#800000"><th><font color="white">Position</font></th><th><font color="white">Person</font></th>
			<th><font color="white">Email</font></th><th><font color="white">Cell Phone Number</font></th></tr>

			<tr bgcolor="#C0C0C0" align="center"><td>President</td>
			<td>Fernando Chicas</td>
			<td>fchicas1@umbc.edu</td>
			<td>(240)-280-4215</td></tr>
		
			<tr bgcolor="#FFFFFF" align="center"><td><font>Vice President</font></td>
			<td>Ian Moon</td>
			<td>supremeeviloverlord@umbc.edu</td>
 			<td>(240)-618-0268</td></tr>
		
			<tr bgcolor="#C0C0C0" align="center"><td>Treasurer</td>
			<td>Seth Ramsland</td>
			<td>sramsla1@umbc.edu</td>
			<td>(443)-924-8732</td></tr>
		
			<tr bgcolor="#FFFFFF" align="center"><td><font>Secretary</font></td>
			<td>Alexandra (Sasha) Kleeman</td>
			<td>k189@umbc.edu</td>
			<td>(443)-863-0826</td></tr>
		
			<tr bgcolor="#C0C0C0" align="center"><td>Recruitment & Advertising</td>
			<td>Pauly Dillingham</td>
			<td>punkrevolutionnow@umbc.edu</td>
			<td>(443)-630-1393</td></tr>
			</table><br/><br/>
		
			<!-- END BIG UGLY TABLE CREATION FOR OFFICERS -->
			
			<br/>
			<i>For general help, email the officers at <a href="mailto:umbchvzofficers@gmail.com">umbchvzofficers@gmail.com</a></i>
			<br/><br/>
			<p/>
			
			<h2 style="text-align:center;">Current Subofficers</h2><br/>
			<!--<h2 style="text-align:center;">Due to the currrent pandemic, the Officer Board has chosen to not appoint sub officers this semester.</h2><br/> -->
			<!-- BEGIN TABLE CREATION FOR SUBOFFICERS -->
			
			<?php
    			// Define the array of names
    			$names = array("Delia Teter", "Eli Kramer-Smyth", "Marisa Mengel", "Alex Holtz", "Miles Campbell", "Alvin Jecinta", "Vianne Stanford");

				// Randomly select two names to repeat
				$repeated_names = array_rand($names, 1);
				//$names[] = $names[$repeated_names];
				//$names[] = $names[$repeated_names];

    			// Shuffle the names to randomize their positions
   				shuffle($names);
			?>

			<table align="center" border="1" cellspacing="1" cellpadding="3">
				<tr bgcolor="#FFFFFF" align="center">
					<td><?php echo $names[0]; ?></td>
					<td><?php echo $names[1]; ?></td>
					<td><?php echo $names[2]; ?></td>
				</tr>
				<tr bgcolor="#C0C0C0" align="center">
					<td><?php echo $names[3]; ?></td>
					<td><?php echo $names[4]; ?></td>
					<td><?php echo $names[5]; ?></td>
				</tr>
				<tr bgcolor="#FFFFFF" align="center">
					<td><?php echo $names[6]; ?></td>
					<td><?php echo $names[7]; ?></td>
					<td><?php echo $names[8]; ?></td>
				</tr>
			</table>
			
			<!-- END TABLE CREATION FOR SUBOFFICERS -->
			
			<br/><i> Subofficers have the right to make calls on deaths, holds, etc, even when playing.</i><br/>
			<i>If you believe a Subofficer has abused this power, please contact the officers.</i><br/>
			
			<br/><br/>
			
			<h2 style="text-align:center;">Web Committee</h2><br/> 
			
			<!-- BEGIN "TABLE" CREATION FOR WEB COMMITTEE -->
			
			<table align="center" border="1" cellspacing="1" cellpadding="3">
			<tr bgcolor="#FFFFFF" align="center">
			<td>Fernando Chicas</td><td>Sarah Clarke</td></tr>
			<tr bgcolor="#C0C0C0" align="center">
			<td>Eli Kramer-Smyth</td><td>Seth Ramsland</td></tr>
			</table>
			
			<!-- END "TABLE" CREATION FOR WEB COMMITTEE -->
			
			<!-- <br/><b>The officer liaison to Web Committee is TBD.</b><br/> -->
			
			<br/><i>The Web Committee is responsible for maintaining and developing our club's website. 
			For technical help, please contact a web committee member (if you are in contact with them) or the officers.</i><br/>
			
		</div>
		<?php printSidebar(); ?>
		<div class="clearfix">&nbsp;</div>
	</div>
	<div id="footer" class="container">
		<?php printFooter(); ?>
	</div>
</div>
</body>
</html>
