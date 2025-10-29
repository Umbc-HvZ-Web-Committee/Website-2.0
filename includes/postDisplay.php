<?php
function displayPost($postID){
	$ret = mysql_oneline("SELECT title, author, content, TIMESTAMPDIFF(SECOND, posted, CURRENT_TIMESTAMP) AS postedDiff FROM blog_posts WHERE postID = '$postID';");
	$title = $ret['title'];
	$authorID = $ret['author'];
	$posted = secondsToHumanReadable($ret['postedDiff']);
	$content = $ret['content'];
	
	$ret = mysql_oneline("SELECT fname, uname FROM users WHERE UID='$authorID';");
	$name = $ret['fname'];
	$username = $ret['uname'];
	
	//TODO COMMENTS (no not like this, I mean post comments)
	echo '<div class="post">';
		echo '<div class="title">';
			echo '<h2>'.$title.'</h2>';
			echo '<p>Posted by <a href="#'.$username.'">'.$name.'</a> '.$posted;
			if($_SESSION['isAdmin'] >= 2){
			echo '&nbsp';
			echo '<button type="button" class="delete">Delete Post</button>';
			echo '<button type="button" id='.$postID.'class="confirm" style="display:none;">Click this if you are sure.</button>';
			}
			echo '</p>';
		echo '</div>';
		echo '<div class="entry">';
			echo $content;
		echo '</div>';
	echo '</div>';
}

function displayPosts($startIndex, $numPosts){
	$ret = mysql_query("SELECT postID FROM blog_posts ORDER BY posted DESC LIMIT $startIndex, $numPosts;");
	while($row = mysql_fetch_assoc($ret)){
		displayPost($row['postID']);
	}
}

function deletePost($postID){
	if($_SESSION['isAdmin'] >= 2){
		mysql_query("DELETE FROM blog_posts WHERE postID = $postID;");
	}
}
?>