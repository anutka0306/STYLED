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


/*print_debug($model_array);
exit();*/




//для брендов
/*foreach ($services_array as $service) {
    foreach ($brands_array as $brand) {
        if($brand[0] == 'volkswagen') {
            $query = mysqli_query($connect, "SELECT `id` FROM `content` WHERE `path` = '{$service[0]}{$brand[0]}/' AND `page_type` IS NULL ");

            if ($row = mysqli_fetch_assoc($query)) {
                $id = $row['id'];
                print_debug($id . '-' . $brand[0]);
                if ($id) {
                    mysqli_query($connect, "UPDATE `content` SET `page_type` = 'service', `service_id` = {$service[1]}, `price_category_id` = {$service[2]} WHERE `id`={$id}");
                } else {
                    continue;
                }
            }
        }

    }
}*/

//добавление самой модели
/*foreach ($model_array as $model){

        $query = mysqli_query($connect, "SELECT `id` FROM `content` WHERE `path` = '/remont/{$model[3]}/{$model[0]}/' AND `model_id` IS NULL ");
        echo $model[3].'/'.$model[0].'/';
        if ($row = mysqli_fetch_assoc($query)) {
            $id = $row['id'];
            print_debug($id . '-' . $model[1]);
            if ($id) {
                mysqli_query($connect, "UPDATE `content` SET `page_type` = 'model', `model_id`= {$model[1]} WHERE `id`={$id}");
            } else {
                continue;
            }
        }
}*/

// для моделей

foreach ($services_array as $service) {
    foreach ($model_array as $model) {

            $query = mysqli_query($connect, "SELECT `id` FROM `content` WHERE `path` = '".$service[0].$model[3]."/".$model[0]."/"."' AND `page_type` IS NULL");
            if($model[3] == 'volvo') {
                echo $service[0] . $model[3] . '/' . $model[0] . '/<br/>';
            }
            if ($row = mysqli_fetch_assoc($query)) {
                $id = $row['id'];
                //print_debug($id . '-' . $model[0]);
                print_debug($row);
                /*if ($id) {
                    mysqli_query($connect, "UPDATE `content` SET `page_type` = 'service', `service_id` = {$service[1]}, `price_category_id` = {$service[2]}, `model_id`= {$model[1]} WHERE `id`={$id}");
                } else {
                    continue;
                }*/
            }
        }

    }



/*foreach ($services_array as $service) {
    foreach ($model_array as $model) {
        $query = mysqli_query($connect, "SELECT `id` FROM `content` WHERE `path` = '{$service[0]}{$model[3]}/{$model[0]}/' AND `service_id` IS NULL ");

        if ($row = mysqli_fetch_assoc($query)) {
            $id = $row['id'];
            print_debug($id .'-'. $model[1]);
            if ($id) {
                mysqli_query($connect, "UPDATE `content` SET `service_id` = {$service[1]}, `price_category_id` = {$service[2]} WHERE `id`={$id}");
            }else{
                continue;
            }
        }


    }
}*/


