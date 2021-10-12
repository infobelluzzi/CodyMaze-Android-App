<?php
require_once(dirname(__FILE__) . '/data.php');
require_once(dirname(__FILE__) . '/lib.php');
require('fpdf/fpdf.php');
$pdf = new FPDF('L');
if(isset($_REQUEST['date'])){
	$date=$_REQUEST['date'];
	$certs = db_table_query("SELECT * FROM `certificates_list` WHERE date LIKE '{$date}%' ORDER BY date ASC");
	$ncert=count($certs);
	foreach ($certs as $item) {
		$pdf->AddPage();
		$pdf->SetFont('Arial','I',20);
		$id=$item[0];
		$name=$item[2];
		$date=substr($item[3],0,10);
		$nameMsg=$name;
		$dateMsg="This certificate, released on ".$date.", has the following identifier:";
		$idMsg=$id;
		$pdf->Image('images/header.png',40,14,210,0);
		$pdf->Cell(0,60,'',0,1,'C');
		$pdf->Cell(0,0,'Certificate of completion',0,1,'C');
		$pdf->SetFont('Arial','B',32);
		$pdf->Cell(0,30,$name,0,1,'C');
		$pdf->SetFont('Arial','',20);
		$pdf->Cell(0,10,'has successfully completed the Maker Summer Camp CodyMaze activity, performing',0,1,'C');
		$pdf->Cell(0,10,'code interpretation with basic coding instructions, among which sequence of elementary',0,1,'C');
		$pdf->Cell(0,10,'instructions, conditionals, repetitions, and conditional repetitions.',0,1,'C');
		$pdf->Cell(0,10,'',0,1,'C');
		$pdf->Cell(0,10,$dateMsg,0,1,'C');
		$pdf->Cell(0,10,$idMsg,0,1,'C');
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(0,29,'IIS Belluzzi-Fioravanti CC-BY-SA A. Bogliolo http://www.coodemooc.org/codymaze',0,1,'L');
		$pdf->Image('images/logo-summer-school.jpg',200,165,80,0);
		$i++;
	}
}	
else {
	$pdf->Cell(0,10,'Certificate not available',0,1,'C');			
}	
$pdf->Output();
?>