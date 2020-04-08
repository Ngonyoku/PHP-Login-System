<?php


class User
{
    private $_db, $_data;

    public function __construct()
    {
        $this->_db = DB::getInstance();
    }

    //This method Checks if The username Exists in Database
    public function find($user = null)
    {
        if ($user) {
            #If parameter is a number then we Check Id column Otherwise we Check the username column in Database
            $field = (is_numeric($user)) ? 'id' : 'username';
            $data = $this->_db->get('users', array($field, '=', $user));

            if ($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
    }

    public function login($username, $password)
    {
        #Confirm that the Username Exists in Database
//        $user = $this->find($username);
        if ($username) {
            #Confirm that the password is Existent.
            $userPassword = "SELECT password FROM users WHERE username = '$username'";
            if (password_verify($password, $userPassword)) {
                echo "Passwords Match!";
            } else {
                echo "Incorrect Password";
            }
        }
    }

    public function create($fields = array())
    {
        if (!$this->_db->insert('user', $fields)) {
            throw new Exception("Unable To Create Account!");
        }
    }

    public function data()
    {
        return $this->_data;
    }
}