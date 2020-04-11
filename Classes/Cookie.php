<?php

class Cookie
{
    //The method checks the existence of the cookie
    public static function cookieExists($name)
    {
        return (isset($_COOKIE[$name])) ? true : false;
    }

    //This method will return the cookie
    public static function getCookie($name)
    {
        return $_COOKIE[$name];
    }

    //The Method creates a Cookie (sets the Cookie).
    public static function putCookie($name, $value, $expire)
    {
        if (setcookie($name, $value, time() + $expire, '/')) {
            return true;
        }

        return false;
    }

    //This method deletes the Cookie.
    public static function deleteCookie($name)
    {
        self::putCookie($name, '', time() - 1);
    }
}