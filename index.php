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
        "فندق" => $telegram->message([
            ["type" => "image", "src" => "AgACAgQAAxkBAAIHw2FDOXwPXl_BHmCGT6Cedj-VAAHH3QACcbYxG9lAGVJMj4HHKaBVwAEAAwIAA3MAAyAE", "caption" => "#fandogh"],
            ["type" => "text", "text" => "فندق گربه ی زیبایی است"],
        ]),
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
]);

$telegram->getUpdate();
$telegram->setAnswer();
$telegram->sendMessage();