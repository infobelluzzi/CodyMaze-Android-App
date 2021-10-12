<?php
require_once(dirname(__FILE__) . '/sendmail.php');
$response=array();
if(isset($_REQUEST['guid'])){
	$guid=$_REQUEST['guid'];
	if(isset($_REQUEST['date'])){
		$date=$_REQUEST['date'];
		$date=date("j F Y",strtotime($date));
		if(isset($_REQUEST['name'])){
			$name=$_REQUEST['name'];
			if(isset($_REQUEST['email'])){
				$email=$_REQUEST['email'];
				if(isset($_REQUEST['time'])){
					$time=$_REQUEST['time'];
					$tArray=array();
					$tArray=explode(":",$time);
					switch (count($tArray)){
						case 1:
							$itime=$tArray[0];
							break;
						case 2:
							$itime=$tArray[0]*60+$tArray[1];
							break;
						case 3:
							$itime=$tArray[0]*3600+$tArray[1]*60+$tArray[2];
							break;
						default:
							$itime=0;
					}			
					$ris=sendMail($guid,$date,$name,$email,$itime); 
				}
				else {
					$ris=0;
				}	
			}
			else {
				$ris=0;		
			}	
		}
		else {
			$ris=0;		
		}	
	}
	else {
		$ris=0;
	}	
}
else {
	$ris=0;
}	
$response['status']=$ris;
header('Content-type: application/json');
echo json_encode($response);