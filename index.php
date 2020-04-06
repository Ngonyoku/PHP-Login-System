<?php 
	require_once 'Core/init.php';
// $object = DB::getInstance()->insertData('user', array(
// 	'username' => 'Morio',
// 	'password' => 'MorioPass',
// 	'salt' => 'salt'
// ));
// if ($object) {
// 	echo "Success";
// } else {
// 	echo "Error";
// }
// $userUpdate = DB::getInstance()->updateData('user',2, array('name' => 'Morio'));
// if ($userUpdate) {
// 	echo "Success";
// }
	if (Session::sessionExist('Success')) {
		echo Session::flash('Success');
	}
 ?>