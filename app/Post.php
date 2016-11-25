<?php
namespace App;

class Post extends Singleton
{
    private static $post = null;

    public static function init()
    {
        self::getInstance();
        self::$post = $_POST;
    }

    public static function exists($key)
    {
        if (!empty(self::$post[$key])) {
            return isset(self::$post[$key]);
        }
        return false;
    }

    public static function get($key)
    {
        if (!empty(self::$post[$key])) {
            return self::$post[$key];
        }
        return null;
    }

    public static function getAll()
    {
        if (!empty(self::$post)) {
            return self::$post;
        }
        return null;
    }
}
