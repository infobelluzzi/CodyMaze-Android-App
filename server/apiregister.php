<?php
/*
 * CodyMaze for
 * Maker Summer Camp 2021
 * ===================
 * IIS Belluzzi Fioravanti
 * ===================
 * Client registering  API
 * used by remote app
 * create a new telegram_id and return id to client a JSON string
 */
require_once(dirname(__FILE__) . '/data.php');
require_once(dirname(__FILE__) . '/lib.php');
global $memory;
$memory = new stdClass();
$encoded = json_encode($memory);
$lastid = db_scalar_query("SELECT `telegram_id` FROM `user_status` ORDER BY `telegram_id` DESC LIMIT 1");
if(is_null($lastid)) {
	Logger::error("Failed to get last insert id", __FILE__, $lastid);
	$response['status']='fail';
	$response['data']['telegram_id']=0;
}	
else {
	$telegram_id=$lastid+1;	
	$insert_result = db_perform_action("INSERT INTO `user_status` (`telegram_id`, `last_memory_update`, `memory`) VALUES({$telegram_id}, NOW(), '" . db_escape($encoded) . "')");
	if($insert_result === false) {
		Logger::error("Failed to create conversation memory", __FILE__, $telegram_id);
		$response['status']='fail';
		$response['data']['telegram_id']=0;
	}
	else {
		Logger::debug("Conversation memory created: {$encoded}", __FILE__, $telegram_id);
		$response['status']='ok';
		$response['data']['telegram_id']=$telegram_id;
	}
}
//send response to client in JSON format
header('Content-type: application/json');
echo json_encode($response);
