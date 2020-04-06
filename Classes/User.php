<?php

class User
{
	private $_db;

	public function __construct($user = null)
	{
		$this->_db = DB::getInstance();	
	}

	public function create($fields = array())
	{
		if (!$this->_db->insertData('user',$fields)) {
			throw new Exception("Failed To Create Account!");
		}
	}
}