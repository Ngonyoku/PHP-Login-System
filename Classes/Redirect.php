<?php

/**
 *  Written By @Ngonyoku
 *
 * PHP Version 7
 * MySql database
 *___________________________________________________________________________________________________________________
 *          Redirect Class
 * The class is associated with methods that assist in smooth navigation between Activities.
 * ------------------------------------------------------------------------------------------------------------------
 *___________________________________________________________________________________________________________________
 * */
class Redirect
{
    public static function moveTo($location = null)
    {
        if ($location) {
            if (is_numeric($location)) {
                switch ($location) {
                    case 404:
                        header('HTTP/1.0 404 Not Found');
                        include "Includes/Errors/404.php";
                        exit();
                }
            }

            header('Location:' . $location);
            exit();
        }
    }
}