<?php
/*
 * CodyMaze for
 * Maker Summer Camp 2021
 * ===================
 * IIS Belluzzi Fioravanti
 * ===================
 * Callback processing API
 * used by remote app
 * command: /apicallback.php??chat_id=<chat_it>&lang=<lang>&data=<data>
 * <chat_id>: registered telegram_id 1..n
 * <lang>: ISO code for language: en,it, ...
 * <data>: card <dir> <state> where <dir>:n,e,s,w and <state>t,f,e
 */
require_once(dirname(__FILE__) . '/data.php');
require_once(dirname(__FILE__) . '/lib.php');
require_once(dirname(__FILE__). '/callback_msg_processing.php');
$callback=array();
$response=array();
if (isset($_REQUEST['chat_id'])) {
	$callback['message']['chat']['id']=$_REQUEST['chat_id'];	
	if (isset($_REQUEST['lang'])) {
		$callback['message']['from']['language_code']=$_REQUEST['lang'];	
		if (isset($_REQUEST['data'])) {
			$callback['data']=$_REQUEST['data'];	
			$response=callback_msg_processing($callback);
		}	 
		else {
			$response['status']='fail';
			$response['data']['msg']='missing data';
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
header('Content-type: application/json');
echo json_encode($response);