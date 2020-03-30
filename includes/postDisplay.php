<?php
function displayPost($postID){
	$ret = mysql_oneline("SELECT title, author, content, TIME_TO_SEC(TIMEDIFF(CURRENT_TIMESTAMP, posted)) AS postedDiff FROM blog_posts WHERE postID = '$postID';");
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
			echo '<p>Posted by <a href="#'.$username.'">'.$name.'</a> '.$posted.'</p>';
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
?>