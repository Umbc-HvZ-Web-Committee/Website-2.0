<?php
function colorNameToCSS($str){
	switch(strtolower($str)){
		case "red": return "background: rgb(255,0,17); border-color: rgb(255,255,255); color: rgb(255,255,255)";
		case "orange": return "background: rgb(255,102,0); border-color: rgb(255,255,255); color: rgb(255,255,255)";
		case "yellow": return "background: rgb(255,255,0); border-color: rgb(0,0,0); color: rgb(0,0,0)";
		case "green": return "background: rgb(0,255,0); border-color: rgb(0,0,0); color: rgb(0,0,0)";
		case "blue": return "background: rgb(0,0,155); border-color: rgb(255,255,255); color: rgb(255,255,255)";
		case "violet":
		case "purple": return "background: rgb(204,0,255); border-color: rgb(255,255,255); color: rgb(255,255,255)";
		case "ultraviolet": return "background: rgb(255,255,255); border-color: rgb(0,0,0); color: rgb(0,0,0)";
	}
}

function colorNameToBackground($str){
	$css = colorNameToCSS($str);
	$cur = preg_split('/;/', $css);
	$cur = $cur[0];
	$pos = strpos($cur, '(');
	$len = strpos($cur, ')') - $pos;
	$cur = substr($cur, $pos+1, $len-1);
	$parts = preg_split('/,/', $cur);
	return $parts;
}

function colorNameToForeground($str){
	$css = colorNameToCSS($str);
	$cur = preg_split('/;/', $css);
	$cur = $cur[2];
	$pos = strpos($cur, '(');
	$len = strpos($cur, ')') - $pos;
	$cur = substr($cur, $pos+1, $len-1);
	$parts = preg_split('/,/', $cur);
	return $parts;
}
?>