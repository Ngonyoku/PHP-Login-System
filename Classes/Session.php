<?php


class Session
{
    //This method Checks if a session exists.
    public static function sessionExist($name)
    {
        return (isset($_SESSION[$name])) ? true : false;
    }

    //This method sets a session.
    public static function putSession($name, $value)
    {
        return $_SESSION[$name] = $value;
    }

    //The method Returns the Session specified.
    public static function getSession($name)
    {
        return $_SESSION[$name];
    }

    //This method unsets the session specified provided that the session Exists.
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