<?php

require "./vendor/autoload.php";
require_once(__DIR__ . '/logger.php');

use TelegramBot\Classes\Telegram;
use TelegramBot\Classes\TelegramRepository;

$logger = getLogger();
$logger->info("hi");

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
    "سوالات متداول" => [
        "غذای گربه" => "غذای گربه باید مناسب باشد",
        "وسایل مورد نیاز گریه" => [
            "خاک گربه" => "خاک گربه برای کاشتن گربه استفاده نمیشود و برای دستشویی کردن گربه استفاده میشود",
            "باکس گربه" => "باکس گربه بیشتر برای جایجایی گربه مورد استفاده قرار میگیرد",
        ],
    ],
    "درباره ی ما" => "ما گوربه میفروشیم",
    "special" => $telegram->message([
        ["type" => "text", "text" => "hi hi"],
        ["type" => "image", "src" => "https://i.guim.co.uk/img/media/26392d05302e02f7bf4eb143bb84c8097d09144b/446_167_3683_2210/master/3683.jpg?width=1200&height=1200&quality=85&auto=format&fit=crop&s=49ed3252c0b2ffb49cf8b508892e452d"],
        ["type" => "text", "text" => "hi hi2"],
    ])
]);

$telegram->getUpdate();
$telegram->setAnswer();
$telegram->sendMessage();