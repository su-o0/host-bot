<?php
if(!defined("ACCESS")) { error_code_503();}


class message {
    public function explode_message($message){
        $this->message_id = $message['message_id'];

        $this->is_custom_cmd = false;
        /* message type  */
        if(isset($message['voice']))
            $this->type = 'voice';
        else if(isset($message['video_note']))
            $this->type = 'video_note';   
        else if(isset($message['photo']))
            $this->type = 'photo';
        else if(isset($message['video']))
            $this->type = 'video';
        // else if(isset($message['document']))
        //     $type = 'document';
        else if(isset($message['location']))
            $this->type = 'location';
        else if(isset($message['sticker']))
            $this->type = 'sticker';
        else if(isset($message['animation'])) {
            $this->type = 'animation';
            $this->thumb_file_id = $message['animation']['thumb']['file_id'];
            $this->thumb_file_unique_id = $message['animation']['thumb']['file_id'];
            $this->file_id = $message['animation']['file_id'];
            $this->file_unique_id = $message['animation']['file_unique_id'];
        }
        else if(isset($message['contact'])) {
            $this->type = 'contact';
            $this->contact = $message['contact'];
        }
        else if(isset($message['text'])) {
            $this->type = 'text';
            $this->text = $message['text'];

            if($this->text[0] == '!') {
                $this->is_custom_cmd = true;    
                $this->command = explode(' ', $this->text)[0];
            }
        }

        /* date */
        $this->date = $message['date'];

        /* from */
        $this->from = $message['from'];
        $this->id = $this->from['id'];
        $this->first_name = $this->from['first_name'];
        if(isset($this->from['language_code']))
            $this->language_code =  $this->from['language_code'];

        /* chat*/
        $this->chat = $message['chat'];
        $this->chat_id = $this->chat['id'];

        /* command */
        $this->is_cmd = false;
        if(isset($message["entities"]))
        {
            $entities = $message["entities"];
            if($entities[0]['offset'] == 0)
                if($entities[0]['type'] == 'bot_command')
                {
                    $this->is_cmd = true;
                    $this->command = substr($this->text, 1, $entities[0]['length']);

                    $this->cmd_parametrs = substr($this->text, $entities[0]['length']);
                }
        }
        $this->is_reply_message = false;
        if(isset($message["reply_to_message"]))
        {
            $this->is_reply_message = true;
            $reply_message = $this->message["reply_to_message"];
            $this->reply_message_id = $reply_message['message_id'];
            if(isset($reply_message['voice']))
                $this->reply_type = 'voice';
            else if(isset($reply_message['video_note']))
                $this->reply_type = 'video_note'; 
            else if(isset($reply_message['photo']))
                $this->reply_type = 'photo';
            else if(isset($reply_message['video']))
                $this->reply_type = 'video';
            else if(isset($reply_message['document']))
                $this->reply_type = 'document';
            else if(isset($reply_message['location']))
                $this->reply_type = 'location';
            else if(isset($reply_message['sticker']))
                $this->reply_type = 'sticker';
            else if(isset($reply_message['animation']))
                $this->reply_type = 'animation';
            else if(isset($reply_message['contact']))
            {
                $this->reply_type = 'contact';
                $this->reply_contact = $reply_message['contact'];
            }
            else if(isset($reply_message['text']))
                $this->reply_type = 'text';
        }
    }
}