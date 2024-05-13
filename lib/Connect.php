<?php

namespace AppName;

class Connect
{
    public static $routes = array();

    public static $host;
    public static $dbname;
    public static $charset;
    public static $user;
    public static $pass;

    public static $pdo;
    /**
     * Установка параметров соедениния
     */
    public static function SetParameter()
    {
        $params = parse_ini_file('database.ini');
        if ($params === false) {
            throw new \Exception("Error reading database configuration file");
        }
        self::$host = trim($params['host']);
        self::$dbname=trim($params['db']);
        self::$charset=trim($params['charset']);
        self::$user = trim($params['user']);
        self::$pass = trim($params['pass']);
    }
    /**
     * Подключение к базе данных и возврат экземпляра объекта \PDO
     */
    public static function dBconnect()
    {
         self::SetParameter();
         try {
            $dsn = "mysql:host=".self::$host.";dbname=".self::$dbname.";charset=".self::$charset;
            self::$pdo = new \PDO($dsn, self::$user, self::$pass, null);
        } catch (\PDOException $e) {
            die('Подключение не удалось: ' . $e->getMessage());
        }
    }
    /**
     * Отключение от базы данных экземпляра объекта \PDO
     */
    public static function dBDisconnect()
    {
        self::$pdo = null;
    }
}