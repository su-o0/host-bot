<?php

if(!defined("ACCESS")) { error_code_503();}
include 'core/function.php';

include 'disassembly/message.php';
include 'disassembly/callback.php';

// disassembly into components
class update extends callback  {
    public $method;
    
    public $chat_id;
    public function __construct()
    { 
        $content = file_get_contents("php://input");
        $update = json_decode($content, true);
        $this->update_id = $update['update_id'];

        if(isset($update["message"])){
            $this->method = "message";
            $this->explode_message($update["message"]);
        }
        else if(isset($update["callback_query"])){
            $this->method = "callback";
            $this->explode_callback($update["message"]);
        }
    }
} 

global $bot;
$bot = new update();