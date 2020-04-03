<?php 
/*
*-----------------------------------------------------------------------------------------------------------------------------------------------------------
* 	Code By @Ngonyoku
*----------------------------------------------------------------------------------------------------------------------------------------------------------
*/

session_start();
$GLOBALS['Config'] = array( #This global Variable Contains Key Elemets used accross the Entire Website
	'mysql' => array('host' => '127.0.0.1', 'username' => 'root', 'password' => '', 'db' => 'DBOOP'),
	'remember' => array('cookie_name' => 'hash', 'cookie_value' => '', 'cookie_expire' => 86400),
	'session' => array('session_name' => 'user' , 'token_name' => 'token')
);

	#We autoload all of the Class Files
spl_autoload_register(function ($class){ 
	require_once 'Classes/'.$class.'.php';
});

require_once 'Functions/Sanitize.php';
?>