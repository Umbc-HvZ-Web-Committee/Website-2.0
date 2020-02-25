<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/tcpdf/config/lang/eng.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/tcpdf/tcpdf.php');

// length of an inch in point units
function inches($x){
	return 27.6*$x;
}

function openPDF($author, $title){
	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor($author);
	$pdf->SetTitle($title);
	$pdf->SetSubject($title);

	// remove default header/footer
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	//set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	//set some language-dependent strings
	$pdf->setLanguageArray($l);
	return $pdf;
}

function page1($pdf, $uid, $username, $name, $phoneNumber, $killID){
$box1 = "$name
$username
$phoneNumber

Bandanna #:";

$box2 = '<table><tr><td>
Kill ID:<br/>
'.$killID.'<br/>
<br/>
Give this to<br/>
the zombie<br/>
who tags you!
</td><td align="right">
<img src="http://chart.apis.google.com/chart?chs=200x200&cht=qr&chl='.$killID.'"/><br/>
Scan to kill!&nbsp;&nbsp;&nbsp;&nbsp;
</td></tr></table>';

$box3 = '<table><tr><td>
<img src="http://chart.apis.google.com/chart?chs=200x200&cht=qr&chl='.$username.'"/><br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sign in
</td><td align="right">'.
//'<img src="http://chart.apis.google.com/chart?chs=200x200&cht=qr&chl=ADMIN_0"/><br/>
//Log a kill&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
'</td></tr></table>';

	$pdf->SetFont('times', '', 20);
	$pdf->AddPage();

	// write cells
	$pdf->MultiCell(inches(3.5), inches(2.25), $box1, 1, 'L', false, 0, 5+inches(0), 5+inches(0), true, 0, false, true, inches(2.25), 'T', false);
	$pdf->writeHTMLCell(inches(3.5), inches(2.25), 5+inches(3.5), 5+inches(0), $box2, 1);
	$pdf->writeHTMLCell(inches(3.5), inches(2.25), 5+inches(0), 5+inches(2.25), $box3, 1);
	$pdf->writeHTMLCell(inches(3.5), inches(2.25), 5+inches(3.5), 5+inches(2.25), "LEAVE THIS SPACE BLANK", 1);

	// write the mission attendance chart as its own set of cells
// 	$oneFifth = inches(3.5)/5.0;
// 	$oneHalf = inches(2.25)/2.0;
// 	$pdf->writeHTMLCell($oneFifth, $oneHalf, 5+inches(3.5)+(0*$oneFifth), 5+inches(2.25)+(0*$oneHalf), "Mon<br/><br/>Day", 1);
// 	$pdf->writeHTMLCell($oneFifth, $oneHalf, 5+inches(3.5)+(1*$oneFifth), 5+inches(2.25)+(0*$oneHalf), "Tue<br/><br/>Day", 1);
// 	$pdf->writeHTMLCell($oneFifth, $oneHalf, 5+inches(3.5)+(2*$oneFifth), 5+inches(2.25)+(0*$oneHalf), "Wed<br/><br/>Day", 1);
// 	$pdf->writeHTMLCell($oneFifth, $oneHalf, 5+inches(3.5)+(3*$oneFifth), 5+inches(2.25)+(0*$oneHalf), "Thurs<br/><br/>Day", 1);
// 	$pdf->writeHTMLCell($oneFifth, $oneHalf, 5+inches(3.5)+(4*$oneFifth), 5+inches(2.25)+(0*$oneHalf), "Fri<br/><br/>Day", 1);
// 	$pdf->writeHTMLCell($oneFifth, $oneHalf, 5+inches(3.5)+(0*$oneFifth), 5+inches(2.25)+(1*$oneHalf), "Mon<br/><br/>Night", 1);
// 	$pdf->writeHTMLCell($oneFifth, $oneHalf, 5+inches(3.5)+(1*$oneFifth), 5+inches(2.25)+(1*$oneHalf), "Tue<br/><br/>Night", 1);
// 	$pdf->writeHTMLCell($oneFifth, $oneHalf, 5+inches(3.5)+(2*$oneFifth), 5+inches(2.25)+(1*$oneHalf), "Wed<br/><br/>Night", 1);
// 	$pdf->writeHTMLCell($oneFifth, $oneHalf, 5+inches(3.5)+(3*$oneFifth), 5+inches(2.25)+(1*$oneHalf), "Thurs<br/><br/>Night", 1);
// 	$pdf->writeHTMLCell($oneFifth, $oneHalf, 5+inches(3.5)+(4*$oneFifth), 5+inches(2.25)+(1*$oneHalf), "Fri<br/><br/>Night", 1);
}

function page2($pdf, $uid, $username, $name, $phoneNumber, $killID){
$box1 = "$name
$username
$phoneNumber

Kill ID: $killID

Give this to the zombie who kills you.";

$box2 = "Benjamin Harris - (443) 599 9236
Grant Wunderlin - (240) 405 2745
Andrew Allison - (410) 703 2034
Elizabeth Kohl - (410) 708 3589
Peggy Barnett - (301) 351 0610
Katy McNeely - (443) 528 8981
UMBC Police: (410) 455 5555
(non-emergency: (410) 455 3136)";

$box3 = "Locations:

Mon., February 25 - ITE 104
Tues., February 26 - ENGR 027
Wed., February 27- ITE 104
Thurs., February 28 - ITE 104
Fri., March 1 - ITE 104";

$box4 = "umbchvz.com
umbchvzofficers@gmail.com
facebook.com/umbchvz
twitter.com/umbchvz

Unless stated otherwise, safe time starts
10 minutes before the start of a mission.";

	$pdf->SetFont('times', '', 16);
	$pdf->AddPage();
	
	$width = $pdf->getPageWidth();

	$pdf->MultiCell(inches(3.5), inches(2.25), $box1, 1, 'L', false, 0, $width-(5+inches(7)), (5+inches(0)), true, 0, false, true, inches(2.25), 'T', false);
	$pdf->MultiCell(inches(3.5), inches(2.25), $box2, 1, 'L', false, 0, $width-(5+inches(3.5)), (5+inches(0)), true, 0, false, true, inches(2.25), 'T', false);
	$pdf->MultiCell(inches(3.5), inches(2.25), $box3, 1, 'L', false, 0, $width-(5+inches(7)), (5+inches(2.25)), true, 0, false, true, inches(2.25), 'T', false);
	$pdf->MultiCell(inches(3.5), inches(2.25), $box4, 1, 'L', false, 0, $width-(5+inches(3.5)), (5+inches(2.25)), true, 0, false, true, inches(2.25), 'T', false);
}

function closePDF($pdf, $fileName){
	$pdf->Output($fileName, 'I');
}
?>