<?php


class User
{
    private $_db;

    public function __construct()
    {
        $this->_db = DB::getInstance();
    }

    public function create($fields = array())
    {
        if (!$this->_db->insert('user', $fields)) {
            throw new Exception("Unable To Create Account!");
        }
    }
}