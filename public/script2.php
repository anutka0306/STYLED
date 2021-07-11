<?php

function print_debug($data){
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}

$user = 'root';
$pass = '';
$db = 'ch82817_fuel';

$connect = mysqli_connect('localhost', $user, $pass, $db) or die("Don't connect");


$query = mysqli_query($connect, "SELECT `code`, `id` FROM `price__brand`");

$brands_array = array();

while($row = mysqli_fetch_array($query)){
    $brands_array[] = array(trim(str_replace('-euro','',$row['code'])), $row['id']);
}
/*print_debug($brands_array);
exit();*/

$model_array = array();
$query = mysqli_query($connect, "SELECT `id`, `code`, `brand_id` FROM `price__model`");
while($row = mysqli_fetch_array($query)){
    $model_array[] = array($row['code'], $row['id'], $row['brand_id']);
}

foreach ($model_array as $key => $model) {
    foreach ($brands_array as $brand) {
        if ($brand[1] == $model[2]) {
            $model_array[$key][3] = str_replace('-euro','',$brand[0]);
            break;
        }
    }
}


$services_array = array();
$query = mysqli_query($connect, "SELECT `slug`, `id`, `price_category_id` FROM `price__services`");
$services_array = array();
while($row = mysqli_fetch_array($query)){
    $services_array[] = array($row['slug'], $row['id'], $row['price_category_id']);
}
//print_debug($services_array);
//exit();
foreach ($services_array as $service) {
    foreach ($model_array as $model) {
        $query = mysqli_query($connect, "SELECT `id` FROM `content` WHERE `path` = '{$service[0]}{$model[3]}/{$model[0]}/' AND `page_type` IS NULL ");

        if ($row = mysqli_fetch_assoc($query)) {
            $id = $row['id'];
            print_debug($id .'-'. $model[1]);
            if ($id) {
                mysqli_query($connect, "UPDATE `content` SET `page_type` = 'service', `model_id` = {$model[1]} WHERE `id`={$id}");
            }else{
                continue;
            }
        }


    }
}


