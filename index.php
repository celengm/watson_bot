<?php
date_default_timezone_set("Asia/Bangkok");
require_once('./vendor/autoload.php');

// Namespace
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;

$channel_token = '7En2HqJRS/m2SQIoxGyE3G6gwAGT9LqgrG9H5vMjDjtbOrXzVWRleZ+flxM9yZ/OTNMb99iUcvCTtAWrbX0IM0eRrX4yyLA8Xqlm9RlLuy1vKSCTwwN/smvluF+EC0Vd7OffEpakSvidWoIq0R/sCQdB04t89/1O/w1cDnyilFU=';
$channel_secret = 'a13f45009f928d96922ccff8d5390091';

// Get message from Line API
$content = file_get_contents('php://input');
$events = json_decode($content, true);

if (!is_null($events['events'])) {

    foreach ($events['events'] as $event) {
        //  Line API send a lot of event type,
        if ($event['type'] == 'message') {
            switch ($event['message']['type']) {
                case 'text':

                    $receiveText = $event['message']['text'];

                    // Get replyToken
                    $replyToken = $event['replyToken'];

                    if ($receiveText == '1') {

                        $textMessageBuilder = new TextMessageBuilder('ว่าไงนี้คือข้อความของคุณ ' . $receiveText);

                    } else if ($receiveText == '2') {
                        // Reply message
                        $originalContentUrl = 'https://preview.ibb.co/g4pTOG/Pets.jpg';
                        /*$respMessage = 'ขณะนี้เวลา '. date('H:i')  . $event['message']['text'];*/
                        $previewImageUrl = 'https://image.ibb.co/dd7Mcb/Pets.jpg';

                        $textMessageBuilder = new ImageMessageBuilder($originalContentUrl, $previewImageUrl);

                    } else if ($receiveText == '3') {

                        $arrayAction = [
                            [
                                "type" => "postback",
                                "label" => "Buy",
                                "data" => "action=buy&itemid=123"
                            ],
                            [
                                "type" => "postback",
                                "label" => "Add to cart",
                                "data" => "action=add&itemid=123"
                            ],
                            [
                                "type" => "uri",
                                "label" => "View detail",
                                "uri" => "http://example.com/page/123"
                            ]

                        ];

                        $textMessageBuilder = new ButtonTemplateBuilder('สวัสดีครับ', 'มีอะไรให้ช่วยเหลือมั้ยครับ', 'https://preview.ibb.co/g4pTOG/Pets.jpg', $arrayAction);

                    }

                    $httpClient = new CurlHTTPClient($channel_token);
                    $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));
                    $response = $bot->replyMessage($replyToken, $textMessageBuilder);
                    break;
            }

        } else if ($event['type'] == 'follow') {

            $replyToken = $event['replyToken'];

            // Greeting
            $respMessage = 'Hello This is WATSON';

            $httpClient = new CurlHTTPClient($channel_token);
            $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));

            $textMessageBuilder = new TextMessageBuilder($respMessage);
            $response = $bot->replyMessage($replyToken, $textMessageBuilder);

        } else if ($event['type'] == 'join') {

            $replyToken = $event['replyToken'];

            // Greeting
            $respMessage = 'ขอบคุณน้าที่พาเข้ากลุ่ม';

            $httpClient = new CurlHTTPClient($channel_token);
            $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));

            $textMessageBuilder = new TextMessageBuilder($respMessage);
            $response = $bot->replyMessage($replyToken, $textMessageBuilder);

        }
    }
}

echo "OK";