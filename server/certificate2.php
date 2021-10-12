<?php
require('fpdf/fpdf.php');
$pdf = new FPDF('L');
$pdf->AddPage();
$pdf->SetFont('Arial','I',20);
if(isset($_REQUEST['name'])){
	$name=$_REQUEST['name'];
	if(isset($_REQUEST['date'])){
		$date=$_REQUEST['date'];
		if(isset($_REQUEST['id'])){
			$id=$_REQUEST['id'];
			$time=$_REQUEST['time'];
//			die("time:".$time);
			$nameMsg=$name;
//			$dateMsg="This certificate, released on ".$date.", has the following identifier:";
			$dateMsg="This certificate, released on ".substr($date,0,10).", has the following identifier:";
			$idMsg=$id;
			$tmMsg="1 ora, 2 minuti, 4 secondi";
			//die("time:".$time);
			if(is_null($time)||($time==0)||!is_numeric($time)){
				$tmMsg="";	
			}
			else {
				$h=intdiv($time,3600);
				$m=intdiv(($time%3600),60);
				$s=($time%3600)%60; 
				$tmMsg="in the time of ";
				if($h>0) {
					if($h==1) {
						$tmMsg.=$h." hour ";	
					}
					else {
						$tmMsg.=$h." hours ";					
					}	
				}
				if($m==1) {
						$tmMsg.=$m." minute ";	
				}
				else {
						$tmMsg.=$m." minutes ";					
				}				
				if($s==1) {
					$tmMsg.=$s." second";	
				}
				else {
					$tmMsg.=$s." seconds";					
				}				
			}
			$pdf->Image('images/header.png',40,14,210,0);
			$pdf->Cell(0,60,'',0,1,'C');
			$pdf->Cell(0,0,'Certificate of completion',0,1,'C');
			$pdf->SetFont('Arial','B',32);
			$pdf->Cell(0,30,$name,0,1,'C');
			$pdf->SetFont('Arial','',20);
			$pdf->Cell(0,10,'has successfully completed the Maker Summer Camp CodyMaze activity',0,1,'C');
			$pdf->Cell(0,10,$tmMsg,0,1,'C');
			$pdf->Cell(0,10,'performing code interpretation with basic coding instructions, among which sequence',0,1,'C');
			$pdf->Cell(0,10,'of elementary instructions, conditionals, repetitions, and conditional repetitions.',0,1,'C');
			//$pdf->Cell(0,10,'',0,1,'C');
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
?>