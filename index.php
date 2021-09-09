<?php

require "./vendor/autoload.php";

use TelegramBot\Classes\Telegram;

try {
    $telegram = new Telegram("1997963802:AAHnNRQtNKdmdtquiHtPjeBzKkC2xCCAQzE");
    $telegram->getUpdate();
    $message = $telegram->getMessage();
    $telegram->setMessage($message['chatID'], $message['text'])->addKeyboard(array(
        array(
            array("text" => "1", "callback_data" => "myCallbackData"),
            array("text" => "2", "callback_data" => "myCallbackData")
        ),
        array(
            array("text" => "3", "callback_data" => "myCallbackData"),
            array("text" => "4", "callback_data" => "myCallbackData")
        ),
    ));
    $telegram->sendMessage();

} catch (Exception $e) {
    $myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
    $txt = 'Caught exception : '.$e->getMessage()."\n";
    fwrite($myfile, $txt);
    fclose($myfile);
}


// $t = array('ReplyKeyboardMarkup' => array('keyboard' => array(array("A", "B"))));

// $replyMarkup = array(
//     'inline_keyboard' => array(
//         array("A", "B"),
// 	array("C")
//     )
// );

// $keyboard = array(
//     "inline_keyboard" => array(
//         array(
//             array("text" => "1", "callback_data" => "myCallbackData"),
//             array("text" => "2", "callback_data" => "myCallbackData")
//         ),
//         array(
//             array("text" => "3", "callback_data" => "myCallbackData"),
//             array("text" => "4", "callback_data" => "myCallbackData")
//         ),
//     )
// );
// $picwithhtml = <<<EOT
// "<p>Like this<br>
// <a href="https://res.cloudinary.com/practicaldev/image/fetch/s--KJhLmFDG--/c_limit%2Cf_auto%2Cfl_progressive%2Cq_auto%2Cw_880/https://dev-to-uploads.s3.amazonaws.com/i/8f1y5bddj2d9aqngx4ol.png" class="article-body-image-wrapper"><img src="https://res.cloudinary.com/practicaldev/image/fetch/s--KJhLmFDG--/c_limit%2Cf_auto%2Cfl_progressive%2Cq_auto%2Cw_880/https://dev-to-uploads.s3.amazonaws.com/i/8f1y5bddj2d9aqngx4ol.png" alt="Alt Text" loading="lazy"></a></p>

// <h4>
//   <a name="now-you-have-all-things-that-you-need-for-api-auth-" href="#now-you-have-all-things-that-you-need-for-api-auth-">
//   </a>
//   now you have all things that you need for api auth :)
// </h4>

// <h4>
//   <a name="if-you-have-question-you-can-ask-it-here-" href="#if-you-have-question-you-can-ask-it-here-">
//   </a>
//   if you have question you can ask it here :)
// </h4>

// <h4>
//   <a name="share-it-with-your-friends-if-you-like-it" href="#share-it-with-your-friends-if-you-like-it">
//   </a>
//   share it with your friends if you like it
// </h4>
// EOT;

// $replyMarkupl = array(
//     'inline_keyboard' => array("d","w")
// );

// if (isset($update["message"])){

//     $chatID = $update["message"]["chat"]["id"];
//     $text = $update["message"]["text"];

//     $args = array(
//         "chat_id" => $chatID,
//         "text" => $picwithhtml,
//         "parse_mode" => "HTML",
//         "reply_markup" => json_encode($keyboard),
//     );



//     if ( $text == '/start' ) {
//         // send welcome message
//         file_get_contents($website."sendMessage?chat_id=".$chatID."&text=Welcome to my bot");
//     }else{
//         // send another message or do your logic (up to you)
//         file_get_contents($website."sendMessage?".http_build_query($args));
//     }

//     $args = array(
//         "chat_id" => $chatID,
//         "photo" => 'https://image.shutterstock.com/image-photo/example-word-written-on-wooden-260nw-1765482248.jpg',
//         "reply_markup" => json_encode($keyboard),
//     );
    
//     file_get_contents($website."sendPhoto?".http_build_query($args));
// }