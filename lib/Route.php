<?php

namespace AppName;

class Route
{
    public static $routes = array();
    public static function get($route, $action)
    {
        self::$routes['GET'][$route] = $action;
    }
}
