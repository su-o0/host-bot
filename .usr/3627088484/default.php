<?php
if(!defined("ACCESS")) { error_code_503();}

include "sqlite.php";

if ($method == "message") {
    if ($type != "text") {
        apiRequestJson('sendMessage', array('chat_id' => $chat_id, 'text' => "I understand only text messages"));
    }
    else if ($is_cmd) {
        bot_command($command);
    } 
    else if ($is_custom_cmd) {
        custom_command($command);
    }
} 
else if ($method == "callback") {
    if(!$is_callback_data_explode){
        callback_command($callback_data);
    }
    else {
        explode_callback_command($explode_callback_data[0]);
    }
}

exit;