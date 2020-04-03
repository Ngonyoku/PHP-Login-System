<?php 
/*
*-----------------------------------------------------------------------------------------------------------------------------------------------------------
* 	Code By @Ngonyoku
*----------------------------------------------------------------------------------------------------------------------------------------------------------
*/

class Token
{
	#generates a Token and assigns it a value
	public static function generate()
	{	
		return Session::putSession(Config::get('session/token_name'), md5(uniqid()));
	}

	public static function checkToken($token)
	{
		#Creates a new Token if The old one Is still Existent.
		$tokenName = Config::get('session/token_name');
		if (Session::sessionExist($tokenName) && $token == Session::getSession($tokenName)) {
			Session::deleteSession($tokenName);
			return true;
		}
		return false;
	}
}
?>