<?php

require "./vendor/autoload.php";

use TelegramBot\Classes\Telegram;


$myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
fwrite($myfile, 'we are here 1' . PHP_EOL);
fclose($myfile);

try {
    $telegram = new Telegram("1997963802:AAHnNRQtNKdmdtquiHtPjeBzKkC2xCCAQzE");
    $telegram->addMenu([
        "درباره ی گربه ها" => [
            "غذای گربه ها" => "غذای گربه ها خوب است",
            "خواب گربه ها" => "خواب گربه باید مناسب باشد",
        ],
        "فروشگاه گربه" => [
            "فندق" => "فندق گربه ی زیبایی است",
            "قصه" => "قصه برای فروش نیست :|",
        ],
        "درباره ی ما" => "ما گوربه میفروشیم"
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