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


function update_guanteen($id)
{

    global $db_connection;

    $queryFindQustion = $db_connection->prepare("SELECT point FROM guanteen WHERE id= :my_id");
    $queryFindQustion->bindValue(':my_id', $id);
    $queryFindQustion->execute();
    $resultArr = $queryFindQustion->fetch(PDO::FETCH_OBJ);
    $getPoint = $resultArr->point;
    $getPoint++;

    $sqlUpdate = "UPDATE guanteen SET point=:my_point WHERE id=:my_id";
    $save = $db_connection->prepare($sqlUpdate);
    $save->bindValue(':my_point', $getPoint);
    $save->bindValue(':my_id', $id);
    $save->execute();


}

function validNameCheckIn($receiveText, $user_line_id)
{

    $dateCheckin = date('Y-m-d H:i:s');
    $nameCheckin = '';

    global $db_connection;


    if ($receiveText == 'วัติเช็คอิน') {

        global $nameCheckin;
        $nameCheckin = 'วัติ';

    } else if ($receiveText == 'ปืนเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'ปืนคนดี ศรีสุราษฎร์';
    } else if ($receiveText == 'ตู่เช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'ประธาน ตู่';
    } else if ($receiveText == 'ฟลุ๊คเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'เทพเจ้าท้ายตาราง';
    } else if ($receiveText == 'นาถเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'นาถ แดนซ์ซิ่งควีนแห่ง LIANUDOM';
    } else if ($receiveText == 'เบียร์เช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'เบียร์';
    } else if ($receiveText == 'ปิงเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'ปิง';
    } else if ($receiveText == 'ต้นเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'ท่านต้น';
    } else if ($receiveText == 'แคทเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'แคทเจ้าแม่แคที่ ร้อยผัว';
    } else if ($receiveText == 'ผึ้งเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'ผึ้ง';
    } else if ($receiveText == 'มะปรางเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'มะปราง';
    } else if ($receiveText == 'หวานเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'นักข่าวหวาน';
    } else if ($receiveText == 'กิ่งเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'PMกิ่ง';
    } else if ($receiveText == 'ฟาริสเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'ฟาริส';
    } else if ($receiveText == 'แต้งเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'แต้ง';
    } else if ($receiveText == 'แยมเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'แยม';
    } else if ($receiveText == 'หญิงเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'หญิง';
    }
    else if ($receiveText == 'เจเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'เจ';
    }

    else {
        return $textMessageBuilder = new TextMessageBuilder('โปรดระบุชื่อในการเช็คอินด้วยจ้าที่รัก');
    }

    if (!empty($nameCheckin)) {
        $sqlCheckin = "INSERT INTO users(nick_name,checkin_at,id_line) VALUES (:myname,:mydate,:my_idline)";
        $saveAnswer = $db_connection->prepare($sqlCheckin);
        $saveAnswer->bindValue(':myname', $nameCheckin);
        $saveAnswer->bindValue(':my_idline', $user_line_id);
        $saveAnswer->bindValue(':mydate', $dateCheckin);
        $saveAnswer->execute();

        return $textMessageBuilder = new TextMessageBuilder('สวัสดีค่ะคุณ' . $nameCheckin . ' เช็คอินที่เวลา ' . date('H:i'));
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

                    if ((strpos($receiveText, 'เช็คอิน') !== false) && $receiveText != 'แก้วขอข้อมูลเช็คอินวันนี้หน่อย' && $receiveText != 'ข้อมูลเช็คอินวันนี้') {

                        $textMessageBuilder = validNameCheckIn($receiveText, $user_line_id);

                    }


                    $queryFindQustion = $db_connection->prepare("SELECT id FROM questions WHERE name_question= :my_question LIMIT 1");
                    $queryFindQustion->bindValue(':my_question', $receiveText);
                    $queryFindQustion->execute();

                    if ($queryFindQustion->rowCount() > 0) {


                        $qID = $queryFindQustion->fetchColumn();

                        $sql = "SELECT name_answer FROM answer WHERE id_question= $qID";
                        $queryFindAnswer = $db_connection->query($sql);

                        /*$sql = "SELECT name_answer FROM answer WHERE id_question= :id_question OFFSET floor(random() * (select count(*) from answer)) LIMIT 1";
                        $queryFindAnswer = $db_connection->prepare($sql);
                        $queryFindAnswer->bindValue('id_question', $qID);
                        $queryFindAnswer->execute();*/

                        $array_answer = [];
                        /*echo '<pre>';
                        print_r($queryFindAnswer->fetch(PDO::FETCH_ASSOC));
                        echo '</pre>';*/

                        while ($row = $queryFindAnswer->fetch(PDO::FETCH_ASSOC)) {
                            $array_answer[] = $row['name_answer'];
                        }

                        $random_keys = array_rand($array_answer, 1);

                        if (getimagesize($array_answer[$random_keys])) {
                            $textMessageBuilder = new ImageMessageBuilder($array_answer[$random_keys], $array_answer[$random_keys]);

                        } else {
                            $textMessageBuilder = new TextMessageBuilder($array_answer[$random_keys]);
                        }


                    }

                    if ($receiveText == 'บริษัทอยู่ที่ไหน') {

                        $address = 'อาคารซอฟท์แวร์ปาร์ค ชั้น 3 ห้อง 303 ถนน เจ้าฟ้าตะวันตก ตำบล วิชิต อำเภอเมืองภูเก็ต ภูเก็ต 83000';
                        $textMessageBuilder = new MessageBuilder\LocationMessageBuilder('บริษัท เลี่ยนอุดม จำกัด', $address, '7.8749316', '98.3631689');

                    } else if ($receiveText == 'ขอวาร์ปหน่อย') {
                        // Reply message

                        $warps = ['IPZ-405', 'STAR-248', 'SNIS-228', 'MVSD-255', 'BCDP-086', 'WANZ-540', 'PPPD-537'];
                        $random_keys = array_rand($warps, 1);

                        $textMessageBuilder = new TextMessageBuilder('จัดไปลูกเพ่ ' . $warps[$random_keys]);

                    } else if ($receiveText == 'เที่ยงนี้กินอะไรดี' || $receiveText == 'วันนี้กินอะไรดี') {

                        $columns = array();
                        $nameMenu = ['ข้าวผัดหมูกรอบ', 'กระเพราไข่ดาว', 'คะน้าหมูกรอบ', 'หมูทอดกระเทียม, หมูทอด',
                            'ผัดซีอิ้ว', 'ผัดผักรวม', 'ผัดพริกหยวก', 'ผัดพริกเผา', 'KFC', 'Pizza', 'ก๋วยเตี๋ยวหมูต้มยำ', 'ก๋วยเตี๋ยวลูกชิ้นหมูต้มยำ', 'ข้าวมันไก่'
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

                        $answerText = [
                            'กินมั้ยคะ',
                            'จะกินกับหนูมั้ยคะ',
                            'แก้ว่อยนะคะ',
                            'ลองเลยค่ะวันนี้ แก้วแนะนำจ้า'
                        ];

                        $random_keys = array_rand($pictureMenu, 1);
                        $random_answerText = array_rand($answerText, 1);

                        $textMessageBuilder = new MessageBuilder\MultiMessageBuilder();
                        $textMessageBuilder->add(new ImageMessageBuilder($pictureMenu[$random_keys], $pictureMenu[$random_keys]))
                            ->add(new TextMessageBuilder($nameMenu[$random_keys] . ' ' . $answerText[$random_answerText]));

                        /*$textMessageBuilder = new TextMessageBuilder($nameMenu[$random_keys]);*/

                        /*$textMessageBuilder = new ImageMessageBuilder($pictureMenu[$random_keys], $pictureMenu[$random_keys]);*/

                    } else if (strpos($receiveText, 'สอนแก้ว') !== false) {
                        $x_tra = str_replace("สอนแก้ว", "", $receiveText);
                        $pieces = explode("|", $x_tra);
                        $_question = str_replace("[", "", $pieces[0]);
                        $_answer = str_replace("]", "", $pieces[1]);


                        $queryFindQustion = $db_connection->prepare("SELECT * FROM questions WHERE name_question= :my_question");
                        $queryFindQustion->bindValue(':my_question', $_question);
                        $queryFindQustion->execute();

                        $dateToday = date('Y-m-d H:i:s');
                        // เช็คว่ามีในฐานข้อมูลมั้ย
                        if ($queryFindQustion->rowCount() == 0) {


                            $sql = "INSERT INTO questions(name_question,created_by,created_at) VALUES (:name_question,:createby,:createat)";
                            $saveQuestion = $db_connection->prepare($sql);
                            $saveQuestion->bindValue(':name_question', $_question);
                            $saveQuestion->bindValue(':createby', $user_line_id);
                            $saveQuestion->bindValue(':createat', $dateToday);
                            $saveQuestion->execute();


                            $sql = "INSERT INTO answer(id_question,name_answer,created_by,created_at) VALUES (:id_question,:name_answer,:created_by,:todaydate)";
                            $saveAnswer = $db_connection->prepare($sql);
                            $saveAnswer->bindValue(':id_question', $db_connection->lastInsertId());
                            $saveAnswer->bindValue(':name_answer', $_answer);
                            $saveAnswer->bindValue(':created_by', $user_line_id);
                            $saveAnswer->bindValue(':todaydate', $dateToday);
                            $saveAnswer->execute();

                            $textMessageBuilder = new TextMessageBuilder('ขอบคุณนะจ้ะที่ช่วยสอนแก้ว');

                        } else {
                            // เอา ID คำถาม
                            $questionID = $queryFindQustion->fetchColumn();

                            $sql = "INSERT INTO answer(id_question,name_answer,created_by,created_at) VALUES (:id_question,:name_answer,:created_by,:todaydate)";
                            $saveAnswer = $db_connection->prepare($sql);
                            $saveAnswer->bindValue(':id_question', $questionID);
                            $saveAnswer->bindValue(':name_answer', $_answer);
                            $saveAnswer->bindValue(':created_by', $user_line_id);
                            $saveAnswer->bindValue(':todaydate', $dateToday);
                            $saveAnswer->execute();

                            $textMessageBuilder = new TextMessageBuilder('ขอบคุณนะจ้ะที่ช่วยสอนแก้ว');

                        }


                    } else if ($receiveText == 'ซื้อเสื้อหน่อย') {

                        $columns = array();
                        $img_url = [
                            'https://preview.ibb.co/kVnSJG/3.jpg',
                            'https://image.ibb.co/b9XBdG/183.jpg',
                            'https://preview.ibb.co/jqQxJG/153.jpg',
                            'https://preview.ibb.co/n9ojyG/006.jpg'
                        ];


                        for ($i = 0; $i < 4; $i++) {
                            $actions = array(
                                new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder("ดูสินค้า", "https://www.facebook.com/pg/sabuycentral/shop/?rid=189993954752890&rt=39")
                            );
                            $column = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder("เสื้อยืด", "เสื้อผ้าใส่สบายราคาถูก", $img_url[$i], $actions);
                            $columns[] = $column;
                        }

                        $carousel = new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder($columns);
                        $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder("แนะนำเสื้อจากร้าน Sabuy Central เลยจ้า", $carousel);

                    } else if ($receiveText == 'คำสั่งแก้ว') {
                        $respMessage = " ขอบคุณน้าที่พาเข้ากลุ่ม จ้า \n
                น้องชื่อแก้วนะคะ น้องถูกสร้างมาขึ้นเพื่อพูดคุย ขำๆ กับ พี่ๆ เลี่ยนอุดม จ้า  \n
                วิธีการใช้งานน้องง่ายมากๆจ้า ตอนนี้น้องมีคำสั่งตามนี้ค่ะ (ไม่ต้องพิมพ์ - นะคะ) \n
- วัติเช็คอิน หรือตามชื่อของพี่ๆได้เลยจ่ะ (ตู่เช็คอิน,ปืนเช็คอิน)  \n
- บริษัทอยู่ที่ไหน  \n
- เที่ยงนี้กินอะไรดี  \n
- ขอวาร์ปหน่อย  \n
- ซื้อเสื้อหน่อย  \n
- สอนแก้วพูดด้วยนะคะ สอนแก้ว[คำถาม|คำตอบ] เช่น สอนแก้ว[ใครหน้าตาดีที่สุด|คุณวัติจ้า] \n
- แก้วขอสุ่มชื่อสมาชิกหน่อย \n
- ข้อมูลเช็คอินวันนี้ \n
- ขอเบแก้ว์วัติ , ขอเบแก้ว์พี่ฟลุ๊ค และคนอื่นได้ครับ \n
- ขอสภาพภูมิอากาศ \n 
- ขอสภาพภูมิอากาศภาคเหนือ หริอ ภาคใต้ฝั่งตะวันตก และภาคอื่นๆ \n
- ส่งโลเคชั่นคำนวณระยะทาง ถึง ซอฟท์แวร์ปาร์ค \n
- เพิ่มคะแนนกวนตีนค่ะ ตัวอย่าง (นาถกวนตีน+1) ให้มีคำว่า กวนตีน+1 อยู่ด้านหลังชื่อเล่นนะคะ \n
- ขอคะแนนกวนตีน 
                            ";

                        $textMessageBuilder = new TextMessageBuilder($respMessage);

                    } else if ($receiveText == '555' || $receiveText == 'โครตฮา' || $receiveText == 'ผ่าม' || $receiveText == 'จางในจาง' || $receiveText == 'หลกจริง') {


                        $randomSticker = [
                            [
                                'pk_ID' => 1,
                                'sk_ID' => 10,
                            ],
                            [
                                'pk_ID' => 1,
                                'sk_ID' => 100,
                            ],
                            [
                                'pk_ID' => 1,
                                'sk_ID' => 110,
                            ],
                            [
                                'pk_ID' => 2,
                                'sk_ID' => 163,
                            ]
                        ];


                        $random_keys = array_rand($randomSticker, 1);

                        $textMessageBuilder = new MessageBuilder\StickerMessageBuilder($randomSticker[$random_keys]['pk_ID'], $randomSticker[$random_keys]['sk_ID']);


                    } else if ($receiveText == 'ขอเพลงหน่อยจ่ะ') {

                        $arr_youtube = [
                            'https://www.youtube.com/watch?v=bONq00_XaA0',
                            'https://www.youtube.com/watch?v=_Wl9Y05j8yU',
                            'https://www.youtube.com/watch?v=9SfVVzN3oCU',
                            'https://www.youtube.com/watch?v=0_-bZiDbK3w',
                            'https://www.youtube.com/watch?v=5JGuHs_inbU',
                            'https://www.youtube.com/watch?v=ohgoyu1Th68',
                            ''
                        ];
                        $random_keys = array_rand($arr_youtube, 1);
                        $textMessageBuilder = new TextMessageBuilder($arr_youtube[$random_keys]);

                    } else if ($receiveText == 'สุดยอด' || $receiveText == 'สุดยอดไปเลย') {

                        $randomSticker = [
                            [
                                'pk_ID' => 1,
                                'sk_ID' => 14,
                            ],
                            [
                                'pk_ID' => 1,
                                'sk_ID' => 114,
                            ],
                            [
                                'pk_ID' => 1,
                                'sk_ID' => 125,
                            ],
                            [
                                'pk_ID' => 1,
                                'sk_ID' => 407,
                            ]
                        ];


                        $random_keys = array_rand($randomSticker, 1);

                        $textMessageBuilder = new MessageBuilder\StickerMessageBuilder($randomSticker[$random_keys]['pk_ID'], $randomSticker[$random_keys]['sk_ID']);

                    } else if (strpos($receiveText, '1+1=เท่าไหร่?') !== false) {

                        /*$ex_plodeArray = explode('=',$receiveText);*/


                        $textMessageBuilder = new MessageBuilder\MultiMessageBuilder();
                        $textMessageBuilder->add(new TextMessageBuilder('อะไรกันไม่รู้จริงหรอ'))
                            ->add(new TextMessageBuilder('2ไง'));

                    } else if ($receiveText == 'แก้วขอข้อมูลเช็คอินวันนี้หน่อย' || $receiveText == 'ข้อมูลเช็คอินวันนี้') {

                        $sqlGetDate = "SELECT users.* FROM users WHERE checkin_at <= now() - interval '-7 hours' AND checkin_at >= now() - interval '7 hours' ORDER BY checkin_at ASC";
                        $querytime = $db_connection->query($sqlGetDate);
                        $checkInText = 'ข้อมูลเช็คอินวันที่ ' . date('d/m/Y') . ' จ่ะ' . "\n";

                        while ($row = $querytime->fetch(PDO::FETCH_ASSOC)) {
                            $datetimeToday = $row['checkin_at'];
                            $timeDate = strtotime($datetimeToday);
                            $checkInText .= $row['nick_name'] . ' เวลา = ' . date('H:i:s', $timeDate) . "\n";
                        }

                        $checkInText .= "\n แก้วยินดีรับใช้จ้า";

                        $textMessageBuilder = new TextMessageBuilder($checkInText);

                    } else if ($receiveText == 'แก้วขอสุ่มชื่อสมาชิกหน่อย') {

                        $nameUser = [
                            'วัติ',
                            'ปิง',
                            'แคท',
                            'ผึ้ง',
                            'ต้น',
                            'แยม',
                            'ปืน',
                            'ฟลุ๊ค',
                            'นาถ',
                            'แต้ง',
                            'แอ๋ม',
                            'เบียร์',
                            'มะปราง',
                            'หวาน',
                            'กิ่ง',
                            'ฟาริส',
                            'ตู่'
                        ];

                        $random_keys = array_rand($nameUser, 1);
                        $textMessageBuilder = new TextMessageBuilder('ชื่อที่ออกได้แก่ แท่นแท๊นแต้น คุณ ' . $nameUser[$random_keys] . ' จ้า');

                    } else if (strpos($receiveText, 'ขอเบแก้ว์') !== false) {

                        $number_phone = 0;
                        $name = '';
                        $textResponse = '';

                        if (strpos($receiveText, 'กิ่ง') !== false) {
                            $number_phone = '0873833272';
                            $name = 'กิ่ง';
                        } else if (strpos($receiveText, 'ตู่') !== false) {
                            $number_phone = '0814761422';
                            $name = 'ตู่';
                        } else if (strpos($receiveText, 'ฟลุ๊ค') !== false) {
                            $number_phone = '0874194130';
                            $name = 'ฟลุ๊ก';
                        } else if (strpos($receiveText, 'แต้ง') !== false) {
                            $number_phone = '0824853836';
                            $name = 'แต้ง';
                        } else if (strpos($receiveText, 'ต้น') !== false) {
                            $number_phone = '0988322637';
                            $name = 'ต้น';
                        } else if (strpos($receiveText, 'ปิง') !== false) {
                            $number_phone = '0831958515';
                            $name = 'ปิง';

                        } else if (strpos($receiveText, 'วัติ') !== false) {
                            $number_phone = '0874785366';
                            $name = 'วัติ';
                        } else if (strpos($receiveText, 'มะปราง') !== false) {
                            $number_phone = '0950296644';
                            $name = 'มะปราง';
                        } else if (strpos($receiveText, 'เบียร์') !== false) {
                            $number_phone = '0824275986';
                            $name = 'เบียร์';
                        } else if (strpos($receiveText, 'ผึ้ง') !== false) {
                            $number_phone = '0980599360';
                            $name = 'ผึ้ง';
                        } else if (strpos($receiveText, 'นาถ') !== false) {
                            $number_phone = '089 653 8918';
                            $name = 'นาถ';
                        } else if (strpos($receiveText, 'แคท') !== false) {
                            $number_phone = '094 496 0656';
                            $name = 'แคท';
                        } else if (strpos($receiveText, 'หวาน') !== false) {
                            $number_phone = '089 596 1232';
                            $name = 'หวาน';
                        } else if (strpos($receiveText, 'ปืน') !== false) {
                            $number_phone = '095 041 3765';
                            $name = 'ปืน';
                        } else if (strpos($receiveText, 'แอ๋ม') !== false) {
                            $number_phone = '0980693839';
                            $name = 'แอ๋ม';
                        } else if (strpos($receiveText, 'ฟาริส') !== false) {
                            $number_phone = '0945919964';
                            $name = 'ฟาริส';
                        } else if (strpos($receiveText, 'แยม') !== false) {
                            $number_phone = '0828060546';
                            $name = 'แยม';
                        } else {
                            $number_phone = '';
                            $name = 'ไม่มีชื่อในระบบจ้า';
                        }

                        $textMessageBuilder = new TextMessageBuilder('เบแก้ว์คุณ ' . $name . ' คือ ' . $number_phone);

                    } else if (strpos($receiveText, 'สภาพภูมิอากาศ') !== false) {

//                        $url = 'http://data.tmd.go.th/api/WeatherWarningNews/v1/?uid=u60pongniwat.w&ukey=4643b5996103347437e6c710bd14f8a9&format=json';
                        $url = 'http://data.tmd.go.th/api/WeatherForecastDaily/V1/?type=json';

                        $ch = curl_init();
                        // Disable SSL verification
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        // Will return the response, if false it print the response
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        // Set the url
                        curl_setopt($ch, CURLOPT_URL, $url);
                        // Execute
                        $result = curl_exec($ch);
                        // Closing
                        curl_close($ch);

                        $arrayWeather = json_decode($result, true);

                        $textMessageRespones = 'วันที่ ' . date('d/m/Y') . "\n สภาพภูมิอากาศประเทศไทย 24 ชั่วโมงข้างหน้าจ้า \n\n\n";


                        if (strpos($receiveText, 'ภาคเหนือ') !== false) {
                            $textMessageRespones .= "สภาพภูมิอากาศภาคเหนือ \n" . $arrayWeather['DailyForecast']['RegionsForecast'][0]['Description'];

                        } else if (strpos($receiveText, 'ภาคตะวันออกเฉียงเหนือ') !== false || strpos($receiveText, 'ภาคอีสาน') !== false) {
                            $textMessageRespones .= "สภาพภูมิอากาศภาคตะวันออกเฉียงเหนือ \n" . $arrayWeather['DailyForecast']['RegionsForecast'][1]['Description'];

                        } else if (strpos($receiveText, 'ภาคกลาง') !== false) {
                            $textMessageRespones .= "สภาพภูมิอากาศภาคกลาง \n" . $arrayWeather['DailyForecast']['RegionsForecast'][2]['Description'];

                        } else if (strpos($receiveText, 'ภาคตะวันออก') !== false) {
                            $textMessageRespones .= "สภาพภูมิอากาศภาคตะวันออก \n" . $arrayWeather['DailyForecast']['RegionsForecast'][3]['Description'];

                        } else if (strpos($receiveText, 'ภาคใต้ฝั่งตะวันออก') !== false) {
                            $textMessageRespones .= "สภาพภูมิอากาศภาคใต้ฝั่งตะวันออก \n" . $arrayWeather['DailyForecast']['RegionsForecast'][4]['Description'];

                        } else if (strpos($receiveText, 'ภาคใต้ฝั่งตะวันตก') !== false) {
                            $textMessageRespones .= "สภาพภูมิอากาศภาคใต้ฝั่งตะวันตก \n" . $arrayWeather['DailyForecast']['RegionsForecast'][5]['Description'];

                        } else if (strpos($receiveText, 'กรุงเทพ') !== false) {
                            $textMessageRespones .= "สภาพภูมิอากาศกรุงเทพ \n" . $arrayWeather['DailyForecast']['RegionsForecast'][6]['Description'];

                        } else {
                            $textMessageRespones .= $arrayWeather['DailyForecast']['DescTh'];

                        }

                        $textMessageRespones .= "\n" . "จากกรมอุตุนิยมวิทยา \n น้องแก้วพยากรณ์อากาศ \nขอบคุณจ้า";

                        $textMessageBuilder = new TextMessageBuilder($textMessageRespones);


                    } else if (strpos($receiveText, 'กวนตีน+1') !== false) {


                        if (strpos($receiveText, 'นาถ') !== false) {

                            update_guanteen(1);
                            $textMessageRespones = 'เพิ่มคะแนนความกวนตีนของคุณนาถ ให้แล้วค่ะ';

                        } else if (strpos($receiveText, 'เบียร์') !== false) {

                            update_guanteen(2);
                            $textMessageRespones = 'เพิ่มคะแนนความกวนตีนของคุณเบียร์ ให้แล้วค่ะ';

                        } else if (strpos($receiveText, 'แคท') !== false) {

                            update_guanteen(3);
                            $textMessageRespones = 'เพิ่มคะแนนความกวนตีนของคุณแคท ให้แล้วค่ะ';

                        } else if (strpos($receiveText, 'ผึ้ง') !== false) {

                            update_guanteen(4);
                            $textMessageRespones = 'เพิ่มคะแนนความกวนตีนของคุณผึ้ง ให้แล้วค่ะ';

                        } else if (strpos($receiveText, 'ปืน') !== false) {

                            update_guanteen(5);
                            $textMessageRespones = 'เพิ่มคะแนนความกวนตีนของคุณปืน ให้แล้วค่ะ';

                        } else if (strpos($receiveText, 'วัติ') !== false) {

                            update_guanteen(6);
                            $textMessageRespones = 'เพิ่มคะแนนความกวนตีนของคุณวัติ ให้แล้วค่ะ';

                        } else if (strpos($receiveText, 'กิ่ง') !== false) {

                            update_guanteen(7);
                            $textMessageRespones = 'เพิ่มคะแนนความกวนตีนของคุณกิ่ง ให้แล้วค่ะ';

                        } else if (strpos($receiveText, 'ฟาริส') !== false) {

                            update_guanteen(8);
                            $textMessageRespones = 'เพิ่มคะแนนความกวนตีนของคุณฟาริส ให้แล้วค่ะ';

                        } else if (strpos($receiveText, 'ปิง') !== false) {

                            update_guanteen(9);
                            $textMessageRespones = 'เพิ่มคะแนนความกวนตีนของคุณปิง ให้แล้วค่ะ';

                        } else if (strpos($receiveText, 'แยม') !== false) {

                            update_guanteen(10);
                            $textMessageRespones = 'เพิ่มคะแนนความกวนตีนของคุณแยม ให้แล้วค่ะ';

                        }else if (strpos($receiveText, 'หญิง') !== false) {

                            update_guanteen(11);
                            $textMessageRespones = 'เพิ่มคะแนนความกวนตีนของคุณหญิง ให้แล้วค่ะ';

                        }else if (strpos($receiveText, 'เจ') !== false) {

                            update_guanteen(12);
                            $textMessageRespones = 'เพิ่มคะแนนความกวนตีนของคุณเจ ให้แล้วค่ะ';

                        }

                        $textMessageBuilder = new TextMessageBuilder($textMessageRespones);


                    } else if ($receiveText == 'ขอคะแนนกวนตีน') {

                        $sqlGetPoint = "SELECT * FROM guanteen ORDER BY point DESC";
                        $querytime = $db_connection->query($sqlGetPoint);
                        $index = 1;

                        $res_text = 'อันดับคะแนนความประพฤติกวนตีนจ้า' . "\n";

                        while ($row = $querytime->fetch(PDO::FETCH_ASSOC)) {
                            $nick_name = $row['name'];
                            $point = $row['point'];

                            if ($index == 1) {
                                $res_text .= 'อันดับที่ ' . $index . ' ' . $nick_name . ' ได้คะแนน = ' . $point . ' ... คุณคือแชมป์จ้า ^^' . "\n";
                                $index++;

                            } else {

                                $res_text .= 'อันดับที่ ' . $index . ' = ' . $nick_name . ' ได้คะแนน = ' . $point . "\n";
                                $index++;
                            }


                        }

                        $checkInText .= "\n อย่าทำตัวไม่น่ารักน้าา อิอิ";

                        $textMessageBuilder = new TextMessageBuilder($res_text);


                    } else if($receiveText == 'อุ๋งๆ') {

                        $originalContentUrl = 'https://watson-autobot.herokuapp.com/bot/videos/aungaung.MP4';
                        $previewImageUrl = 'https://watson-autobot.herokuapp.com/bot/images/preview.JPG';

                        $textMessageBuilder = new MessageBuilder\VideoMessageBuilder($originalContentUrl,$previewImageUrl);

                    } else if($receiveText == 'อุ๋งๆๆ') {

                        $originalContentUrl = 'https://watson-autobot.herokuapp.com/bot/videos/aungaungg.MP4';
                        $previewImageUrl = 'https://watson-autobot.herokuapp.com/bot/images/preview1.JPG';

                        $textMessageBuilder = new MessageBuilder\VideoMessageBuilder($originalContentUrl,$previewImageUrl);

                    }


                    $httpClient = new CurlHTTPClient($channel_token);
                    $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));


                    $response = $bot->replyMessage($replyToken, $textMessageBuilder);
                    break;

                case 'location':
                    $replyToken = $event['replyToken'];

                    $latigude = $event['message']['latitude'];
                    $longtigude = $event['message']['longitude'];

                    $url = 'https://maps.googleapis.com/maps/api/directions/json?origin=' . $latigude . ',' . $longtigude . '&destination=7.874986,98.363149&language=th&key=AIzaSyDpBieoJxqJvo0DBdD4-1dvDR2Z6PLHu6c';

                    $ch = curl_init();
                    // Disable SSL verification
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    // Will return the response, if false it print the response
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    // Set the url
                    curl_setopt($ch, CURLOPT_URL, $url);
                    // Execute
                    $result = curl_exec($ch);
                    // Closing
                    curl_close($ch);

                    $arrayWeather = json_decode($result, true);

                    $textMessageBuilder = "แก้วคำนวณระยะเส้นทางให้นะคะ จากจุดตำแหน่งของคุณถึง ซอฟท์แวร์ปาร์ค \n";
                    $textMessageBuilder .= "ตอนนี้คุณอยู่ที่" . $arrayWeather['routes'][0]['legs'][0]['start_address'] . "\n";
                    $textMessageBuilder .= "ระยะที่ห่างจากซอฟท์แวร์ปาร์ค " . $arrayWeather['routes'][0]['legs'][0]['distance']['text'] . "\n\n";
                    $textMessageBuilder .= "ใช้เวลาเดินทางด้วยรถโดยประมาณ " . $arrayWeather['routes'][0]['legs'][0]['duration']['text'] . "ค่ะ \n";

                    $textMessageBuilder = new TextMessageBuilder($textMessageBuilder);

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
            $respMessage = 'ขอบคุณน้าที่พาเข้ากลุ่ม จ้า 
                น้องชื่อแก้วนะคะ น้องถูกสร้างมาขึ้นเพื่อพูดคุย ขำๆ กับ พี่ๆ เลี่ยนอุดม จ้า 
                วิธีการใช้งานน้องง่ายมากๆจ้า ตอนนี้น้องมีคำสั่งตามนี้ค่ะ (ไม่ต้องพิมพ์ - นะคะ)
                - วัติเช็คอิน หรือตามชื่อของพี่ๆได้เลยจ่ะ (ตู่เช็คอิน,ปืนเช็คอิน) 
                - บริษัทอยู่ที่ไหน 
                - เที่ยงนี้กินอะไรดี 
                - ขอวาร์ปหน่อย 
                - ซื้อเสื้อหน่อย 
                - สอนแก้วพูดด้วยนะคะ สอนแก้ว[คำถาม|คำตอบ] เช่น สอนแก้ว[ใครหน้าตาดีที่สุด|คุณวัติจ้า]
            ';

            $httpClient = new CurlHTTPClient($channel_token);
            $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));

            $textMessageBuilder = new TextMessageBuilder($respMessage);
            $response = $bot->replyMessage($replyToken, $textMessageBuilder);

        }
    }
}


echo "OK";


