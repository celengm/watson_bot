<?php
date_default_timezone_set("Asia/Bangkok");
require_once('./vendor/autoload.php');

// Namespace
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot;
use LINE\LINEBot\MessageBuilder;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;

$channel_token = '7En2HqJRS/m2SQIoxGyE3G6gwAGT9LqgrG9H5vMjDjtbOrXzVWRleZ+flxM9yZ/OTNMb99iUcvCTtAWrbX0IM0eRrX4yyLA8Xqlm9RlLuy1vKSCTwwN/smvluF+EC0Vd7OffEpakSvidWoIq0R/sCQdB04t89/1O/w1cDnyilFU=';
$channel_secret = 'a13f45009f928d96922ccff8d5390091';

// Get message from Line API
$content = file_get_contents('php://input');
$events = json_decode($content, true);

$textMessageBuilder = '';

function validNameCheckIn($receiveText)
{

    if ($receiveText == 'วัติเช็คอิน') {
        return $textMessageBuilder = new TextMessageBuilder('สวัสดีครับคุณวัติ เช็คอินที่เวลา ' . date('H:i'));
    } else if ($receiveText == 'ปืนเช็คอิน') {
        return $textMessageBuilder = new TextMessageBuilder('สวัสดีครับคุณปืน เช็คอินที่เวลา ' . date('H:i'));
    } else if ($receiveText == 'ตู่เช็คอิน') {
        return $textMessageBuilder = new TextMessageBuilder('สวัสดีครับคุณตู่ เช็คอินที่เวลา ' . date('H:i'));
    } else if ($receiveText == 'ฟลุ๊คเช็คอิน') {
        return $textMessageBuilder = new TextMessageBuilder('สวัสดีครับคุณฟลุ๊ค เช็คอินที่เวลา ' . date('H:i'));
    } else if ($receiveText == 'นาถเช็คอิน') {
        return $textMessageBuilder = new TextMessageBuilder('นาถเด็กเกรียน2017-2560 เช็คอินที่เวลา ' . date('H:i'));
    } else if ($receiveText == 'เบียร์เช็คอิน') {
        return $textMessageBuilder = new TextMessageBuilder('สวัสดีครับคุณเอกชัย เช็คอินที่เวลา ' . date('H:i'));
    } else if ($receiveText == 'ปิงเช็คอิน') {
        return $textMessageBuilder = new TextMessageBuilder('สวัสดีครับคุณปิง เช็คอินที่เวลา ' . date('H:i'));
    } else if ($receiveText == 'แคทเช็คอิน') {
        return $textMessageBuilder = new TextMessageBuilder('สวัสดีครับคุณแคทร้อยผัว เช็คอินที่เวลา ' . date('H:i'));
    } else if ($receiveText == 'ผึ้งเช็คอิน') {
        return $textMessageBuilder = new TextMessageBuilder('สวัสดีครับคุณผึ้งจอฟ้า เช็คอินที่เวลา ' . date('H:i'));
    } else if ($receiveText == 'มะปรางเช็คอิน') {
        return $textMessageBuilder = new TextMessageBuilder('สวัสดีครับคุณมะปราง เช็คอินที่เวลา ' . date('H:i'));
    } else if ($receiveText == 'หวานเช็คอิน') {
        return $textMessageBuilder = new TextMessageBuilder('สวัสดีครับคุณนักข่าวหวาน เช็คอินที่เวลา ' . date('H:i'));
    } else if ($receiveText == 'กิ่งเช็คอิน') {
        return $textMessageBuilder = new TextMessageBuilder('สวัสดีครับคุณ PM กิ่ง เช็คอินที่เวลา ' . date('H:i'));
    } else if ($receiveText == 'ฟาริสเช็คอิน') {
        return $textMessageBuilder = new TextMessageBuilder('สวัสดีครับคุณฟาริส เช็คอินที่เวลา ' . date('H:i'));
    } else if ($receiveText == 'แต้งเช็คอิน') {
        return $textMessageBuilder = new TextMessageBuilder('สวัสดีครับคุณแต้ง เช็คอินที่เวลา ' . date('H:i'));
    } else if ($receiveText == 'แยมเช็คอิน') {
        return $textMessageBuilder = new TextMessageBuilder('สวัสดีครับคุณแยม เช็คอินที่เวลา ' . date('H:i'));
    } else if ($receiveText == 'แอ๋มเช็คอิน') {
        return $textMessageBuilder = new TextMessageBuilder('สวัสดีครับคุณแอ๋ม เช็คอินที่เวลา ' . date('H:i'));
    }

}

if (!is_null($events['events'])) {

    foreach ($events['events'] as $event) {
        //  Line API send a lot of event type,
        if ($event['type'] == 'message') {
            switch ($event['message']['type']) {
                case 'text':

                    $receiveText = $event['message']['text'];

                    // Get replyToken
                    $replyToken = $event['replyToken'];

                    $textMessageBuilder = validNameCheckIn($receiveText);

                    if($receiveText == 'สวัสดีค่ะ' || $receiveText == 'สวัสดีจ้า' || $receiveText == 'ดีจ้า'){

                    }

                    $httpClient = new CurlHTTPClient($channel_token);
                    $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));
                    $response = $bot->replyMessage($replyToken, $textMessageBuilder);
                    break;
            }

        } else if ($event['type'] == 'follow') {

            $replyToken = $event['replyToken'];

            // Greeting
            $respMessage = 'สวัสดีนะ รู้สึกยินดีมากๆ ที่ได้เธอเป็นเพื่อน 5555 มีอะไรคุยกันได้น้า';

            $httpClient = new CurlHTTPClient($channel_token);
            $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));

            $textMessageBuilder = new TextMessageBuilder($respMessage);
            $response = $bot->replyMessage($replyToken, $textMessageBuilder);

        } else if ($event['type'] == 'join') {

            $replyToken = $event['replyToken'];

            // Greeting
            $respMessage = 'ขอบคุณน้าที่พาเข้ากลุ่ม จ้า';

            $httpClient = new CurlHTTPClient($channel_token);
            $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));

            $textMessageBuilder = new TextMessageBuilder($respMessage);
            $response = $bot->replyMessage($replyToken, $textMessageBuilder);

        }
    }
}


echo "OK";


