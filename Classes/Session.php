<?php


class Session
{
    public static function sessionExist($name)
    {
        return (isset($_SESSION[$name])) ? true : false;
    }

    public static function putSession($name, $value)
    {
        return $_SESSION[$name] = $value;
    }

    public static function getSession($name)
    {
        return $_SESSION[$name];
    }

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