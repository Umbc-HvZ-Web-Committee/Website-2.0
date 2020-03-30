<?php
require_once('includes/pdf.php');
require_once('includes/util.php');
load_config('config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('includes/loginUpdate.php');

?>

<html><body>
<form method="POST" enctype="multipart/form-data">
<?php
var_dump($_FILES);
?>
<input name="uploadedfile" type="file" /><br />
<input type="submit" value="Upload File" />
</form>
</body></html>
