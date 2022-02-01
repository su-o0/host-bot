<?php
if(!defined("ACCESS")) { error_code_503();}

include "sqlite.php";

if ($method == "message") {
    if ($type != "text") {

        // apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => "Ремонтые работы..."));
        exit;
    }
    else if ($is_cmd) {
        $command;
        exit;
    } 
    else if ($is_custom_cmd) {
        switch ($command) {
            default:
                exit;
            break;
        }
    }
} 
else if ($method == "callback") {
    if(!$is_callback_data_explode){
        switch ($callback_data) {
            default:
                exit;
            break;
        }
    }
    else {
        switch ($explode_callback_data[0]) {
            default:
                exit;
            break;
        }
    }

}