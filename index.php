<?php
date_default_timezone_set("Asia/Bangkok");
require_once('./vendor/autoload.php');
require_once('./config/connection.php');

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
                    $user_line_id = $event['source']['userId'];

                    // Get replyToken
                    $replyToken = $event['replyToken'];

                    $textMessageBuilder = validNameCheckIn($receiveText);


                    $queryFindQustion = $db_connection->prepare("SELECT * FROM questions WHERE name_question= :my_question");
                    $queryFindQustion->bindValue(':my_question', $receiveText);
                    $queryFindQustion->execute();

                    if($queryFindQustion->rowCount() > 0){

                        $qID = $queryFindQustion->fetchColumn();

                        $sql = "SELECT * FROM answer WHERE id_question= :id_question OFFSET floor(random() * (select count(*) from answer)) LIMIT 1";
                        $queryFindAnswer = $db_connection->prepare($sql);
                        $queryFindAnswer->bindValue('id_question',$qID);
                        $queryFindAnswer->execute();

                        while($row = $queryFindAnswer->fetch(PDO::FETCH_ASSOC))
                        {

                            $answer_send = $row['name_answer'];
                            $textMessageBuilder = new TextMessageBuilder($answer_send.'');

                        }

                    }



                    if ($receiveText == 'บริษัทอยู่ที่ไหน') {

                        $address = 'อาคารซอฟท์แวร์ปาร์ค ชั้น 3 ห้อง 303 ถนน เจ้าฟ้าตะวันตก ตำบล วิชิต อำเภอเมืองภูเก็ต ภูเก็ต 83000';
                        $textMessageBuilder = new MessageBuilder\LocationMessageBuilder('บริษัท เลี่ยนอุดม จำกัด',$address,'7.8749316','98.3631689');

                    } else if ($receiveText == 'ขอวาร์ปหน่อย') {
                        // Reply message

                        $warps = ['IPZ-405','STAR-248','SNIS-228','MVSD-255','BCDP-086','WANZ-540','PPPD-537'];
                        $random_keys = array_rand($warps,1);

                        $textMessageBuilder = new TextMessageBuilder('จัดไปลูกเพ่ '.$warps[$random_keys]);

                    } else if ($receiveText == 'เที่ยงนี้กินอะไรดี') {

                        $columns = array();
                        $nameMenu = ['ข้าวผัดหมูกรอบ','กระเพราไข่ดาว','คะน้าหมูกรอบ','หมูทอดกระเทียม, หมูทอด',
                            'ผัดซีอิ้ว','ผัดผักรวม','ผัดพริกหยวก','ผัดพริกเผา','KFC','Pizza'
                            ];
                        $pictureMenu = [
                            'https://image.ibb.co/b4jy7b/3_CL3_MZ976_FA2_AB862_F8_AA9mx.jpg',
                            'https://image.ibb.co/n1gd7b/1398137376_o.jpg',
                            'https://image.ibb.co/iWCeDG/480x280.jpg',
                            'https://image.ibb.co/mTC1Sb/a75.jpg',
                            'https://preview.ibb.co/i3GBSb/min13.jpg',
                            'https://preview.ibb.co/eoLufw/d49f8fb8_6744_44fe_ab02_e16fe49fc2d9.jpg',
                            'https://preview.ibb.co/fzUsYG/3_BRYQWE2877_E24_F6136_D56lv.jpg',
                            'https://preview.ibb.co/cHWH0w/1391753959_81_o.jpg',
                            'https://preview.ibb.co/drneDG/oqydw0nhqquy5_VQs_MVh_o.jpg',
                            'https://preview.ibb.co/ffjgSb/c700x420.jpg',
                            'https://preview.ibb.co/de1gtG/1418114529_DSCF14121_o.jpg',
                            'https://preview.ibb.co/djxzfw/1420811272_e32_o.jpg',
                            'https://preview.ibb.co/ny7zfw/kao_man_kai_09.jpg'
                        ];

                        $random_keys = array_rand($pictureMenu,1);

                        /*$textMessageBuilder = new TextMessageBuilder($nameMenu[$random_keys]);*/

                        $textMessageBuilder = new ImageMessageBuilder($pictureMenu[$random_keys],$pictureMenu[$random_keys]);

                    }else if (strpos($receiveText, 'สอนสไมล์') !== false) {
                        $x_tra = str_replace("สอนสไมล์", "", $receiveText);
                        $pieces = explode("|", $x_tra);
                        $_question = str_replace("[", "", $pieces[0]);
                        $_answer = str_replace("]", "", $pieces[1]);


                        $queryFindQustion = $db_connection->prepare("SELECT * FROM questions WHERE name_question= :my_question");
                        $queryFindQustion->bindValue(':my_question', $_question);
                        $queryFindQustion->execute();

                        $dateToday = date('Y-m-d');
                        // เช็คว่ามีในฐานข้อมูลมั้ย
                        if($queryFindQustion->rowCount() == 0){


                            $sql = "INSERT INTO questions(name_question,created_by,created_at) VALUES (:name_question,:createby,:createat)";
                            $saveQuestion = $db_connection->prepare($sql);
                            $saveQuestion->bindValue(':name_question',$_question);
                            $saveQuestion->bindValue(':createby',$user_line_id);
                            $saveQuestion->bindValue(':createat',$dateToday);
                            $saveQuestion->execute();


                            $sql = "INSERT INTO answer(id_question,name_answer,created_by,created_at) VALUES (:id_question,:name_answer,:created_by,:todaydate)";
                            $saveAnswer = $db_connection->prepare($sql);
                            $saveAnswer->bindValue(':id_question',$db_connection->lastInsertId());
                            $saveAnswer->bindValue(':name_answer',$_answer);
                            $saveAnswer->bindValue(':created_by',$user_line_id);
                            $saveAnswer->bindValue(':todaydate',$dateToday);
                            $saveAnswer->execute();

                            $textMessageBuilder = new TextMessageBuilder('ขอบคุณนะจ้ะที่ช่วยสอนสไมล์');

                        }else{
                            // เอา ID คำถาม
                            $questionID = $queryFindQustion->fetchColumn();

                            $sql = "INSERT INTO answer(id_question,name_answer,created_by,created_at) VALUES (:id_question,:name_answer,:created_by,:todaydate)";
                            $saveAnswer = $db_connection->prepare($sql);
                            $saveAnswer->bindValue(':id_question',$questionID);
                            $saveAnswer->bindValue(':name_answer',$_answer);
                            $saveAnswer->bindValue(':created_by',1);
                            $saveAnswer->bindValue(':todaydate',$dateToday);
                            $saveAnswer->execute();

                            $textMessageBuilder = new TextMessageBuilder('ขอบคุณนะจ้ะที่ช่วยสอนสไมล์');

                        }


                    }else if($receiveText == 'ซื้อเสื้อหน่อย'){

                        $columns = array();
                        $img_url = [
                            'https://preview.ibb.co/kVnSJG/3.jpg',
                            'https://image.ibb.co/b9XBdG/183.jpg',
                            'https://preview.ibb.co/jqQxJG/153.jpg',
                            'https://preview.ibb.co/n9ojyG/006.jpg'
                        ];


                        for($i=0;$i<4;$i++) {
                            $actions = array(
                                new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder("ดูสินค้า","https://www.facebook.com/pg/sabuycentral/shop/?rid=189993954752890&rt=39")
                            );
                            $column = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("เสื้อยืด", "เสื้อผ้าใส่สบายราคาถูก", $img_url[$i] , $actions);
                            $columns[] = $column;
                        }

                        $carousel = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder($columns);
                        $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("แนะนำเสื้อจากร้าน Sabuy Central เลยจ้า", $carousel);

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
            $respMessage = 'ขอบคุณน้าที่พาเข้ากลุ่ม จ้า <br>
                น้องชื่อสไมล์นะคะ น้องถูกสร้างมาขึ้นเพื่อพูดคุย ขำๆ กับ พี่ๆ เลี่ยนอุดม จ้า <br>
                วิธีการใช้งานน้องง่ายมากๆจ้า ตอนนี้น้องมีคำสั่งตามนี้ค่ะ (ไม่ต้องพิมพ์ - นะคะ)<br>
                - วัติเช็คอิน หรือตามชื่อของพี่ๆได้เลยจ่ะ (ตู่เช็คอิน,ปืนเช็คอิน) <br>
                - บริษัทอยู่ที่ไหน <br>
                - เที่ยงนี้กินอะไรดี <br>
                - ขอวาร์ปหน่อย <br>
                - ซื้อเสื้อหน่อย <br>
                - สอนสไมล์พูดด้วยนะคะ สอนสไมล์[คำถาม|คำตอบ] เช่น สอนสไมล์[ใครหน้าตาดีที่สุด|คุณวัติจ้า]
            ';

            $httpClient = new CurlHTTPClient($channel_token);
            $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));

            $textMessageBuilder = new TextMessageBuilder($respMessage);
            $response = $bot->replyMessage($replyToken, $textMessageBuilder);

        }
    }
}


echo "OK";


