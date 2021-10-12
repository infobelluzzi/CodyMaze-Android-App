<?php
//iDP 2021
//release 2: separato cmd da msg/email pdf certificate
require_once(dirname(__FILE__) . '/msg_processing_simple.php');
require_once(dirname(__FILE__) . '/htmltopdf.php');
require_once(dirname(__FILE__) . '/sendmail.php');
//end iDP 2021
function callback_msg_processing($callback,$email,$time) {
    global $memory;

    Logger::debug("Processing callback query", __FILE__);
    $callback_data = $callback['data'];
    $chat_id = $callback['message']['chat']['id'];
//iDP 2021
    //$message_id = $callback['message']['message_id'];
    $message_id = 1;
	$response=array();
//end iDP 2021
    memory_load_for_user($chat_id);
    localization_load_user($chat_id, (isset($callback['message']['from']['language_code'])) ? $callback['message']['from']['language_code'] : null);
//iDP 2021
    //if(isset($memory->lastCallbackMessageId) && $message_id == $memory->lastCallbackMessageId) {
    //    // Clear memory
    //    unset($memory->lastCallbackMessageId);
//end iDP 2021 
        if(starts_with($callback_data, 'card ')) {
            $response=cardinal_message_processing($chat_id, $callback_data);
        }
        else if(starts_with($callback_data, 'name ')) {
//iDP 2021		
            $response=name_message_processing($chat_id, $callback_data,$email,$time);
//end iDP 2021			
        }
        else if(starts_with($callback_data, 'language ')) {
//iDP 2021			
//TODO
			$response['status']='ok';
			$response['chat_id']=$chat_id;
			$response['direction']="unk";	
			$response['data']['msg1']='TODO:';
			$response['data']['msg2']='localization';
			$response['data']['state']='f';
			$response['data']['cmd']="";
			$response['data']['dbg']='S0L';	
			$response['data']['lvl']='0';
			$response['data']['cell']="";
			$response['data']['dir']="";
            //localization_message_processing($chat_id, $callback_data);
//end iDP 2021			
        }
        else {
            Logger::error("Unknown callback, data: {$callback_data}", __FILE__, $chat_id);
//iDP 2021
			$response['status']='fail';
			$response['chat_id']=$chat_id;
			$response['direction']="unk";	
			$response['data']['msg1']='TODO:';
			$response['data']['msg2']='unknown callback';
			$response['data']['state']='f';
			$response['data']['cmd']="";
			$response['data']['dbg']='S0U';	
			$response['data']['lvl']='0';
			$response['data']['cell']="";
			$response['data']['dir']="";
        }
//iDP 2021		
    //}
    //else {
    //    Logger::debug("Already processed callback from message ID {$message_id}, ignoring", __FILE__, $chat_id);
    //}
//end iDP 2021
    memory_persist($chat_id);
//iDP 2021
	return $response;
//end iDP 2021	
}

function cardinal_message_processing($chat_id, $callback_data){
//iDP 2021
	$response=array();
//end iDP 2021	
    // Get cardinal position
    $card_code = substr($callback_data, 5, 1);
    $cardinal_info = substr($callback_data, 7, 1);

    Logger::debug("Position {$card_code}, direction {$cardinal_info}", __FILE__, $chat_id);

    if(cardinal_direction_is_valid($card_code)) {
//iDP 2021
/*		
        telegram_send_message(
            $chat_id,
            sprintf(__("Ok, you’re looking %s!"), cardinal_direction_to_description($card_code))
        );
*/	
//		$msg="Ok, you're looking ".cardinal_direction_to_description($card_code)."!\n\n";
		$msg1="Ok, you're looking ".cardinal_direction_to_description($card_code)."!";
//end IDP 2021
        // Get current game state
        $user_status = db_scalar_query("SELECT COUNT(*) FROM moves WHERE telegram_id = {$chat_id} AND reached_on IS NOT NULL");
        $user_null_status = db_scalar_query("SELECT COUNT(*) FROM moves WHERE telegram_id = {$chat_id} AND reached_on IS NULL");

        // If user has started a game, get position, else set to step 1
        if ($user_status !== NULL && $user_status !== false) {
            if ($user_null_status != NULL && $user_null_status !== false) {
                $lvl = $user_status + 1;
            } else {
                $lvl = $user_status;
            }
        } else {
            Logger::warning("Can't find user status. Setting user lvl to 1", __FILE__, $chat_id);
            $lvl = 1;
        }

        Logger::debug("Game lvl: {$lvl}");

        // Get target coordinates (any kind)
        $current_coordinate = db_scalar_query("SELECT cell FROM moves WHERE telegram_id = {$chat_id} AND reached_on IS NULL LIMIT 1");
        if(!$current_coordinate) {
            $current_coordinate = db_scalar_query("SELECT cell FROM moves WHERE telegram_id = {$chat_id} AND reached_on IS NOT NULL ORDER BY reached_on DESC LIMIT 1");
        }
        Logger::debug("Expected user coordinates: {$current_coordinate}");

        $expected_direction = get_direction($current_coordinate);
        if($expected_direction !== $card_code) {
            Logger::info("User direction '{$card_code}' does not match expected one '{$current_coordinate}'", __FILE__, $chat_id);

            if($user_null_status >= 1) {
                // User is looking in wrong direction and has an unreached target
                // Remove end of maze position tuple and send back to last position for new maze
                db_perform_action("DELETE FROM moves WHERE telegram_id = {$chat_id} AND reached_on IS NULL");

                $beginning_position = db_scalar_query("SELECT cell FROM moves WHERE telegram_id = {$chat_id} AND reached_on IS NOT NULL ORDER BY reached_on DESC LIMIT 1");
                $last_position_no_direction = get_position_no_direction($beginning_position);
                $last_position_direction = get_direction($beginning_position);

                Logger::info("Sending back to {$last_position_no_direction}-{$last_position_direction}", __FILE__, $chat_id);
//iDP 2021
				$msg1="You're facing the wrong direction!";
				$msg2="Move back to block ".$last_position_no_direction.", face ".cardinal_direction_to_description($last_position_direction)." and scan the QR Code again.";
				$response['status']='fail';	
				$response['chat_id']=$chat_id;
				$response['direction']="unk";	
				$response['data']['msg1']=$msg1;
				$response['data']['msg2']=$msg2;
				$response['data']['state']=$cardinal_info;
				$response['data']['cmd']="";
				$response['data']['dbg']='E0D';	
				$response['data']['lvl']=$lvl;
				$response['data']['cell']=$last_position_no_direction;
				$response['data']['dir']=$last_position_direction;				
/*
                telegram_send_message(
                    $chat_id,
                    __("You’re facing the wrong direction!") . ' 🙁' .
                    sprintf(__("Move back to block <code>%s</code>, face <i>%s</i> and scan the QR Code again."), $last_position_no_direction, cardinal_direction_to_description($last_position_direction)),
                    array("parse_mode" => "HTML")
                );
*/
//end iDP 2021				
            }
            else {
                // User is looking in wrong direction, but was already sent back to last step (so we can assume he reached it)
                $beginning_position = db_scalar_query("SELECT cell FROM moves WHERE telegram_id = {$chat_id} AND reached_on IS NOT NULL ORDER BY reached_on DESC LIMIT 1");
                $expected_block = get_position_no_direction($beginning_position);
                $expected_direction = get_direction($beginning_position);

                Logger::info("Adjusting direction to {$expected_block}-{$expected_direction}", __FILE__, $chat_id);
//iDP 2021
				$msg="Please face ".cardinal_direction_to_description($expected_direction);
				$msg1="Please face ".cardinal_direction_to_description($expected_direction);
				$response['direction']="unk";	
				$response=request_cardinal_position($chat_id, CARD_NOT_ANSWERING_QUIZ);
				$response['status']='fail';	
				$response['chat_id']=$chat_id;
				$response['direction']="unk";	
				$response['data']['msg1']=$msg1;
				$response['data']['msg2']="";
				$response['data']['cmd']="";
				$response['data']['dbg']='END';	
				$response['data']['lvl']=$lvl;
				$response['data']['cell']=$expected_block;
				$response['data']['dir']=$expected_direction;				
/*
                telegram_send_message(
                    $chat_id,
                    sprintf(__("Please face <i>%s</i>."), cardinal_direction_to_description($expected_direction)),
                    array("parse_mode" => "HTML")
                );
				
*/
            }
            return $response;
//end iDP 2021				
        }

        // Update position with arrival timestamp
        db_perform_action("UPDATE `moves` SET `reached_on` = NOW() WHERE `telegram_id` = {$chat_id} AND `reached_on` IS NULL");

        if($cardinal_info == CARD_ENDGAME_POSITION){
//iDP 2021			
            $response=end_of_game($chat_id);
            return $response;
//iDP 2021			
        }
        if($lvl > 0 && $cardinal_info == CARD_ANSWERING_QUIZ) {
//iDP 2021	
			$msg="Very well! You have found the right spot.";
/*		
            telegram_send_message(
                $chat_id,
                __("Very well! You have found the right spot.")
            );
*/			
//iDP 2021			
        }

        // Prepare instructions for next step
        $new_current_coordinate = db_scalar_query("SELECT cell FROM moves WHERE telegram_id = {$chat_id} AND reached_on IS NOT NULL ORDER BY reached_on DESC LIMIT 1");
        $maze_data = generate_maze($lvl, $chat_id, $new_current_coordinate);
        $maze_arrival_position = $maze_data[1];
        $maze_message = $maze_data[0];
        Logger::info("New instructions for level #{$lvl}: {$maze_data[0]}, destination: {$maze_data[1]}", __FILE__, $chat_id);

        if(!$maze_data || empty($maze_message)) {
            Logger::error("Empty instructions (data: '" . print_r($maze_data, true) . "')", __FILE__, $chat_id);
        }

        $success = db_perform_action("INSERT INTO `moves` (`telegram_id`, `cell`) VALUES($chat_id, '{$maze_arrival_position}')");
        Logger::debug("Success of insertion: {$success}");

        // Send maze
//iDP 2021
/*		
        telegram_send_message(
            $chat_id,
            sprintf(__("<b>%d.</b> Follow these instructions and scan the QR Code at the destination:\n<code>%s</code>"), $lvl, $maze_message),
            array("parse_mode" => "HTML")
        );
*/
//		$msg2="Follow these instructions and scan the QR Code at the destination:\n".$maze_message;
		$msg2="Follow these instructions and scan the QR Code at the destination:";
//		$msg.=$lvl.". Follow these instructions and scan the QR Code at the destination:\n".$maze_message;
		$response['status']='ok';
		$response['chat_id']=$chat_id;
		$response['direction']=$callback_data;	
		$response['data']['msg1']=$msg1;
		$response['data']['msg2']=$msg2;
		$response['data']['state']=$cardinal_info;
		$response['data']['cmd']=$maze_message;
		$response['data']['dbg']='S01';	
		$response['data']['lvl']=$lvl;
		$response['data']['cell']=get_position_no_direction($current_coordinate);
		$response['data']['dir']=$expected_direction;		
        return $response;
//end iDP 2021		
    }
    else {
        Logger::error("Invalid callback data: {$callback_data}");
//iDP 2021
        //telegram_send_message($chat_id, __("Invalid code."));
		$response['status']='fail';
		$response['chat_id']=$chat_id;
		$response['direction']="";	
		$response['data']['msg1']="Invalid callback";
		$response['data']['msg2']="---";
		$response['data']['state']="f";
		$response['data']['cmd']="";
		$response['data']['dbg']='ECB';	
		$response['data']['lvl']=$lvl;
		$response['data']['cell']="";
		$response['data']['dir']="";		
		return $response;
//end iDP 2021		
    }
}

function name_message_processing($chat_id, $callback_data,$email,$time) {
    global $memory;
	$data = substr($callback_data, 5);
//    die("Name:".$chat_id." ".$callback_data." ".$data." ".$email);
    if ($data === "error"){
        // Request name again
        $memory->nameRequested = true;
//iDP 2021		
		$response['status']='fail';
		$response['chat_id']=$chat_id;
		$response['direction']="";	
		$response['data']['msg1']="Error:";
		$response['data']['msg2']="Write your full name again, please:";
		$response['data']['state']="f";
		$response['data']['cmd']="";
		$response['data']['dbg']='ECB';	
		$response['data']['lvl']=$lvl;
		$response['data']['cell']="";
		$response['data']['dir']="";		
//        telegram_send_message($chat_id, __("Write your full name again, please:"));
//end iDP 2021		
    }
    else {
        unset($memory->nameRequested);
//iDP 2021		
//        send_pdf($chat_id, $data);
		db_perform_action("UPDATE `user_status` SET `completed_on` = NOW(), name = '" . db_escape($data) . "' WHERE `telegram_id` = {$chat_id}");
		$guid = GUID();
		$date = date("j F Y");
        db_perform_action("UPDATE `user_status` SET `certificate_id` = '" . db_escape($guid) . "' WHERE `telegram_id` = {$chat_id}");		
//        $result = db_perform_action("INSERT INTO `certificates_list` (`certificate_id`, `telegram_id`, `name`, `date`) VALUES ('" . db_escape($guid) . "', {$chat_id}, '" . db_escape($data) . "', NOW())");
        $result = db_perform_action("INSERT INTO `certificates_list` (`certificate_id`, `telegram_id`, `name`, `date`, `email`, `time`) VALUES ('" . db_escape($guid) . "', {$chat_id}, '" . db_escape($data) . "', NOW(),'".$email."',".$time.")");
        $short_guid = substr($guid, 0, 18);
		$ndate=date("Y-m-d H:i:s");
		$msg="Completion certificate. Code: ".$short_guid;
        if($result !== false) {
            Logger::info("Generated and sent certificate {$guid}", __FILE__, $chat_id);
			if(!is_null($email)){
				$ris=sendMail($guid,$date,$data,$email,$time);
			} 
			else {
				$ris=0;	
			}	
            $msg.="Thanks for playing!";
			$response['status']='ok';
			$response['chat_id']=$chat_id;
			$response['direction']="";	
			$response['data']['msg1']="Thanks for playing!";
			$response['data']['msg2']="Completion certificate. Code: ".$short_guid;
			$response['data']['state']="f";
			$response['data']['cmd']="";
			$response['data']['dbg']='COK';	
			$response['data']['lvl']='0';
			$response['data']['cell']="";
			$response['data']['dir']="";		
			$response['data']['code']=$short_guid;		
			$response['data']['date']=$ndate;		
			$response['data']['name']=$data;		
			$response['data']['email']=$email;		
			$response['data']['time']=$time;		
			$response['data']['sent']=$ris;		
        }
		else {
			Logger::error("Failed to generate certificate", __FILE__, $chat_id);
			$msg="Failed to generate certificate";
			$response['status']='fail';
			$response['chat_id']=$chat_id;
			$response['direction']="";	
			$response['data']['msg1']="Thanks for playing!";
			$response['data']['msg2']="Failed to generate certificate";
			$response['data']['state']="f";
			$response['data']['cmd']="";
			$response['data']['dbg']='CKO';	
			$response['data']['lvl']='0';
			$response['data']['cell']="";
			$response['data']['dir']="";		
			$response['data']['code']='---';		
			$response['data']['date']='---';		
			$response['data']['name']='---';		
		}
    }
	return $response;
//end iDP 2021
}

function localization_message_processing($chat_id, $callback_data) {
    $lang_code = substr($callback_data, mb_strlen('language '));
    localization_switch_and_persist_locale($chat_id, $lang_code);
//iDP 2021
//    telegram_send_message($chat_id, __("Switched language."));
//end iDP 2021
}
