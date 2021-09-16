<?php

require_once(__DIR__ . '/vendor/autoload.php');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

function getLogger() : Logger
{
    $logger = null;

    if ($logger === null) {
        $logger = new Logger('Telegram');
        $logger->pushHandler(new StreamHandler(__DIR__.'/log.log'));
    }

    return $logger;
}
