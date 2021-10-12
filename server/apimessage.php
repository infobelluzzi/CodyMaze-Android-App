<?php
/*
 * CodyMaze for
 * Maker Summer Camp 2021
 * ===================
 * IIS Belluzzi Fioravanti
 * ===================
 * Basic message processing API
 * used by remote app
 * command: /apimessage.php?chat_id=<chat_it>&lang=<lang>&text=command
 * <chat_id>: registered telegram_id 1..n
 * <lang>: ISO code for language: en,it, ...
 * <command>: game command in the format /command [param]
 * valid commands: /start, /start <pos>, /reset 
 * <pos>: <row><column> whew <row>:a..e and <column> 1..5
 */
require_once(dirname(__FILE__) . '/data.php');
require_once(dirname(__FILE__) . '/lib.php');
require_once(dirname(__FILE__). '/message_msg_processing.php');
$message=array();
$response=array();
if (isset($_REQUEST['chat_id'])) {
	$message['chat']['id']=$_REQUEST['chat_id'];	
	if (isset($_REQUEST['lang'])) {
		$message['from']['language_code']=$_REQUEST['lang'];	
		if (isset($_REQUEST['text'])) {
			$message['text']=$_REQUEST['text'];	
			$response=message_msg_processing($message);
		}	 
		else {
			$response['status']='fail';
			$response['data']['msg']='missing text';
		}	 
	}	 
	else {
		$response['status']='fail';
		$response['data']['msg']='missing lang';
	}	 
}	 
else {
	$response['status']='fail';
	$response['data']['msg']='missing chat id';
}	 
//$message['from']['language_code']='it';
//$message['text']="/start a1";
//echo $message['chat']['id'].'<br>';
//echo $message['text'].'<br>';
//message_msg_processing($message);
 //send response to client in JSON format
header('Content-type: application/json');
echo json_encode($response);