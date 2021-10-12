<?php
require_once(dirname(__FILE__) . '/data.php');
require_once(dirname(__FILE__) . '/lib_localization.php');
require_once(dirname(__FILE__) . '/msg_processing_simple.php');

function message_msg_processing($message) {
    global $memory;
//iDP 2021
	$response=array();
//fine iDP 2021
    Logger::debug("Processing text message", __FILE__);

    $chat_id = $message['chat']['id'];
    if(isset($message['from']['language_code'])) {
        $chat_language = $message['from']['language_code'];
    }

    memory_load_for_user($chat_id);
    localization_load_user($chat_id, $chat_language);

    if (isset($message['text'])) {
        // We got an incoming text message
        $text = $message['text'];
        // Get user info to see if he has reached end of game
        $user_info = db_row_query("SELECT * FROM user_status WHERE telegram_id = $chat_id LIMIT 1");
//iDP 2021
		if(is_null($user_info)){
			$user_info[USER_STATUS_COMPLETED]=0;	
		}			
//end iDP 2021		
        if ($text === "/reset"){
//iDP 2021		
//direct answer to client
            reset_game($chat_id);
			$response['status']='ok';
			$response['data']['msg']='Your progress has been reset.\nWrite /start to start anew or scan in a QR Code.';
			$response['data']['chat_id']=$chat_id;
		    //echo "Your progress has been reset.\nWrite /start to start anew or scan in a QR Code."."<br>";	
			//telegram_send_message($chat_id, __("Your progress has been reset.\nWrite /start to start anew or scan in a QR Code."));
//end iDP 2021
        }
        else if(strpos($text, "/debug") === 0) {
            // Debugging commands received
//iDP 2021		
//direct answer to client
			$response['status']='ok';
			$response['data']['msg']='TODO:debug';
			$response['data']['chat_id']=$chat_id;
 		    //echo "Received debug command..."."<br>";	
            //telegram_send_message($chat_id, "Received debug command...");
            //debug_message_processing($chat_id, $text);
//end iDP 2021
        }
        else if($text === '/setlanguage') {
            // Prep language keyboard
            $lang_keyboard = array();
            $i = 0;
            foreach(LANGUAGE_NAME_MAP as $code => $lang) {
                if($i % 3 == 0) {
                    $lang_keyboard[] = array();
                }
                $lang_keyboard[sizeof($lang_keyboard) - 1][] = array(
                    'text' => $lang,
                    'callback_data' => 'language ' . $code
                );

                $i++;
            }
//iDP 2021		
//direct answer to client
			$response['status']='ok';
			$response['data']['msg']='TODO:cambio lingua';
			$response['data']['chat_id']=$chat_id;
            //echo "Which language do you speak?"."<br>";
/*		  
            $response = telegram_send_message($chat_id, __("Which language do you speak?"), array(
                'reply_markup' => array(
                    'inline_keyboard' => $lang_keyboard
                )
            ));

            set_new_callback_keyboard($response);
*/			
//end iDP 2021			
        }
        else if($text === '/help') {
            $move_count = db_scalar_query("SELECT count(*) FROM `moves` WHERE `telegram_id` = {$chat_id}");

            $txt = __("I am the <b>CodyMaze bot</b> and I will guide you through the game.") . " 🤖 " . __("The game is composed of <b>13 challenges</b>: for each one, I will send you new instructions that you must follow exactly in order to reach the final destination on the game’s chessboard.");
            if($move_count == 0) {
                $txt .= "\n\n" . __("In order to start playing, please scan the QR Code on one of the outer squares of the chessboard (that is, any square along the first or last row, or the first or last column). You may use any QR Code scanner application to do so.");
            }
            $txt .= "\n\n" . __("The instructions I will send you may contain the following commands:\n<code>f</code>: move forward,\n<code>l</code>: turn left,\n<code>r</code>: turn right,\nand other commands such as <code>if</code>, <code>else</code>, and <code>while</code>. Code blocks are indicated in <code>{}</code> and can be repeated. For instance, <code>2{fr}</code> tells you to move forward and turn right twice.");
            $txt .= "\n\n" . sprintf(__("For further help, check out the <a href=\"%s\">official CodyMaze website</a>."), "https://github.com/CodeMOOC/CodyMazeBot");
//iDP 2021		
//direct answer to client
			$response['status']='ok';
			$response['data']['msg']='TODO:help';
			$response['data']['chat_id']=$chat_id;
			//echo $txt."<br>";	
/*		  
            telegram_send_message(
                $chat_id,
                $txt,
                array("parse_mode" => "HTML")
            );
*/			
//end iDP 2021	
        }
        else if($text === "/send_my_certificates") {
//iDP 2021		
//direct answer to client
			//$response['status']='ok';
			//$response['data']['msg']='TODO:certificati non disponibili';
			//$response['data']['chat_id']=$chat_id;
			//echo "Sorry, feature under development. Come back later."."<br>";	
/*
            telegram_send_message(
                $chat_id,
                __("Sorry, feature under development. Come back later.")
            );
*/						
            //return $response;
//end iDP 2021
            $result = db_table_query("SELECT * FROM `certificates_list` WHERE telegram_id = '{$chat_id}' ORDER BY date ASC");
			$ncert=count($result);
			if($result !== null && $result !== false && count($result)>0){
				$i=0;
				$response['status']='ok';
				$response['data']['msg']='Completion certificate.';
				$response['data']['chat_id']=$chat_id;
				$response['data']['ncert']=$ncert;
				foreach ($result as $item) {
					$response['data'][$i]['cert_id']=$item[0];
					$response['data'][$i]['chat_id']=$item[1];
					$response['data'][$i]['name']=$item[2];
					$response['data'][$i]['date']=$item[3];	
					$response['data'][$i]['email']=$item[4];	
					$response['data'][$i]['time']=$item[5];	
					$i++;
                    //$pdf_path = "certificates/" . $item[0] . ".png";
                    //if(!file_exists($pdf_path)) {
                    //    Logger::warning("Certificate file '{$pdf_path}' does not exist, trying PDF", __FILE__);
                    //    $pdf_path = "certificates/" . $item[0] . ".pdf";
                    //    if(!file_exists($pdf_path)) {
                    //        Logger::error("Certificate file '{$pdf_path}' does not exist, ignoring", __FILE__);
                    //        continue;
                    //    }
                }
//                    $short_guid = substr($item[0], 0, 18);
//iDP 2021		
//direct answer to client
					//echo "Completion certificate. Code: %s."."<br>";	
/*					
                    $result = telegram_send_document(
                        $chat_id,
                        $pdf_path,
                        sprintf(__("Completion certificate. Code: %s."), $short_guid)
                    );
*/					
//end iDP 2021		
            }
            //}
            else {
//iDP 2021		
//direct answer to client
				$response['status']='ok';
				$response['data']['msg']="You've never completed CodyMaze yet, I have no certificate to send you.";
				$response['data']['chat_id']=$chat_id;
				//echo "You’ve never completed CodyMaze yet, I have no certificate to send you."."<br>";	
/*                
				telegram_send_message(
                    $chat_id,
                    __("You’ve never completed CodyMaze yet, I have no certificate to send you.") . ' 😔'
                );
*/				
//end iDP 2021	
            }
        }
        else if($user_info[USER_STATUS_COMPLETED] == 1) {
				// Game is completed
				if (isset($memory->nameRequested)) {
					// User is writing name for certificate
					$response['status']='ok';
					$response['data']['msg']='TODO:richiesta nome';
					$response['data']['chat_id']=$chat_id;
					//request_name($chat_id, $text);
				}
				else {
//iDP 2021		
//direct answer to client
					$response['status']='ok';
					$response['data']['msg']='You completed CodyMaze!\nIf you want to play again, please send the /reset command.';
					$response['data']['chat_id']=$chat_id;
					//echo "You completed CodyMaze!\nIf you want to play again, please send the /reset command."."<br>";	
					//telegram_send_message($chat_id, __("You completed CodyMaze!\nIf you want to play again, please send the /reset command."));
//end iDP 2021				
				}
        }
        else {
            // Game is not completed
            if (strpos($text, "/start") === 0) {
				//$response['status']='ok';
				//$response['data']='TODO: perform command start';
				//echo "TODO: Perform command start:".$text."<br>";	
                $response=perform_command_start($chat_id, mb_strtolower($text));
            }
            else {
                // User is probably writing something instead of playing
//iDP 2021		
//direct answer to client
				$response['status']='fail';
				$response['data']['msg']='TODO:comando non compreso scan QR';
				$response['data']['chat_id']=$chat_id;
//echo "I don’t understand this kind of message!"."<br>";	
/*				
                telegram_send_message(
                    $chat_id,
                    __("Didn’t get that. Please scan one of the QR Codes in the maze.")
                );
*/
//end iDP 2021					
            }
        }
    }
    else {
//iDP 2021		
//direct answer to client
			$response['status']='fail';
			$response['data']['msg']='TODO:comando non compreso';
			$response['data']['chat_id']=$chat_id;
			//echo "I don’t understand this kind of message!"."<br>";	
//        telegram_send_message($chat_id, __("I don’t understand this kind of message!"));
//end iDP 2021		
    }
    memory_persist($chat_id);
	return $response;
}