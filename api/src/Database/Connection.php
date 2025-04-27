<?php

namespace Src\Database;

use PDO;

class Connection
{
    private static ?PDO $instance = null;

    public static function getInstance($isDocker = true): PDO
    {
        if (self::$instance === null) {
            if ($isDocker) {
                $host = 'db'; // Docker Service name
                $port = '3306';
            } else {
                $host = '127.0.0.1'; // Docker Service name
                $port = '8001';
            }
            $db = 'course_catalog';
            $user = 'test_user';
            $pass = 'test_password';
            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

            self::$instance = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        }

        return self::$instance;
    }
}