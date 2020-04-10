<?php


class User
{
    private $_db, $_data, $_sessionName;

    public function __construct()
    {
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('session/session_name');
    }

    //This method Checks if The username Exists in Database
    public function find($user = null)
    {
        if ($user) {
            #If parameter is a number then we Check Id column Otherwise we Check the username column in Database
            $field = (is_numeric($user)) ? 'id' : 'username';
            $data = $this->_db->get('user', array($field, '=', $user));

            if ($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
    }

    public function login($username, $password)
    {
        #Confirm that the Username Exists in Database
        $user = $this->find($username);
        if ($user) {
            $dbh = $this->_db->getPDO()->prepare("SELECT password FROM user WHERE username = ?");
            $dbh->bindValue(1, $username);
            $dbh->execute();
            $result = $dbh->fetch(PDO::FETCH_ASSOC);

            #Check if the password Entered is the same as the password in Database
            if (password_verify($password, $result["password"])) {
                Session::putSession($this->_sessionName, $this->data()->id);
                return true;
            }
        }
        return false;
    }

    public function register($fields = array())
    {
        #Insert new User into Database
        if (!$this->_db->insert('user', $fields)) {
            throw new Exception("Unable To Create Account!");
        }
    }

    public function data()
    {
        return $this->_data;
    }
}