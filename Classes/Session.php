<?php 
/*
*-----------------------------------------------------------------------------------------------------------------------------------------------------------
* 	Code By @Ngonyoku
*----------------------------------------------------------------------------------------------------------------------------------------------------------
*/
class Session
{
	//Checks if Session Exists
	public static function sessionExist($name)
	{
		return (isset($_SESSION[$name])) ? true : false;
	}

	//Creates the session
	public static function putSession($name, $value)
	{
		return $_SESSION[$name] = $value;
	}

	//Returns pre-Existent session
	public static function getSession($name)
	{
		return $_SESSION[$name];
	}

	//Eliminates(Unsets) the session
	public static function deleteSession($name)
	{
		if (self::sessionExist($name)) {
			unset($_SESSION[$name]);
		}
	}

	public static function flash($name, $string = "")
	{
		if (self::sessionExist($name)) {
			$session = self::getSession($name);
			self::deleteSession($name);

			return $session;
		} else {
			self::putSession($name, $string);
		}
	}
}
?>