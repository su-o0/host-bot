<?php
if(!defined("ACCESS")) { error_code_503();}


class callback extends message  {
    public function explode_callback($callback_query){
        // $update_id = $update["update_id"];
        // $callback_query = $update["callback_query"];
    
        $this->callback_id = $callback_query['id'];
        $from = $callback_query['from'];
        $this->from_id = $from['id'];
        /* chat id */
        $this->chat_id = $from['id'];
    
        $this->is_bot = $from['is_bot'];
        $this->first_name = $from['first_name'];
        $this->language_code = $from['language_code'];
    
        $callback_message = $callback_query['message'];
        $this->message_id = $callback_message['message_id'];    

        $message_from = $callback_message['from'];    
        $this->message_from_id = $message_from['id'];
        $this->message_from_is_bot = $message_from['is_bot'];
        $this->message_first_name = $message_from['first_name'];
        // $message_language_code = $message_from['language_code'];
    
        $message_chat = $callback_message['chat'];
        $this->message_chat_id = $message_chat['id'];
        $this->message_chat_first_name = $message_chat['first_name'];
        
        $this->message_date = $callback_message['date'];
        $this->message_text = $callback_message['text'];
    
        $this->reply_markup = $callback_message['reply_markup'];
    
        $this->chat_instance = $callback_query["chat_instance"];
        $this->callback_data = $callback_query["data"];
    
        $this->is_callback_data_explode = false;
        $explode = explode(' ', $this->callback_query["data"]);
        if(count($explode) > 1)
        {
            $this->is_callback_data_explode = true;
            $this->explode_callback_data = $explode;
        }
    }
}
