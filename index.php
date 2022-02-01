<?php
define("ACCESS", true);

function error_code_503(){
    header('HTTP/1.1 503 Service Temporarily Unavailable');
    exit;
}

if (!isset($_GET["AA"]))
    error_code_503();

$token = ("./.token/".sha1($_GET['AA']).".json");

if(!file_exists($token))
    error_code_503();

$perms = fileperms($token);

if((fileperms("./.token/") & 0x0002) or (fileperms($token) & 0x0002)){
    echo "check token permision";
    exit;
}

$token_data = file_get_contents($token);

if($token_data === false)
    error_code_503();

$json = json_decode($token_data, true);

define('bot_id', $_GET["AA"]);
define('BOT_TOKEN', $json[0]);
define('__root__', '.usr/'.crc32(bot_id));

include_once "core/init.php";
include_once __root__."/default.php";