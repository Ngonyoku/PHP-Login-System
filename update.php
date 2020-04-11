<?php
require_once 'Core/init.php';
$user = new User();
$name = $user->data()->name;
if (Input::exist()) {
    if (Token::checkToken(Input::get('token'))) {
        $validate = new Validation();
        $validation = $validate->check($_POST, array(
            'name' => array('required' => true, 'min' => 2, 'max' => 50)
        ));

        if ($validation->passed()) {
            try {
                $user->update(array(
                    'name' => Input::get('name')
                ));

                Session::flash('Home', "Update Successful");
                Redirect::moveTo('index.php');
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } else {
            foreach ($validation->error() as $error) {
                echo $error . '<br>';
            }
        }
    }
}
?>
<form action="" method="post">
    <div class="field">
        <label for="name">
            Name:
            <input type="text" name="name" value="<?php echo escape($name); ?>">
            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
            <input type="submit" name="submit" value="Update">
        </label>
    </div>
</form>
