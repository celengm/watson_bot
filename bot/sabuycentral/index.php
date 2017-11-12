<?php
date_default_timezone_set("Asia/Bangkok");
require_once '../../config/connection.php';

$receiveText = 'วัติ';

$queryFindQustion = $db_connection->prepare("SELECT * FROM questions WHERE name_question= :my_question");
$queryFindQustion->bindValue(':my_question', $receiveText);
$queryFindQustion->execute();



/*$queryFindQustion->bindValue(':my_question', $receiveText);
$queryFindQustion->execute();*/

if ($queryFindQustion->rowCount() > 0) {

    $qID = $queryFindQustion->fetchColumn();


    $sql = "SELECT * FROM answer WHERE id_question= $qID";
    $queryFindAnswer = $db_connection->query($sql);
    /*$queryFindAnswer->bindValue(':id_question', $qID);
    $queryFindAnswer->execute();*/

    $array_answer = [];
    /*echo '<pre>';
    print_r($queryFindAnswer->fetch(PDO::FETCH_ASSOC));
    echo '</pre>';*/

    while ($row = $queryFindAnswer->fetch(PDO::FETCH_ASSOC)) {

        $array_answer[] = $row['name_answer'];

        /*$textMessageBuilder = new TextMessageBuilder($answer_send . '');*/

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

}