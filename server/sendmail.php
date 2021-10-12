<?php
require('fpdf/fpdf.php');
function sendMail($id,$date,$name,$email,$time){
	//die($id." ".$date." ".$name." ".$email." ".$time);
	$ris=0;
	$pdf = new FPDF('L');
	$pdf->AddPage();
	$pdf->SetFont('Arial','I',20);
//	$dateMsg="This certificate, released on ".substr($date,0,10).", has the following identifier:";
	$dateMsg="This certificate, released on ".$date.", has the following identifier:";
	$idMsg=$id;
	if(is_null($time)||($time==0)||!is_numeric($time)){
		$tmMsg="";	
	}
	else {
		$h=intdiv($time,3600);
		$m=intdiv(($time%3600),60);
		$s=($time%3600)%60; 
		$tmMsg="in ";
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
	$pdf->Cell(0,10,$dateMsg,0,1,'C');
	$pdf->Cell(0,10,$idMsg,0,1,'C');
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(0,25,"CC-BY-SA IIS Belluzzi-Fioravanti derived from CC-BY-SA A. Bogliolo",0,1,'L');
	$pdf->Image('images/logo-belluzzi-2021-cert.jpg',200,165,80,0);
	// Recupero il valore dei campi del form
	$destinatario = $email;
	$mittente = 'info@belluzzifioravanti.it';
	$oggetto = 'Belluzzi-Fioravanti CodyMaze Certificate of completion';
	$messaggio = 'Dear user,<br>your Certificate of completion is in attachment.<br>Best regards<br>IIS Belluzzi-Fioravanti';
	// Valorizzo le variabili relative all'allegato
	//$allegato = 'testfile.pdf';
	//$allegato_type = 'pdf';
	$allegato_name = $name.'.pdf';
	// Creo altre due variabili ad uno interno
	$headers = "From: " . $mittente;
	$msg = "";
	$data=$pdf->Output('S');
	// Adatto il file al formato MIME base64 usando base64_encode
	$data = chunk_split(base64_encode($data));
	// Genero il "separatore"
	// Serve per dividere, appunto, le varie parti del messaggio.
	// Nel nostro caso separerà la parte testuale dall'allegato
	$semi_rand = md5(time());
	$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
	// Aggiungo le intestazioni necessarie per l'allegato
	$headers .= "\nMIME-Version: 1.0\n";
	$headers .= "Content-Type: multipart/mixed;\n";
	$headers .= " boundary=\"{$mime_boundary}\"";
	// Definisco il tipo di messaggio (MIME/multi-part)
	$msg .= "This is a multi-part message in MIME format.\n\n";
	// Metto il separatore
	$msg .= "--{$mime_boundary}\n";
	// Questa è la parte "testuale" del messaggio
	$msg .= "Content-Type: text/html; charset=\"iso-8859-1\"\n";
	$msg .= "Content-Transfer-Encoding: 8bit\n\n"; 
	//$msg .= "Content-Type: text/plain; charset=\"iso-8859-1\"\n";
	//$msg .= "Content-Transfer-Encoding: 7bit\n\n";
	$msg .= $messaggio . "\n\n";
	// Metto il separatore
	$msg .= "--{$mime_boundary}\n";
	// Aggiungo l'allegato al messaggio
	$msg .= "Content-Disposition: attachment; filename=\"{$allegato_name}\"\n";
	$msg .= "Content-Transfer-Encoding: base64;\n";
	$msg .= "Content-Type: application/pdf;\n";
	$msg .= "name={$allegato_name};\n\n";
	$msg .= $data . "\n\n";
	// chiudo con il separatore
	$msg .= "--{$mime_boundary}--\n";
	// Invio la mail
	if (mail($destinatario, $oggetto, $msg, $headers)){
		$ris=1;
	}
	else{
		$ris=-1;
	}
	return $ris;
}	
?>