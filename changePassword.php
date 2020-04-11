<?php
require_once 'Core/init.php';

$user = new User();
if (Input::exist()) {
    if (Token::checkToken(Input::get('token'))) {
        $validate = new Validation();
        $validation = $validate->check($_POST, array(
            'currentPassword' => array('required' => true, 'min' => 2, 'max' => 50),
            'newPassword' => array('required' => true, 'min' => 2, 'max' => 50),
            'confirmPassword' => array('required' => true, 'min' => 2, 'max' => 50, 'matches' => 'newPassword')
        ));

        if ($validation->passed()) {
            if (password_verify(Input::get('currentPassword'), $user->data()->password)) {
                $newPassword = password_hash(Input::get('newPassword'), PASSWORD_DEFAULT);
                $user->update(array(
                    'password' => $newPassword
                ));
                Session::flash('Home', 'Password Updated Successfully!');
                Redirect::moveTo('index.php');
            } else {
                echo "Incorrect Password";
            }
        } else {
            displayError($validation->error());
        }
    }
}
?>
<form action="" method="post" autocomplete="off">

    <div class="field">
        <label for="currentPassword">Current Password</label>
        <input type="password" name="currentPassword" id="currentPassword">
    </div>

    <div class="field">
        <label for="newPassword">New Password</label>
        <input type="password" name="newPassword" id="newPassword">
    </div>

    <div class="field">
        <label for="confirmPassword">Confirm new Password</label>
        <input type="password" name="confirmPassword" id="confirmPassword">
    </div>
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input type="submit" value="register">
</form>


