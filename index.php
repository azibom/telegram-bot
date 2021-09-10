<?php

require "./vendor/autoload.php";

use TelegramBot\Classes\Telegram;


$myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
fwrite($myfile, 'we are here 1' . PHP_EOL);
fclose($myfile);

try {
    $telegram = new Telegram("1997963802:AAHnNRQtNKdmdtquiHtPjeBzKkC2xCCAQzE");
    $telegram->addMenu([
        "news" => [
            "HI1" => "SAMPLE1",
            "HI2" => "SAMPLE2",
            "HI3" => "SAMPLE3",
        ],
        "aboutUs" => "hi I am a bot"
    ]);

    $telegram->getUpdate();
    $telegram->setAnswer();
    $telegram->sendMessage();

    // $message = $telegram->getMessage();
    // $telegram->setMessage($message['chatID'], $message['text'])->addKeyboard(array(
    //     array(
    //         array("text" => "1"),
    //         array("text" => "2")
    //     ),
    //     array(
    //         array("text" => "3"),
    //         array("text" => "4")
    //     ),
    // ));
    // $telegram->sendMessage();

} catch (Exception $e) {
    $myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
    $txt = 'Caught exception : '.$e->getMessage()."\n";
    fwrite($myfile, $txt);
    fclose($myfile);
}