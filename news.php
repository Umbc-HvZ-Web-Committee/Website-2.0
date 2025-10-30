<?php
require_once('pageIncludes/home.inc.php');
if(!isset($_SESSION)) session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['postID'])) {
    if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] >= 2) {
        $id = mysql_real_escape_string($_POST['postID']);
        mysql_query("DELETE FROM blog_posts WHERE postID = " . $id);
        header('Location: '.$_SERVER['PHP_SELF']);
        exit;
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><?php placeTabIcon(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>UMBC HvZ - News</title>
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
			<?php displayPosts(0,5); ?>
		</div>
		<?php printSidebar(); ?>
		<div class="clearfix">&nbsp;</div>
	</div>
	<div id="footer" class="container">
		<?php printFooter(); ?>
	</div>
</div>
</body>
<form id="deleteForm" method="post" style="display:none">
    <input type="hidden" name="postID" id="deletePostID" value="">
</form>
</html>

<script defer>
	var deleteButtons = document.getElementsByClassName("delete");
	var confirmButtons = document.getElementsByClassName("confirm");

    var isAdmin = <?php echo (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] >= 2) ? 'true' : 'false'; ?>;

	for(let i = 0; i < deleteButtons.length; i++) {
  		deleteButtons[i].onclick = function() {
    		confirmButtons[i].style.display = "inline-block";
		}

		confirmButtons[i].onclick = function() {
			if (!isAdmin) return;
			document.getElementById('deletePostID').value = confirmButtons[i].id;
			document.getElementById('deleteForm').submit();
		}
	}
</script>