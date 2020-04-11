<?php
require_once 'Core/init.php';
$username = Input::get('username');
$password = Input::get('password');
if (Input::exist()) {
    if (Token::checkToken(Input::get('token'))) {
        $validate = new Validation();
        $validate->check($_POST, array(
            'username' => array('required' => true),
            'password' => array('required' => true)
        ));

        if ($validate->passed()) {
            $user = new User();
            $remember = (Input::get('remember') === 'on') ? true : false;
            $user->login($username, $password, $remember);
            if ($user) {
                Redirect::moveTo("index.php");
            }
        } else {
            print_r($validate->error());
        }
    }
}
?>

<form action="" method="post" autocomplete="off">
    <div class="field">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="<?php echo escape($username); ?>">
    </div>
    <div class="field">
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
    </div>

    <div class="field">
        <label for="rememberMe">
            <input type="checkbox" name="remember" id="rememberMe"> Remember Me
        </label>
    </div>

    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input type="submit" value="register">
</form>