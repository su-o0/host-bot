<?php
if(!defined("ACCESS")) { error_code_503();}

apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => "Ремонтые работы...start"));
