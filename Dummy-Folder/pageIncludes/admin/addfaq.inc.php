<?php
require_once('../includes/util.php');
load_config('../config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('../includes/loginUpdate.php');
$settings = get_settings();

require_once('../includes/ajaxCRUD/preheader.php');
require_once('../includes/ajaxCRUD/ajaxCRUD.class.php');
$faqEditor = new ajaxCRUD("Question", "faq", "number", "../includes/ajaxCRUD/");

$faqEditor->omitPrimaryKey();

$faqEditor->displayAs("number", "Number");
$faqEditor->displayAs("title", "Question");
$faqEditor->displayAs("answer", "Answer");

$faqEditor->setTextareaHeight("answer", 200);

$faqEditor->formatFieldWithFunction("answer", "shorten");

function shorten($val){
	if(strlen($val)>53) return htmlspecialchars(substr($val, 0, 50)."...");
	else return htmlspecialchars($val);
}
?>