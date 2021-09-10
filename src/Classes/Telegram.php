<?php

namespace TelegramBot\Classes;

class Telegram {

    CONST BASE_BOT_URL = "https://api.telegram.org/bot";
    CONST NOT_FOUND = "دستور مورد نظر پیدا نشد";
    CONST SELECT_ONE_ITEM = "لطفا یک دستور را انتخاب کنید";
    private $botToken;
    private $botUrl;
    private $content = null;
    private $message = null;
    private $menu    = [];

    public function __construct($botToken)
    {
        $this->botToken = $botToken;
        $this->botUrl = self::BASE_BOT_URL . $botToken . "/";
    }

    public function addMenu($menu)
    {
        $this->menu = $menu;
    }

    public function setAnswer()
    {
        $inputMessage = $this->getMessage();

        $myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
        fwrite($myfile, json_encode($inputMessage));
        fclose($myfile);

        $searchResult = $this->searchInMenu($inputMessage['text'], $this->menu);
        if ($searchResult == null) {
            $this->setMessage($inputMessage['chatID'], self::NOT_FOUND);
        } elseif (is_array($searchResult)) {
            $array = [];
            foreach ($searchResult as $key => $value) {
                $array[] = array("text" => $key);
            }
            $this->setMessage($inputMessage['chatID'], self::SELECT_ONE_ITEM)->addKeyboard(array(
                $array,
            ));
        } else {
            $this->setMessage($inputMessage['chatID'], $searchResult);
        }
    }

    public function searchInMenu($input, $menu)
    {
        foreach ($menu as $key => $value) {
            if ($key == $input) {
                return $value;
            }
            if (is_array($value)) {
                $searchInMenuResult = $this->searchInMenu($input, $value);
                if ($searchInMenuResult != null) {
                    return $searchInMenuResult;
                }
            }
        }

        return null;
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

    public function addInlineKeyboard($keyboard)
    {
        $inlineKeyboard = array(
            "inline_keyboard" => $keyboard
        );

        $this->message["reply_markup"] = json_encode($inlineKeyboard);

        return $this;
    }

    public function addKeyboard($keyboard)
    {
        $inlineKeyboard = array(
            "keyboard" => $keyboard
        );

        $this->message["reply_markup"] = json_encode($inlineKeyboard);

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


