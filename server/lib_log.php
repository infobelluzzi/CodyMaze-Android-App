<?php
/**
 * Telegram Bot Sample
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Support library. Don't change a thing here.
 */

require_once(dirname(__FILE__) . '/lib_database.php');
require_once(dirname(__FILE__) . '/lib_utility.php');

class Logger {

    const SEVERITY_DEBUG = 1;
    const SEVERITY_INFO = 64;
    const SEVERITY_WARNING = 128;
    const SEVERITY_ERROR = 255;

    public static function debug($message, $tag = '', $telegram_id = null) {
        self::common(self::SEVERITY_DEBUG, $message, $tag, $telegram_id);
    }

    public static function info($message, $tag = '', $telegram_id = null) {
        self::common(self::SEVERITY_INFO, $message, $tag, $telegram_id);
    }

    public static function warning($message, $tag = '', $telegram_id = null) {
        self::common(self::SEVERITY_WARNING, $message, $tag, $telegram_id);
    }

    public static function error($message, $tag = '', $telegram_id = null) {
        self::common(self::SEVERITY_ERROR, $message, $tag, $telegram_id);
    }

    public static function fatal($message, $tag = '', $telegram_id = null) {
        self::error($message, $tag, $telegram_id);

        die();
    }

    private static function severity_to_char($level) {
        if($level >= self::SEVERITY_ERROR)
            return 'E';
        else if($level >= self::SEVERITY_WARNING)
            return 'W';
        else if($level >= self::SEVERITY_INFO)
            return 'I';
        else
            return 'D';
    }

    private static function common($level, $message, $tag = '', $telegram_id = null) {
        if(is_cli()) {
            // In CLI mode, output all logs to stderr
            fwrite(STDERR, self::severity_to_char($level) . '/' . $message . PHP_EOL);
        }
        else {
            $base_tag = basename($tag, '.php');

//            if($level >= self::SEVERITY_WARNING) {
            if($level >= self::SEVERITY_DEBUG) {
                // Write warnings and errors to the system log
                error_log(self::severity_to_char($level) . ':' . $tag . ':' . $message);
				if(is_null($telegram_id)){
					$q="INSERT INTO `log` (`severity`,`tag`,`message`,`timestamp`,`telegram_id`) VALUES({$level},'{$base_tag}','{$message}',NOW(),NULL)";					
                }
				else {
					$q="INSERT INTO `log` (`severity`,`tag`,`message`,`timestamp`,`telegram_id`) VALUES({$level},'{$base_tag}','{$message}',NOW(),{$telegram_id})";					
				}
             	$insert_result = db_perform_action($q);	
            }
        }
    }
}
