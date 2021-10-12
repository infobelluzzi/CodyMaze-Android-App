<?php
error_reporting(E_ALL);
require_once(dirname(__FILE__) . '/data.php');
require_once(dirname(__FILE__) . '/lib.php');
require('fpdf/fpdf.php');
$n = db_scalar_query("SELECT count(*) FROM `certificates_list` WHERE `telegram_id` = 30");
echo $n."<br>";
$c = db_row_query("SELECT * FROM certificates_list WHERE telegram_id = 30 LIMIT 1");
if(is_null($c)) {
	echo "null result<br>";	
}	
else {
	echo $c[0]."<br>";
	echo $c[1]."<br>";
	echo $c[2]."<br>";
}
$certs = db_table_query("SELECT * FROM `certificates_list` WHERE telegram_id = 30 ORDER BY date ASC");
$ncert=count($certs);
foreach ($certs as $item) {
	echo $item[0]."<br>";
	echo $item[1]."<br>";
	echo $item[2]."<br>";
	echo $item[3]."<br>";
	$i++;
}
/*
$pdf = new FPDF('L');
$pdf->AddPage();
$pdf->SetFont('Arial','I',20);
if(isset($_REQUEST['name'])){
	$name=$_REQUEST['name'];
	if(isset($_REQUEST['date'])){
		$date=$_REQUEST['date'];
		if(isset($_REQUEST['id'])){
			$id=$_REQUEST['id'];
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
			$pdf->Image('images/logo-summer-school.jpg',200,165,80,0);
		}
		else {
			$pdf->Cell(0,10,'Certificate not available',0,1,'C');			
		}	
	}
	else {
		$pdf->Cell(0,10,'Certificate not available',0,1,'C');			
	}
}	
else {
	$pdf->Cell(0,10,'Certificate not available',0,1,'C');				
}	
$pdf->Output();
*/
?>