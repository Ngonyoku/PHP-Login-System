<?php


class User
{
    private $_db, $_data, $_sessionName, $_cookieName, $_isLoggedIn;

    public function __construct($user = null)
    {
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('session/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');

        if (!$user) {
            if (Session::sessionExist($this->_sessionName)) {
                $user = Session::getSession($this->_sessionName);
                if ($this->find($user)) {
                    $this->_isLoggedIn = true;
                } else {
                    //Process The LogOut
                }
            }
        } else {
            $this->find($user);
        }
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

        return false;
    }

    public function register($fields = array())
    {
        #Insert new User into Database
        if (!$this->_db->insert('user', $fields)) {
            throw new Exception("Unable To Create Account!");
        }
    }

    public function update($fields = array(), $id = null)
    {
        if (!$id && $this->isLoggedIn()) {
            $id = $this->data()->id;
        }
        if (!$this->_db->update('user', $id, $fields)) {
            throw new Exception("Failed To Update Profile");
        }
    }

    public function login($username = null, $password = null, $remember = false)
    {
        #if the parameters have not been initialised(meaning user is already logged in), we start our session.
        if (!$username && !$password && $this->exists()) {
            Session::putSession($this->_sessionName, $this->data()->id);
        } else {
            #..else we Log the user In.
            $user = $this->find($username);#Confirm that the Username Exists in Database

            if ($user) {
                $dbh = $this->_db->getPDO()->prepare("SELECT password FROM user WHERE username = ?");
                $dbh->bindValue(1, $username);
                $dbh->execute();
                $result = $dbh->fetch(PDO::FETCH_ASSOC);#Fetch the result from the database.

                #Check if the password Entered is the same as the password in Database
                if (password_verify($password, $result["password"])) {
                    #We start our session
                    Session::putSession($this->_sessionName, $this->data()->id);

                    #If "remember me" is set in the form..we do the following...
                    if ($remember) {
                        #Generate a hash key and Confirm if the user has been recorded in the "users_session" table
                        $hash = Hash::unique();
                        $hashCheck = $this->_db->get('users_session', array('user_id', '=', $this->data()->id));

                        #if session has not been recorded in Database(i.e in the "users_session" table), we record the session.
                        if (!$hashCheck->count()) {
                            $this->_db->insert('users_session', array(
                                'user_id' => $this->data()->id,
                                'hash' => $hash
                            ));
                        } else {
                            #...else if the session is recorded, the value of the hash is set to the existent hash in the database.
                            $hash = $hashCheck->first()->hash;
                        }
                        #We generate a cookie to mark the user.
                        Cookie::putCookie($this->_cookieName, $hash, Config::get('remember/cookie_expire'));
                    }
                    return true;
                }
            }
        }

        return false;
    }

    public function logout()
    {
        #Permanetly delete the Cookie and session from both the computer and Database.
        $this->_db->delete('users_session', array('user_id', '=', $this->data()->id));
        Cookie::deleteCookie($this->_cookieName);
        Session::deleteSession($this->_sessionName);
    }

    public function hasPermissions($key)
    {
        $group = $this->_db->get('groups', array('id', '=', $this->data()->group_id));
        if ($group->count()) {
            $permissions = json_decode($group->first()->permission, true);
            if ($permissions[$key] == true) {
                return true;
            }
        }

        return false;
    }

    //This method checks if there is any data which has been returned from database.
    public function exists()
    {
        return (!empty($this->data())) ? true : false;
    }

    //The method returns the status of the user if he/she is logged in or Not.
    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }

    //The Methods returns the data stored in the database.
    public function data()
    {
        return $this->_data;
    }
}