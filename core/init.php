<?php
if(!defined("ACCESS")) { error_code_503();}

define('API_URL', 'https://api.telegram.org/bot' . BOT_TOKEN . '/');

$content = file_get_contents("php://input");
$update = json_decode($content, true);

$update_id = $update['update_id'];

if(isset($update["message"]))
{
    $method = "message";
    $message = $update["message"];

    $update_id =  $update["update_id"];
    $message_id = $message['message_id'];

    $is_custom_cmd = false;
    /* message type  */
    if(isset($message['voice']))
        $type = 'voice';
    else if(isset($message['video_note']))
        $type = 'video_note'; 
    else if(isset($message['photo']))
        $type = 'photo';
    else if(isset($message['video']))
        $type = 'video';
    // else if(isset($message['document']))
    //     $type = 'document';
    else if(isset($message['location']))
        $type = 'location';
    else if(isset($message['sticker']))
        $type = 'sticker';
    else if(isset($message['animation']))
    {
        $type = 'animation';
        $thumb_file_id = $message['animation']['thumb']['file_id'];
        $thumb_file_unique_id = $message['animation']['thumb']['file_id'];
        $file_id = $message['animation']['file_id'];
        $file_unique_id = $message['animation']['file_unique_id'];
    }
    else if(isset($message['contact']))
    {
        $type = 'contact';
        $contact = $message['contact'];
    }
    else if(isset($message['text']))
    {
        $type = 'text';
        $text = $message['text'];

        if($text[0] == '!')
        {
            $is_custom_cmd = true;    
            $command = explode(' ', $text)[0];
        }
    }

    /* date */
    $date = $message['date'];

    /* from */
    $from = $message['from'];
    $id = $from['id'];
    $first_name =  $from['first_name'];
    if(isset($from['language_code']))
        $language_code =  $from['language_code'];

    /* chat*/
    $chat = $message['chat'];
    $chat_id = $chat['id'];

    /* command */
    $is_cmd = false;
    if(isset($message["entities"]))
    {
        $entities = $message["entities"];
        if($entities[0]['offset'] == 0)
            if($entities[0]['type'] == 'bot_command')
            {
                $is_cmd = true;
                $command = substr($text, 0, $entities[0]['length']);

                $cmd_parametrs = substr($text, $entities[0]['length']);
            }
    }
    $is_reply_message = false;
    if(isset($message["reply_to_message"]))
    {
        $is_reply_message = true;
        $reply_message = $message["reply_to_message"];
        $reply_message_id = $reply_message['message_id'];
        if(isset($reply_message['voice']))
            $reply_type = 'voice';
        else if(isset($reply_message['video_note']))
            $reply_type = 'video_note'; 
        else if(isset($reply_message['photo']))
            $reply_type = 'photo';
        else if(isset($reply_message['video']))
            $reply_type = 'video';
        else if(isset($reply_message['document']))
            $reply_type = 'document';
        else if(isset($reply_message['location']))
            $reply_type = 'location';
        else if(isset($reply_message['sticker']))
            $reply_type = 'sticker';
        else if(isset($reply_message['animation']))
            $reply_type = 'animation';
        else if(isset($reply_message['contact']))
        {
            $reply_type = 'contact';
            $reply_contact = $reply_message['contact'];
        }
        else if(isset($reply_message['text']))
            $reply_type = 'text';
    }
    /*
    'entities' => 
    array (
      0 => 
      array (
        'offset' => 0,
        'length' => 6,
        'type' => 'bot_command',
      ),
    ), */

}
else if(isset($update["callback_query"]))
{
    $method = "callback";
    $update_id = $update["update_id"];
    $callback_query = $update["callback_query"];

    $callback_query_id = $callback_query['id'];
    $from = $callback_query['from'];
    $from_id = $from['id'];
    /* chat id */
    $chat_id = $from['id'];

    $is_bot = $from['is_bot'];
    $first_name = $from['first_name'];
    $language_code = $from['language_code'];

    $callback_query_message = $callback_query['message'];
    $message_id = $callback_query_message['message_id'];    
    $message_from = $callback_query_message['from'];    
    $message_from_id = $message_from['id'];
    $message_from_is_bot = $message_from['is_bot'];
    $message_first_name = $message_from['first_name'];
    // $message_language_code = $message_from['language_code'];

    $message_chat = $callback_query_message['chat'];
    $message_chat_id = $message_chat['id'];
    $message_chat_first_name = $message_chat['first_name'];
    
    $message_date = $callback_query_message['date'];
    $message_text = $callback_query_message['text'];

    $reply_markup = $callback_query_message['reply_markup'];

    $chat_instance = $callback_query["chat_instance"];
    $callback_data = $callback_query["data"];

    $is_callback_data_explode = false;
    $explode = explode(' ', $callback_query["data"]);
    if(count($explode) > 1)
    {
        $is_callback_data_explode = true;
        $explode_callback_data = $explode;
    }
}
else if(isset($update["my_chat_member"]))
{
    $method = "my_chat_member";

    $update_id =  $update["update_id"];

    $chat = $update["my_chat_member"]['chat'];

    $chat_id = $chat['id'];
    $chat_first_name = $chat['first_name'];
    $type = $chat['type'];

    $from = $update["my_chat_member"]['from'];
    
    $from_id = $chat['id'];
    $from_first_name = $chat['first_name'];
    $language_code = $chat['language_code'];


    $old_chat_member = $update["my_chat_member"]['old_chat_member'];
    $new_chat_member = $update["my_chat_member"]['new_chat_member'];

    $bot_id = explode(':', BOT_TOKEN)[0];
    $is_my_old_chat_member = ($bot_id == $old_chat_member['user']['id'])?1:0;
    $is_my_new_chat_member = ($bot_id == $new_chat_member['user']['id'])?1:0;

    $is_stop_and_block_bot = false;
    $is_start_alter_stop_and_block_bot = false;

    if($is_my_old_chat_member and $is_my_new_chat_member)
    {
        if($new_chat_member['status'] == 'kicked' and $old_chat_member['status'] == 'member') 
        {
            $is_stop_and_block_bot = true;
        }
    }
    else if($is_my_new_chat_member)
    {
        if($new_chat_member['status'] == 'member' and $old_chat_member['status'] = 'kicked') 
        {
            $is_start_alter_stop_and_block_bot = true;
        }
    }
}

$update_id = $update['update_id'];
    

include 'core/function.php';