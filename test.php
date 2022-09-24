<?php
namespace Dankedev;

class Plugin{
    protected static $varible;

    public static function init(){
        return self::$varible;
    }
}