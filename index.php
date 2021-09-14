<?php

require "./vendor/autoload.php";

use TelegramBot\Classes\Telegram;
use TelegramBot\Classes\TelegramRepository;

$myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
fwrite($myfile, 'we are here 1' .get_current_user(). PHP_EOL);
fclose($myfile);

try {
    $telegramRepository = new TelegramRepository();
    $telegram = new Telegram("1997963802:AAHnNRQtNKdmdtquiHtPjeBzKkC2xCCAQzE", $telegramRepository);
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

    $res = $telegram->getUpdate();

    $myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
    fwrite($myfile, 'weeeeeeeeeeeeeeeeeeeeeeeeeeee' . PHP_EOL);
    fwrite($myfile, json_encode($res) . PHP_EOL);
    fwrite($myfile, 'weeeeeeeeeeeeeeeeeeeeeeeeeeee' . PHP_EOL);

    fclose($myfile);

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