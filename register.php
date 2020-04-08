<?php
require_once 'Core/init.php';
if (Input::exist()) {
    if (Token::checkToken(Input::get('token'))) {
        $validate = new Validation();
//        $validate->validate(Input::get('password'));
//        if ($validate->passed()) {
//            echo "Valid";
//        } else {
//            print_r($validate->error());
//        }
        $validate = $validate->check($_POST, array(
            'username' => array('required' => true, 'min' => 2, 'max' => 20, 'unique' => 'user'),
            'password' => array('required' => true, 'min' => 6),
            'confirmpassword' => array('required' => true, 'matches' => 'password'),
            'name' => array('required' => true, 'min' => 2, 'max' => 50)
        ));
        if ($validate->passed()) {
            $user = new User();
            try {
                $user->create(array(
                    'username' => Input::get('username'),
                    'password' => Hash::hashPassword(Input::get('password')),
                    'salt' => '123456789',
                    'name' => Input::get('name'),
                    'joined' => date("Y-m-d H:i:s"),
                    'group_id' => 1
                ));

                Session::flash('Home', "Registration was Successful");
                header('Location:index.php');
            } catch (Exception $e) {

            }
        } else {
            print_r($validate->error());
//            foreach ($validate->error() as $errorName => $error) {
//                echo $error . "<br>";
//            }
        }
//        if ($validate->passed()) {
//
//        } else {
//            print_r($validate->error());
//        }
    }
}
?>
<form action="" method="post" autocomplete="off">
    <div class="field">
        <label for="name">Name</label>
        <input type="text" value="<?php echo escape(Input::get('name')); ?>" name="name" id="name" required>
    </div>
    <div class="field">
        <label for="username">Username</label>
        <input type="text" name="username" value="<?php echo escape(Input::get('username')); ?>" id="username" required>
    </div>
    <div class="field">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
    </div>
    <div class="field">
        <label for="confirmpassword">Confirm Your Password</label>
        <input type="password" name="confirmpassword" id="confirmpassword" required>
    </div>
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input type="submit" value="register">
</form>

