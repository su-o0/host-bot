<?php
if(!defined("ACCESS")) { error_code_503();}

define('API_URL', 'https://api.telegram.org/bot' . BOT_TOKEN . '/');

include_once "core/config.php";
include_once "core/apiRequest/main.php";
include_once "core/Logger/main.php";


function bot_command($command){
    $path = __root__."command/bot/";
    
    $filename = $path.$command.'.php';
    if(file_exists($filename))
        require_once $filename; 
    else 
        return false;
}
function custom_command($command){
    $path = __root__."command/custom/";

    $filename = $path.$command.'.php';
    if(file_exists($filename))
        require_once $filename; 
    else 
        return false;
}

function callback_command($command){
    $path = __root__."command/callback/";

    $filename = $path.$command.'.php';
    if(file_exists($filename))
        require_once $filename; 
    else 
        return false;
}

function explode_callback_command($command){
    $path = __root__."command/explode_callback/";

    $filename = $path.$command.'.php';
    if(file_exists($filename))
        require_once $filename; 
    else 
        return false;
}
