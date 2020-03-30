<?php
require_once('../pageIncludes/admin/blogPost.inc.php');
$playerData = mysql_oneline("SELECT * FROM users WHERE UID='{$_SESSION['uid']}'");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php placeTabIcon(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>UMBC HvZ - Admin Panel</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="/style.css" rel="stylesheet" type="text/css" media="all" />
<link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css' />
<?php htmlHeader(); ?>
</head>
<body>
<div id="wrapper">
	<?php pageHeader(); ?>
	<div id="page" class="container">
		<div id="content">
		
		<?php 
		$author = $playerData['UID'];
		$title = $_POST['title'];
		$timestamp = date('Y-m-d G:i:s');
		$content = $_POST['content'];
		$content = mysql_real_escape_string($content);
		$title = mysql_real_escape_string($title);
		$sql = "INSERT into `blog_posts`(`title`, `author`, `posted`, `content`) VALUES ('$title', '$author', '$timestamp', '$content');";
		$query = mysql_query($sql);
		?>
		
		<h2>The post has been generated!</h2>
		
		<script type="text/javascript">

		//window.location="http://umbchvz.com/admin/blogPost.php";
		
		</script>
		
		</div>
		<div id="sidebar">
			<div class="section1">
				<?php displayLoginForm();?>
			</div>
		</div>
		<div class="clearfix">&nbsp;</div>
	</div>
	<div id="footer" class="container">
		<?php printFooter(); ?>
	</div>
</div>
</body>
</html>