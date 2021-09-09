<?php

namespace TelegramBot\Classes;

class Telegram {

    CONST BASE_BOT_URL = "https://api.telegram.org/bot";
    private $botToken;
    private $botUrl;
    private $content = null;

    public function __construct($botToken)
    {
        $this->botToken = $botToken;
        $this->botUrl = self::BASE_BOT_URL . $botToken . "/";
    }

    public function getUpdate()
    {
        $contents = file_get_contents("php://input");
        $this->content = json_decode($contents, true);
        
        return $this->content;
    }

    public function getMessage()
    {
        $chatID = null;
        $text   = null;

        if ($this->content != null && isset($this->content["message"])) {
            $message = $this->content["message"];
            
            $chatID = $message["chat"]["id"];
            $text   = $message["text"];
        }

        return [
            "chatID" => $chatID, 
            "text" => $text, 
        ];
    }

    public function sendMessage($chatID, $text)
    {
        $args = array(
            "chat_id" => $chatID,
            "text" => $text,
        );

        file_get_contents($this->botUrl."sendMessage?".http_build_query($args));
    }
}


