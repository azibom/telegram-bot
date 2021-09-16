<?php

namespace TelegramBot\Classes;
require_once(__DIR__ . '/../../logger.php');

class Telegram {

    CONST BASE_BOT_URL = "https://api.telegram.org/bot";
    CONST NOT_FOUND = "دستور مورد نظر پیدا نشد";
    CONST SELECT_ONE_ITEM = "لطفا یک دستور را انتخاب کنید";
    CONST HOME = "منو اصلی";
    CONST BACK = "بازگشت";

    CONST ADD_MENU = "اضافه کردن منو";
    CONST ADD_SUB_MENU = "اضافه کردن زیر منو";

    CONST DELETE_MENU = "حذف منو";
    CONST DELETE_SUB_MENU = "حذف زیر منو";

    CONST ADD_CONTENT = "اضافه کردن محتوا";
    
    private $botToken;
    private $botUrl;
    private $content = null;
    private $message = [];
    private $keyboard = null;
    private $menu    = [];
    private $repo;
    private $user;
    private $userBack = null;
    private $logger = null;
    private $adminKeyboard = null;

    public function __construct($botToken, $repo)
    {
        $this->botToken = $botToken;
        $this->repo = $repo;
        $this->botUrl = self::BASE_BOT_URL . $botToken . "/";

        $this->logger = getLogger();
        $this->admin = new TelegramAdmin("1234");
    }

    public function message($message)
    {
        return json_encode($message);
    }

    public function addMenu($menu)
    {
        try {
            $DB = $this->repo->getConfigByKey("database");
            if ($DB) {
                $this->menu = json_decode($DB->getValue(), true);
            } else {
                $this->repo->addNewConfig("database", json_encode($menu));
                $this->menu = $menu;
            }
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            $this->logger->error($th->getTraceAsString());
        }
    }

    public function ifSpecialInput($input)
    {
        return in_array($input, [self::BACK, self::HOME]);
    }

    public function keyboardGenerator($menu, $extraButton = null)
    {
        $array = [];
        foreach ($menu as $key => $value) {
            $array[] = array(array("text" => $key));
        }

        if ($extraButton) {
            $array[] = $extraButton;
        }

        return $array;
    }

    public function generateDefaultKeyboard($chatID, $message = self::SELECT_ONE_ITEM)
    {
        $keyboard = $this->keyboardGenerator($this->menu);
        $this->setMessage($chatID, $message)->addKeyboard($keyboard);
    }

    public function specialInputHandler($input, $chatID)
    {
        if ($input == self::HOME) {
            $this->generateDefaultKeyboard($chatID);
        } elseif ($input == self::BACK) {
            $searchResultd = $this->searchInMenuFindParent($this->userBack, $this->menu);
            if ($searchResultd == null) {
                $this->generateDefaultKeyboard($chatID);
            } else {
                $searchResult = $this->searchInMenu($searchResultd, $this->menu);
                if ($searchResult == null) {
                    $this->generateDefaultKeyboard($chatID, self::NOT_FOUND);
                } elseif (is_array($searchResult)) {
                    $keyboard = $this->keyboardGenerator($searchResult, array(array("text" => self::BACK), array("text" => self::HOME)));
                    $this->setMessage($chatID, self::SELECT_ONE_ITEM)->addKeyboard($keyboard);
                } else {
                    $this->setMessage($chatID, $searchResult);
                }
            }
        }
    }

    public function setAnswer()
    {
        try {
            $inputMessage = $this->getMessage();
            $this->getUser($inputMessage['text'], $inputMessage['name'], $inputMessage['chatID']);
            if ($this->admin->checkUserIsAdmin($this->user)) {
                $this->adminKeyboard = [];
                $this->adminKeyboard[] = array(array("text" => self::ADD_MENU), array("text" => self::ADD_SUB_MENU));
                $this->adminKeyboard[] = array(array("text" => self::DELETE_MENU), array("text" => self::DELETE_SUB_MENU));
                $this->adminKeyboard[] = array(array("text" => self::ADD_CONTENT));
            } else {
                $this->logger->info("we are here 1" . $inputMessage['text']);
                if ($this->admin->checkPass($inputMessage['text'])) {
                    $this->logger->info("we are here 2" . $inputMessage['text']);
                    $this->logger->info("we are here 5 " . $this->user->getIsAdmin());
                    $this->user = $this->admin->setAdminFlag($this->user);
                    $this->logger->info("we are here 6 " . $this->user->getIsAdmin());
                }
            }

            if ($this->ifSpecialInput($inputMessage['text'])) {
                $this->specialInputHandler($inputMessage['text'], $inputMessage['chatID']);
            } else {
                $searchResult = $this->searchInMenu($inputMessage['text'], $this->menu);
                if ($searchResult == null) {
                    $this->generateDefaultKeyboard($inputMessage['chatID'], self::NOT_FOUND);
                } elseif (is_array($searchResult)) {
                    $keyboard = $this->keyboardGenerator($searchResult, array(array("text" => self::BACK), array("text" => self::HOME)));
                    $this->setMessage($inputMessage['chatID'], self::SELECT_ONE_ITEM)->addKeyboard($keyboard);
                } else {
                    $this->setMessage($inputMessage['chatID'], $searchResult);
                }
            }
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            $this->logger->error($th->getTraceAsString());
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

    public function searchInMenuFindParent($input, $menu)
    {
        foreach ($menu as $key => $el) {
            if (is_array($el)) {
                foreach ($el as $key2 => $value) {
                    if ($key2 == $input) {
                        return $key;
                    }

                    if (is_array($value)) {
                        $searchInMenuResult = $this->searchInMenuFindParent($input, $value);
                        if ($searchInMenuResult != null) {
                            return $searchInMenuResult;
                        }
                    }
                }
            }
        }

        return null;
    }

    public function getUpdate()
    {
        $contents = file_get_contents("php://input");
        $this->content = json_decode($contents, true);
        $this->logger->info("getUpdate content", $this->content ?? []);

        return $this->content;
    }

    public function getUser($text, $name, $chatID)
    {
        try {
            $searchResult = $this->searchInMenu($text, $this->menu);
            $user = $this->repo->getUserByChatId($chatID);
            if (is_array($searchResult)) {
                if ($user) {
                    $this->userBack = $user->getCurrentMenuName();
                    $user = $this->repo->updateUser($user, $name, $text);
                } else {
                    $user = $this->repo->addNewUser($name, $chatID, $text);
                }
            } else {
                if ($user) {
                    $this->userBack = $user->getCurrentMenuName();
                    $user = $this->repo->updateUser($user, $name, null);
                } else {
                    $user = $this->repo->addNewUser($name, $chatID, null);
                }
            }

            $this->user = $user;
            if ($text == self::BACK) {
                $this->repo->updateUser($this->user, null, $this->searchInMenuFindParent($this->userBack, $this->menu));
            }
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            $this->logger->error($th->getTraceAsString());
        }
    }

    public function getMessage()
    {
        $chatID = null;
        $text   = null;

        if ($this->content != null && isset($this->content["message"])) {
            $message = $this->content["message"];
            $chatID  = $message["chat"]["id"];
            $name    = $message["chat"]["first_name"];
            $text    = $message["text"];
        }

        return [
            "chatID" => $chatID, 
            "name" => $name, 
            "text" => $text, 
        ];
    }

    public function setMessage($chatID, $message)
    {
        $messageInArray = json_decode($message, true);
        
        if (!empty($messageInArray)) {
            foreach ($messageInArray as $value) {
                $type = $value["type"];
                if ($type == "text") {
                    $this->message[] = array(
                        "chat_id" => $chatID,
                        "text" => $value['text'],
                    );
                } elseif ($type == "image") {
                    $this->message[] = array(
                        "chat_id" => $chatID,
                        "photo" => $value['src'],
                        "caption" => isset($value['caption']) ? $value['caption'] : null 
                    );
                }
            }
        } else {
            $this->message[] = array(
                "chat_id" => $chatID,
                "text" => $message,
            );
        }

        return $this;
    }

    public function addInlineKeyboard($keyboard)
    {
        $inlineKeyboard = array(
            "inline_keyboard" => $keyboard
        );

        $this->keyboard = json_encode($inlineKeyboard);
        return $this;
    }

    public function addKeyboard($keyboard)
    {
        $inlineKeyboard = array(
            "keyboard" => $keyboard
        );

        $this->keyboard = json_encode($inlineKeyboard);
        return $this;
    }

    public function sendMessage()
    {
        try {
            for ($i=0; $i < count($this->message); $i++) { 
                $queryInArray = $this->message[$i];

                if ($this->keyboard && ($i + 1) == count($this->message)) {
                    if (!empty($this->adminKeyboard)) {
                        $this->keyboard = array_merge($this->keyboard, $this->adminKeyboard);
                    }
                    $queryInArray['reply_markup'] = $this->keyboard;
                }

                if (array_key_exists('photo', $queryInArray)) {
                    $string = $this->botUrl."sendPhoto?".http_build_query($queryInArray);
                } else {
                    $string = $this->botUrl."sendMessage?".http_build_query($queryInArray);
                }
                $this->logger->info($string);
                $this->logger->info($string);
                file_get_contents($string);
            }
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            $this->logger->error($th->getTraceAsString());
        }
    }
}


