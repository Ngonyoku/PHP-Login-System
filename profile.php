<?php
require_once 'Core/init.php';

if (!$username = Input::get('user')) {
    Redirect::moveTo('index.php');
} else {
    $user = new User($username);
    if (!$user->exists()) {
        Redirect::moveTo(404);
    } else {
        $name = $user->data()->name;
        $userName = $user->data()->username;
        ?>
        <h4>Name : <?php echo escape($name); ?></h4>
        <p>username : <?php echo escape($userName); ?></p>
        <?php
    }
}
?>
