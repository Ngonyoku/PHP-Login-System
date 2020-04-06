<?php


class Token
{
    public static function generate()
    {
        return Session::putSession(Config::get('session/token_name'), md5(uniqid()));
    }

    public static function checkToken($token)
    {
        $tokenName = Config::get('session/token_name');
        if (Session::sessionExist($tokenName) && $token == Session::getSession($tokenName)) {
            Session::deleteSession($tokenName);
            return true;
        }
        return false;
    }
}