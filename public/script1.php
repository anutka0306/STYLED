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


foreach ($model_array as $model) {
 /*   $query = mysqli_query($connect, "SELECT `id` FROM `content` WHERE `path` = '/remont/{$model[3]}/{$model[0]}/'");


    if ($row = mysqli_fetch_assoc($query)) {
        $id = $row['id'];
        //print_debug($id);
    } else {*/
        $query = mysqli_query($connect, "SELECT `id` FROM `content` WHERE `path` = '/remont/remont-akpp-{$model[3]}/{$model[0]}/'");
        if ($row = mysqli_fetch_assoc($query)) {
            $id = $row['id'];
            print_debug($id);
        }
   /* }*/

    if($id){
        mysqli_query($connect, "UPDATE `content` SET `page_type` = 'model', `model_id` = {$model[1]} WHERE `id`={$id}");
    }
}




exit();

/*$query = mysqli_query($connect, "SELECT `slug`, `id`, `price_category_id` FROM `price__services`");
$services_array = array();
while($row = mysqli_fetch_array($query)){
    $services_array[] = array($row['slug'], $row['id'], $row['price_category_id']);
}*/
/*print_debug($services_array);
exit();*/





foreach($brands_array as $brand){
    $query = mysqli_query($connect, "SELECT `id` FROM `content` WHERE `path` = '/remont/{$brand[0]}/' AND  `page_type` = 'brand'");
    if($row = mysqli_fetch_assoc($query)){
        $id = $row['id'];
    }else{
        $query = mysqli_query($connect, "SELECT `id` FROM `content` WHERE `path` = '/remont/remont-akpp-{$brand[0]}/' AND  `page_type` = 'brand'");
        if($row = mysqli_fetch_assoc($query)){
            $id = $row['id'];
        }
    }


    if($id) {
        foreach ($services_array as $service) {
            $query = mysqli_query($connect, "SELECT * FROM `content` WHERE `path` = '{$service[0]}{$brand[0]}/'");
            if ($row = mysqli_fetch_assoc($query)) {
                /* print_debug($row);
                 continue;*/
                $update_query = mysqli_query($connect, "UPDATE `content` SET `page_type` = 'service', `parent_id` = {$id}, `service_id` = {$service[1]}, `price_category_id` = {$service[2]} WHERE `id` = {$row['id']}");
                continue;
            } else {
                echo 'other';
                continue;
                // $query1 = mysqli_query($connect, "SELECT * FROM `content` WHERE `path` = '/remont/{$brand[0]}/'");
                /* if ($row = mysqli_fetch_array($query1)) {
                     $update_query = mysqli_query($connect, "UPDATE `content` SET `page_type` = 'brand', `brand_id` = {$brand[1]} WHERE `id` = {$row['id']}");
                 }*/
            }
        }//end services cicle
    }//end if
    else{
        print_debug($brand[0]);
        continue;
    }
}//end brands cicle

