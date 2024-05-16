<?php


namespace App\Classes;

class generalFunctions
{

    public function __construct()
    {
    }

    public function get_TypeUrl()
    {
        if ($_SERVER['SERVER_NAME'] == 'localhost') {
            return '/lui';
        } else {
            return '';
        }
    }

    public function get_TypeEnv()
    {
        if ($_SERVER['SERVER_NAME'] == 'localhost') {
            return 'dev';
        } else {
            return 'production';
        }
    }

    public function get_color($color, $default)
    {
        if(!isset($color)){
            return $default;
        }
        if ($color == '' || $color == 'default') {
            return $default;
        } else {
            return $color;
        }
    }


}
