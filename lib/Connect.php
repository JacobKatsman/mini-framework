<?php

namespace AppName;

class Connect
{
    public static $pdo;
    /**
     * Подключение к базе данных и возврат экземпляра объекта \PDO
     */
    public static function dBconnect()
    {
        // чтение параметров в файле конфигурации ini
        $params = parse_ini_file('database.ini');
        if ($params === false) {
            throw new \Exception("Error reading database configuration file");
        }
        try {
            $dsn = "mysql:host=".trim($params['host']).";dbname=".trim($params['db']).";charset=".trim($params['charset']);
            self::$pdo = new \PDO($dsn, trim($params['user']), trim($params['pass']), null);
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