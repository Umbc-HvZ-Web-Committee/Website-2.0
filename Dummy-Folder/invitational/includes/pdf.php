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

function createID($pdf, $row, $col, $userInfo){
	$COL_SPACING = 3.6;
	//bounding box
	$pdf->MultiCell(inches(3.5), inches(2.25), "", 1, 'L', false, 0, inches(.25+($col*$COL_SPACING)), inches(.25+($row*2.35)), true, 0, true, true, inches(2.25), 'T', false);
	
	// define barcode style
	$style = array(
	    'position' => '',
	    'align' => 'C',
	    'stretch' => false,
	    'fitwidth' => true,
	    'cellfitalign' => '',
	    'border' => true,
	    'hpadding' => 'auto',
	    'vpadding' => 'auto',
	    'fgcolor' => array(0,0,0),
	    'bgcolor' => false, //array(255,255,255),
	    'text' => true,
	    'font' => 'helvetica',
	    'fontsize' => 8,
	    'stretchtext' => 4
	);
	//logo
	$pdf->MultiCell(inches(3.5), inches(1), "<img src=\"/invitational/images/IDBadge.gif\"/>", 0, 'L', false, 0, inches(.25+($col*$COL_SPACING)), inches(.25+($row*2.35)), true, 0, true, true, inches(2.25), 'T', false);
	//clearance level
	$fg = colorNameToForeground($userInfo['team']);
	$bg = colorNameToBackground($userInfo['team']);
	$pdf->SetTextColor($fg[0], $fg[1], $fg[2]);
	$pdf->setFillColor($bg[0], $bg[1], $bg[2]);
	$pdf->SetFont('helvetica', 'B', 13);
	$pdf->MultiCell(inches(1.35), inches(0.5), "Clearance level:\n".$userInfo['team'], 0, 'L', true, 0, inches(.25+($col*$COL_SPACING)+.1), inches(.25+($row*2.35)+1.25), true, 0, false, true, inches(2.25), 'T', false);
	$pdf->SetFont('helvetica', '', 12);
	$pdf->SetTextColor(0,0,0);
	//name
	$pdf->MultiCell(inches(1.8), inches(0.4), $userInfo['name'], 0, 'L', false, 0, inches(.25+($col*$COL_SPACING)+1.7), inches(.25+($row*2.35)+1.2), true, 0, false, true, inches(2.25), 'T', false);
	$pdf->SetFont('helvetica', '', 16);
	//ID & barcode
	$pdf->write1DBarcode($userInfo['UID'], 'C39', inches(.25+($col*$COL_SPACING)+1.6), inches(.25+($row*2.35)+1.5), inches(1.8), inches(0.65), 0.4, $style, 'N');
	//any possible overlay image
	if($userInfo['overlayImage']!=null){
		$pdf->MultiCell(inches(3.5), inches(2.25), "<img src=\"".$userInfo['overlayImage']."\"/>", 0, 'L', false, 0, inches(0.25+($col*$COL_SPACING)), inches(.25+($row*2.35)), true, 0, true, true, inches(2.25), 'T', false);
	}
	$pdf->Ln();
}

function closePDF($pdf, $fileName){
	$pdf->Output($fileName, 'I');
}
?>