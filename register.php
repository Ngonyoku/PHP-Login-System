<?php 

/*
*-----------------------------------------------------------------------------------------------------------------------------------------------------------
* 	Code By @Ngonyoku
*----------------------------------------------------------------------------------------------------------------------------------------------------------
*/
	require_once 'Core/init.php';

	if (Input::existence()) { #Confirm First that the form has been submitted
		if (Token::checkToken(Input::get('token'))) { #Creates a new Token If old Token is Existent
			$validate = new Validation();

			//We validate Our Input though check() method
			$validate = $validate->check($_POST, array(
				'name' => array('required' => true, 'min' => 3, 'max' => 50),
				'username' => array('required' => true, 'min' => 2, 'max' => 20, 'unique' => 'user'),
				'password' => array('required' => true, 'min' => 6),
				'confirmpassword' => array('required' => true, 'matches' => 'password')
			));

			 //we Confirm that input is Valid
			if ($validate->passed()) {
				$user = new User();
				$salt = Hash::salt(32);
				$enter = $user->create(array(
						'username' => Input::get('username'),
						'password' => Hash::make(Input::get('password')),
						'name' => Input::get('name'),
						'salt' => $salt,
						'joined' => date("Y-m-d H:i:s"),
						'groups' => 1
				));

				if (!$enter) {
					var_dump($enter);
				}
				// $salt = Hash::salt(32);
				// try {

				// 	$user->create(array(
				// 		'username' => Input::get('username'),
				// 		'password' => Hash::make(Input::get('password')),
				// 		'name' => Input::get('name'),
				// 		'salt' => $salt,
				// 		'joined' => date("Y-m-d H:i:s"),
				// 		'groups' => 1
				// 	));

				// } catch (Exception $e) {
				// 	die($e->getMessage());
				// }
			} else {
				$arrayV = $validate->_errors;
				for ($x=0; $x < count($arrayV) ; $x++) { 
					echo $arrayV[$x];
				}
			}
		}
	}
?>

<form action="" method="post" autocomplete="off">
	<div class="field">
		<label for="name">Name</label>
		<input type="text" value="<?php echo escape(Input::get('name')); ?>" name="name" id="name">
	</div>
	<div class="field">
		<label for="username">Username</label>
		<input type="text" name="username" value="<?php echo escape(Input::get('username')); ?>" id="username">
	</div>
	<div class="field">
		<label for="password">Password</label>
		<input type="password" name="password" id="password">
	</div>
	<div class="field">
		<label for="confirmpassword">Confirem Your Password</label>
		<input type="password" name="confirmpassword" id="confirmpassword">
	</div>
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
	<input type="submit" value="register">
</form>