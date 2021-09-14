<?php

namespace TelegramBot\Classes;

class Telegram {

    CONST BASE_BOT_URL = "https://api.telegram.org/bot";
    CONST NOT_FOUND = "دستور مورد نظر پیدا نشد";
    CONST SELECT_ONE_ITEM = "لطفا یک دستور را انتخاب کنید";
    CONST HOME = "منو اصلی";
    
    private $botToken;
    private $botUrl;
    private $content = null;
    private $message = null;
    private $menu    = [];
    private $repo;
    private $user;

    public function __construct($botToken, $repo)
    {
        $this->botToken = $botToken;
        $this->repo = $repo;
        $this->botUrl = self::BASE_BOT_URL . $botToken . "/";
    }

    public function addMenu($menu)
    {
        $this->menu = $menu;
    }

    public function setAnswer()
    {
        $inputMessage = $this->getMessage();

        if ($inputMessage['text'] == self::HOME) {
            $array = [];
            foreach ($this->menu as $key => $value) {
                $array[] = array(array("text" => $key));
            }

            $this->setMessage($inputMessage['chatID'], self::SELECT_ONE_ITEM)->addKeyboard($array);
        } else {
            $searchResult = $this->searchInMenu($inputMessage['text'], $this->menu);
            if ($searchResult == null) {
                $array = [];
                foreach ($this->menu as $key => $value) {
                    $array[] = array(array("text" => $key));
                }
    
                $this->setMessage($inputMessage['chatID'], self::NOT_FOUND)->addKeyboard(
                    $array
                );
            } elseif (is_array($searchResult)) {
                $array = [];
                foreach ($searchResult as $key => $value) {
                    $array[] = array(array("text" => $key));
                }
                $array[] = array(array("text" => self::HOME));
    
    
                $this->setMessage($inputMessage['chatID'], self::SELECT_ONE_ITEM)->addKeyboard($array);
            } else {
                $this->setMessage($inputMessage['chatID'], $searchResult);
            }
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
        

        $myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
        fwrite($myfile, "______________________________" . PHP_EOL);
        fwrite($myfile, $contents);
        fwrite($myfile, "______________________________" . PHP_EOL);
        fclose($myfile);

        return $this->content;
    }

    public function getMessage()
    {
        $chatID = null;
        $text   = null;

        if ($this->content != null && isset($this->content["message"])) {
            $message = $this->content["message"];
            
            $chatID = $message["chat"]["id"];
            $name = $message["chat"]["first_name"];
            $text   = $message["text"];
            try {
                $this->user = $this->repo->getUser($name, $chatID, $text);

            } catch (\Throwable $th) {

                $myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
                fwrite($myfile, "tttttttttttttttttttttt".$th->getMessage() . PHP_EOL);
                fclose($myfile);
            }
        }

        if ($text == "/start") {
            
        }

        return [
            "chatID" => $chatID, 
            "name" => $chatID, 
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
        fwrite($myfile, 'we are here 3' . PHP_EOL);
        fwrite($myfile, json_encode($this->message) . PHP_EOL);
        fclose($myfile);


        $myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
        fwrite($myfile, 'we are here 4' . PHP_EOL);
        fwrite($myfile, json_encode($string) . PHP_EOL);
        fclose($myfile);

        file_get_contents($string);
    }
}


