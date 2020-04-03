<?php 
/*
*-----------------------------------------------------------------------------------------------------------------------------------------------------------
* 	Code By @Ngonyoku
*----------------------------------------------------------------------------------------------------------------------------------------------------------
*/
class Input
{
	public static function existence($type = 'post')
	{
		switch ($type) {
			case 'post':
				return (empty($_POST))? false : true;
				break;
			
			case 'get':
				return (empty($_POST))? false : true;
				break;
			default:
				return false;
				break;
		}
	}

	public static function get($item)
	{
		if (isset($_POST[$item])) {
			return $_POST[$item];
		} elseif (isset($_GET[$item])) {
			return $_GET[$item];
		} 
		return '';
	}


}

?>