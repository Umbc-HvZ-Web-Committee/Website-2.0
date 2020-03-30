<?php
require_once('pageIncludes/about.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php placeTabIcon(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>UMBC HvZ</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="/style.css" rel="stylesheet" type="text/css" media="all" />
<link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'/>
<?php htmlHeader(); ?>
</head>
<body>
<a name="top"></a>
<div id="wrapper">
	<?php pageHeader(); ?>
	<div id="page" class="container">
		<div id="content">

			<h1 style="text-align:center; margin-top: 10px;"><b>FAQs</b></h1><br/><br/>
	
			<br/>
			<ul style="text-align: left;">
			<?php
			foreach($questions as $question){
				echo "<li><h4><a href=\"#Q{$question['number']}\">{$question['title']}</a></h4></li>";
			}
			?>
			</ul>
			<p><br clear="right" /></p>
			<hr/>
			<?php
			foreach($questions as $question){
				echo "<a name=\"Q{$question['number']}\"></a><h2>{$question['title']}</h2>";
				echo "<p>{$question['answer']}</p>";
				//echo '<a href="#top">To Top</a>;
				echo '<br/><br/>';
			}
			?>
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
