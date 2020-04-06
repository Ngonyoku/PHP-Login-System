<?php

require_once 'Core/init.php';
//$db = DB::getInstance()->insert('user', array(
//    'username' => 'Morio',
//    'password' => 'password',
//    'salt' => '123456789',
//    'name' => 'Rick Man',
//    'joined' => date("Y-m-d H:i:s"),
//    'group_id' => 1
//));
//if ($db) {
//    echo "Succeeded";
//} else {
//    echo "Failed";
//}

//$up = DB::getInstance()->update('user',5, array('name' => 'Timmy Turner'));
//if ($up) {
//    echo "Succeeded";
//} else {
//    echo "Failed";
//}

if (Session::sessionExist('Home')) {
    echo '<p>' . Session::flash('Home') . '</p>';
}

