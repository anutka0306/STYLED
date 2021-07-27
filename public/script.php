<?php
function print_debug($data){
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}

$user = 'anutkaay_styled';
$pass = 'RAYq50H&';
$db = 'anutkaay_styled';

$connect = mysqli_connect('localhost', $user, $pass, $db) or die("Don't connect");

//Бренды
$query = mysqli_query($connect, "SELECT * FROM `content` WHERE `page_type` = 'brand'");

while($row = mysqli_fetch_array($query)){
    $query1 = mysqli_query($connect, "SELECT `name` FROM `price__brand` WHERE `id` =".$row['brand_id']);
    while($row1 = mysqli_fetch_array($query1)){
        $brands_array[] = array($row['id'], $row1['name']);
    }

}

foreach ($brands_array as $brand){
    mysqli_query($connect, "UPDATE `content` SET `meta_description` = '&#11088; &#11088; &#11088; &#11088; &#11088; Обслуживание {$brand[1]} в Москве. Расположены у метро Отрадное! Детейлинг студия Styled.' WHERE `id` = {$brand[0]}") or die('ERROR');
}

//Модели
$query = mysqli_query($connect, "SELECT * FROM `content` WHERE `page_type` = 'model'");

while($row = mysqli_fetch_array($query)){
    $query1 = mysqli_query($connect, "SELECT `name`, `brand_id` FROM `price__model` WHERE `id` =".$row['model_id']);
    while($row1 = mysqli_fetch_array($query1)){
        $query2 = mysqli_query($connect, "SELECT `name` FROM `price__brand` WHERE `id` =".$row1['brand_id']);
        while ($row2 = mysqli_fetch_array($query2)) {
            $models_array[] = array($row['id'], $row1['name'], $row2['name']);
        }
    }

}

foreach ($models_array as $model){
    mysqli_query($connect, "UPDATE `content` SET `meta_description` = '&#11088; &#11088; &#11088; &#11088; &#11088; Обслуживание {$model[2]} {$model[1]} в Москве. Расположены у метро Отрадное! Детейлинг студия Styled.' WHERE `id` = {$model[0]}") or die('ERROR');
}

//Сервисы

$query = mysqli_query($connect, "SELECT * FROM `content` WHERE `page_type` = 'service'");

while($row = mysqli_fetch_array($query)){
    mysqli_query($connect, "UPDATE `content` SET `meta_description` = '&#11088; &#11088; &#11088; &#11088; &#11088; {$row['h1']}. Расположены у метро Отрадное! Детейлинг студия Styled.' WHERE `id` = {$row['id']}") or die('ERROR');

}


