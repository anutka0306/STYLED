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

function getAllBrands($connect){
    $query = mysqli_query($connect, "SELECT `code`, `id` FROM `price__brand`");
    $brands_array = array();
    while($row = mysqli_fetch_array($query)){
        $brands_array[] = array(trim(str_replace('-euro','',$row['code'])), $row['id']);
    }
    return $brands_array;
}

function getChevroleteModels($connect){
    $query = mysqli_query($connect, "SELECT `id`, `code` FROM `price__model` WHERE `brand_id` = 10");
    $models_array = array();
    while($row = mysqli_fetch_array($query)){
        $models_array[] = array(trim(str_replace('-euro','',$row['code'])), $row['id']);
    }
    return $models_array;
}

function getAllServices($connect){
    $services_array = array();
    $query = mysqli_query($connect, "SELECT `slug`, `id`, `price_category_id` FROM `price__services`");
    $services_array = array();
    while($row = mysqli_fetch_array($query)){
        $services_array[] = array($row['slug'], $row['id'], $row['price_category_id']);
    }
    return $services_array;
}

function getContentForCorrection($connect, $models, $services){
    $content_array = array();
    foreach ($services as $service){
        foreach ($models as $model){
           // echo $service[0]."chevrolet/".$model[0];
            $query = mysqli_query($connect, "SELECT `id` FROM `content` WHERE `path` = '{$service[0]}chevrolet/{$model[0]}/' AND `page_type` = 'servicewithout'");
            if($row = mysqli_fetch_array($query)){
                //var_dump($row[0]);
                mysqli_query($connect, "UPDATE `content` SET `page_type` = 'service', `service_id` = {$service[1]}, `price_category_id` = {$service[2]}, `model_id` = {$model[1]} WHERE `id` = {$row[0]}") or die('Error');
                $content_array[] = $row[0];
            }
        }
    }
    return $content_array;
}


$models = getChevroleteModels($connect);
$services = getAllServices($connect);
$content = getContentForCorrection($connect, $models, $services);


print_debug($content);
exit();

/*$pending = array();

foreach ($content as $page){
    //проверили на страницы сервисов - их не оказалось
    foreach ($services as $service){
        $part = $service[0];
        if(strstr($page[1], $part)){
            $page['service_id'] = $service[1];
            $page['service_slug'] = $service[0];
            $page['price_category_id'] = $service[2];
            $pending[] = $page;
            break;
        }
    }
}
$result = array();
if(!empty($pending)) {
    foreach ($pending as $item) {
       foreach ($models as $model){
           $part = $model[3].'/'.$model[0];

           if(strstr($item[1], $part)){
               //print_debug($model[0]);
               if($item['service_slug'].$model[3].'/'.$model[0].'/' == $item[1]) {
                   $item['model_id'] = $model[1];
                   $result[] = $item;
                   break;
               }
           }
       }
    }
}

if(!empty($result)){
    foreach ($result as $resultItem){
        if(mysqli_query($connect, "UPDATE `content` SET `page_type` = 'service', `service_id` = {$resultItem['service_id']}, `price_category_id` = {$resultItem['price_category_id']}, `model_id` = {$resultItem['model_id']} WHERE `id` = {$resultItem[0]}") or die('Error')){
            print_debug('done');
        }
    }
}
print_debug($result);*/


// проверяем на страницы бренд/модель
/*foreach ($content as $page) {
    foreach ($models as $model){
        $part = '/remont/'.$model[3].'/'.$model[0];
        $part1 = '/remont/remont-akpp-'.$model[3].'/'.$model[0];

        if(strstr($page[1], $part) || strstr($page[1], $part1)){
            if(mysqli_query($connect, "UPDATE `content` SET `page_type` = 'model', `model_id` = {$model[1]} WHERE `id` = {$page[0]}") or die('Error')){
                print_debug('done');
            }
        }
    }
}*/

//ставим типы для отзывов и статей для остальных ставлю servicewithout
/*foreach ($content as $page) {
    if(strstr($page[1], 'otzivyi')){
        if(mysqli_query($connect, "UPDATE `content` SET `page_type` = 'otzivyi' WHERE `id` = {$page[0]}") or die('Error')){
            print_debug('done');
        }
    }
    elseif (strstr($page[1], 'stati')){
        if(mysqli_query($connect, "UPDATE `content` SET `page_type` = 'stati' WHERE `id` = {$page[0]}") or die('Error')){
            print_debug('done');
        }
    }
    elseif (strstr($page[1], 'prodazha-akpp')){
        if(mysqli_query($connect, "UPDATE `content` SET `page_type` = 'simple' WHERE `id` = {$page[0]}") or die('Error')){
            print_debug('done');
        }
    }else{
        if(mysqli_query($connect, "UPDATE `content` SET `page_type` = 'servicewithout' WHERE `id` = {$page[0]}") or die('Error')){
            print_debug('done');
        }
    }
}*/






