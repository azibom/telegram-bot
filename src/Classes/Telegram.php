<?php

namespace TelegramBot\Classes;

class Telegram {

    CONST BASE_BOT_URL = "https://api.telegram.org/bot";
    private $botToken;
    private $botUrl;
    private $content = null;
    private $message = null;

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

    public function setMessage($chatID, $text)
    {
        $this->message = array(
            "chat_id" => $chatID,
            "text" => $text,
        );

        return $this;
    }

    public function addInlineKeyboard($keyboardddd)
    {
        // $this->message["reply_markup"] = json_encode([
        //     ["inline_keyboard" => $keyboard]
        // ]);

        $keyboard = array(
    "inline_keyboard" => $keyboardddd
);

$this->message["reply_markup"] = json_encode($keyboard);

        return $this;
    }

    public function sendMessage()
    {
        $string = $this->botUrl."sendMessage?".http_build_query($this->message);


        $myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
        fwrite($myfile, $string);
        fclose($myfile);

        file_get_contents($string);
    }
}


