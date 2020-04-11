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

$user = new User();
if ($user->isLoggedIn()) {
    ?>
    <h1>Hello <?php echo escape($user->data()->username); ?></h1>
    <ul>
        <li><a href="logout.php">LogOut</a></li>
        <li><a href="update.php">Update Profile</a></li>
    </ul>
    <?php
} else {
    ?><p>Please <a href="login.php">LogIn </a> or <a href="register.php"> Register</a></p><?php
}
//$getOne = DB::getInstance()->getOne('username', 'user', array('username', '=', 'Rick'));
//if ($getOne) {
//    echo 'Success';
//} else {
//    echo 'Failed';
//}

//$table = "user";
//$db = DB::getInstance()->getPDO()->prepare("SELECT password FROM user WHERE username = 'Rhino' ");
//$db->execute();
//$result = $db->fetch(PDO::FETCH_ASSOC);
//echo "<br>";
//echo $result["password"];

// echo Session::getSession(Config::get('session/session_name'));