<?php
if(isset($_REQUEST['name'])){
	$name=$_REQUEST['name'];
	if(isset($_REQUEST['date'])){
		$date=$_REQUEST['date'];
		if(isset($_REQUEST['id'])){
			$id=$_REQUEST['id'];
			if(isset($_REQUEST['time'])){
				$time=$_REQUEST['time'];
				$nameMsg=$name;
				$dateMsg="This certificate, released on ".$date.", has the following identifier:";
				$idMsg=$id;
				$date=date("j F Y",strtotime($date));
//				if(is_null($time)||($time==0)||!is_numeric($time)){
				if(is_null($time)||($time==0)){
					$tmMsg=" ";	
				}
				else {
					$tArray=array();
					$tArray=explode(":",$time);
					//$tmMsg=">".$tArray[1]."<";
					switch (count($tArray)){
						case 1:
							if($tArray[0]==1){
								$tmMsg='in '.$tArray[0].' second ';
							}
							else {
								$tmMsg='in '.$tArray[0].' seconds ';								
							}	
							break;
						case 2:
							if($tArray[0]==1){
								$tmMsg='in '.$tArray[0].' minute ';
							}
							else {
								$tmMsg='in '.$tArray[0].' minutes ';								
							}
							if($tArray[1]==1){
								$tmMsg.=''.$tArray[1].' second ';
							}
							else {
								$tmMsg.=''.$tArray[1].' seconds ';
							}
							break;
						case 3:
							if($tArray[0]==1){
								$tmMsg='in the time of '.$tArray[0].' hour ';
							}
							else {
								$tmMsg='in the time of '.$tArray[0].' hours ';								
							}
							if($tArray[1]==1){
								$tmMsg.=''.$tArray[1].' minute ';
							}
							else {
								$tmMsg.=''.$tArray[1].' minutes ';
							}
							if($tArray[2]==1){
								$tmMsg.=''.$tArray[2].' second ';
							}
							else {
								$tmMsg.=''.$tArray[2].' seconds ';
							}
							break;
						default:
							$tmMsg=' ';
					}	
/*
					switch (count($tArray)){
						case 1:
							if($tArray[0]==1]){
								$tMsg=" ".$tArray[0]." second ";		
							}	
							else{
								$tMsg=" ".$tArray[0]." seconds ";										
							}	
							break;
						case 2:
							if($tArray[0]==1]){
								$tMsg=" ".$tArray[0]." minute ";		
							}	
							else{
								$tMsg=" ".$tArray[0]." minutes ";										
							}	
							if($tArray[1]==1]){
								$tMsg=" ".$tArray[0]." second ";		
							}	
							else{
								$tMsg=" ".$tArray[0]." seconds ";										
							}	
							break;
						case 3:
							if($tArray[0]==1]){
								$tMsg=" ".$tArray[0]." hour ";		
							}	
							else{
								$tMsg=" ".$tArray[0]." hours ";										
							}	
							if($tArray[0]==1]){
								$tMsg=" ".$tArray[0]." minute ";		
							}	
							else{
								$tMsg=" ".$tArray[0]." minutes ";										
							}	
							if($tArray[1]==1]){
								$tMsg=" ".$tArray[0]." second ";		
							}	
							else{
								$tMsg=" ".$tArray[0]." seconds ";										
							}	
							break;
						default:
							$tMsg="";
					}	
*/					
/*					
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
*/					
				}				
			}
			else {
				$id=" ";
				$nameMsg=" ";
				$dateMsg=" ";
				$idMsg=$id;		
				$tmMsg=" ";
			}	
		}
		else {
			$id=" ";
			$nameMsg=" ";
			$dateMsg=" ";
			$idMsg=$id;		
			$tmMsg=" ";
		}	
	}
	else {
			$id=" ";
			$nameMsg=" ";
			$dateMsg=" ";
			$idMsg=$id;
			$tmMsg=" ";
	}	
}
else {
			$id=" ";
			$nameMsg=" ";
			$dateMsg=" ";
			$idMsg=$id;			
			$tmMsg=" ";
}	
?>
<!DOCTYPE html>
<html lang="it-IT">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div><img src="images/header.png" width="100%"></div>
<div style="text-align:center"><p style="font-size: 80%; font-style:italic; font-family: Arial, Helvetica, sans-serif;">Certificate of completion</p></div>
<div style="text-align:center"><p style="font-weight:bold; font-size: 150%; font-style:normal; font-family: Arial, Helvetica, sans-serif;"><?php echo $name ?></p></div>
<div style="text-align:center"><p style="font-size: 80%; font-style:normal; font-family: Arial, Helvetica, sans-serif;">has successfully completed the Belluzzi-Fioravanti CodyMaze activity <?php echo $tmMsg ?> performing code interpretation with basic coding instructions, among which sequence of elementary instructions, conditionals, repetitions, and conditional repetitions.</p></div>
<div style="text-align:center"><p style="font-size: 80%; font-style:normal; font-family: Arial, Helvetica, sans-serif;">This certificate, released on <?php echo $date ?>, has the following identifier:</p></div>
<div style="text-align:center"><p style="font-size: 80%; font-style:normal; font-family: Arial, Helvetica, sans-serif;"><?php echo $id ?></p></div>
<div style="text-align:center"><img src="images/logo-belluzzi-2021.jpg" width="50%"></div>
<div style="text-align:center"><p style="font-size: 50%; font-style:normal; font-family: Arial, Helvetica, sans-serif;">CC-BY-SA IIS Belluzzi-Fioravanti derived from CC-BY-SA A. Bogliolo http://www.coodemooc.org/codymaze</p></div>
</body>
</html>
<?php
/*			
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
			//$pdf->Cell(0,10,'Certificate not available',0,1,'C');			
		}	
	}
	else {
		//$pdf->Cell(0,10,'Certificate not available',0,1,'C');			
	}
}	
else {
	//$pdf->Cell(0,10,'Certificate not available',0,1,'C');				
}	
//$pdf->Output();
*/
?>