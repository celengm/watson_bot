<?php
date_default_timezone_set("Asia/Bangkok");
require_once '../../config/connection.php';



//$url = 'http://data.tmd.go.th/api/WeatherWarningNews/v1/?uid=u60pongniwat.w&ukey=4643b5996103347437e6c710bd14f8a9&format=json';
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

// echo '<pre>'.print_r($arrayWeather['DailyForecast']['RegionsForecast'][0]['RegionName']).'</pre>';
//$textMessageBuilder = "สภาพภูมิอากาศภาคเหนือ \n".$arrayWeather['DailyForecast']['RegionsForecast'][0]['Description'];
$textMessageBuilder = "สภาพภูมิอากาศกรุงเทพ \n" . $arrayWeather['DailyForecast']['RegionsForecast'][6]['Description'];

echo $textMessageBuilder;

/*$exampleText = '1+1=เท่าไหร่?';

if(strpos($exampleText, '=เท่าไหร่?') !== false){
    $ex_plodeArray = explode('=',$exampleText);

    $ex_plodeOperand = explode('+',$ex_plodeArray[0]);

    echo $result.'<br><br>';

}*/




//$sql = "SELECT now() - interval '-7 hours' ";
/*$sqlGetDate = "SELECT now() - interval '-7 hours' FROM users WHERE checkin_at <= now() - interval '-7 hours' AND checkin_at >= now() - interval '7 hours' ORDER BY checkin_at ASC";
$querytime = $db_connection->query($sqlGetDate);

/*echo print_r($queryFindAnswer->fetch(PDO::FETCH_ASSOC));*/

/*while($row = $queryFindAnswer->fetch(PDO::FETCH_ASSOC)){
    echo $row['nick_name'].'<br>';
}*/

/*function validNameCheckIn($receiveText,$user_line_id) {

    $dateCheckin = date('Y-m-d H:i:s');
    $nameCheckin = '';

    global $db_connection;


    if ($receiveText == 'วัติเช็คอิน') {

        global $nameCheckin;
        $nameCheckin = 'วัติ';

    } else if ($receiveText == 'ปืนเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'ปืน';
    } else if ($receiveText == 'ตู่เช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'ตู่';
    } else if ($receiveText == 'ฟลุ๊คเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'ฟลุ๊ค';
    } else if ($receiveText == 'นาถเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'นาถ';
    } else if ($receiveText == 'เบียร์เช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'เบียร์';
    } else if ($receiveText == 'ปิงเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'ปิง';
    } else if ($receiveText == 'แคทเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'แคท';
    } else if ($receiveText == 'ผึ้งเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'ผึ้ง';
    } else if ($receiveText == 'มะปรางเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'มะปราง';
    } else if ($receiveText == 'หวานเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'หวาน';
    } else if ($receiveText == 'กิ่งเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'กิ่ง';
    } else if ($receiveText == 'ฟาริสเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'ฟาริส';
    } else if ($receiveText == 'แต้งเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'แต้ง';
    } else if ($receiveText == 'แยมเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'แยม';
    } else if ($receiveText == 'แอ๋มเช็คอิน') {
        global $nameCheckin;
        $nameCheckin = 'แอ๋ม';
    }

    $sqlCheckin = "INSERT INTO users(name,id_line,checkin_at) VALUES (:myname,:my_idline,:mydate)";
    $saveAnswer = $db_connection->prepare($sqlCheckin);
    $saveAnswer->bindValue(':myname', $nameCheckin);
    $saveAnswer->bindValue(':my_idline', $user_line_id);
    $saveAnswer->bindValue(':mydate', $dateCheckin);
    $saveAnswer->execute();

    return $textMessageBuilder = new TextMessageBuilder('สวัสดีค่ะคุณ'.$nameCheckin.' เช็คอินที่เวลา ' . date('H:i'));

}

validNameCheckIn('วัติเช็คอิน','123145646');



$receiveText = 'วัติ';

$queryFindQustion = $db_connection->prepare("SELECT id FROM questions WHERE name_question = :my_question LIMIT 1");
$queryFindQustion->bindValue(':my_question', $receiveText);
$resultFindQuestion = $queryFindQustion->execute();

/*$queryFindQustion->bindValue(':my_question', $receiveText);
$queryFindQustion->execute();*/

/*if ($queryFindQustion->rowCount() > 0) {

    $qID = $queryFindQustion->fetchColumn();


    $sql = "SELECT name_answer FROM answer WHERE id_question= $qID";
    $queryFindAnswer = $db_connection->query($sql);

    /*$queryFindAnswer->bindValue(':id_question', $qID);
    $queryFindAnswer->execute();*/

    /*$array_answer = [];*/
    /*echo '<pre>';
    print_r($queryFindAnswer->fetch(PDO::FETCH_ASSOC));
    echo '</pre>';*/

    /*$exArray[] = $queryFindAnswer->fetch(PDO::FETCH_ASSOC);

    echo '<br><br>'.print_r($exArray);

    while ($row = $queryFindAnswer->fetch(PDO::FETCH_ASSOC)) {
        $array_answer[] = $row['name_answer'];

    }

    $random_keys = array_rand($array_answer, 1);
    echo $array_answer[$random_keys];


}


if ($receiveText == 'ซื้อเสื้อหน่อย') {

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

}*/