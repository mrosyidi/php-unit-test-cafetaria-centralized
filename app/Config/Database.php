<?php 

    namespace Cafetaria\Config;

    class Database 
    {
        public static function getConnection(): \PDO 
        {
            $host = "localhost";
            $port = 3306;
            $database = "php_database_cafetaria";
            $username = "root";
            $password = "12345";

            $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";

            try 
            {
                $pdo = new \PDO($dsn, $username, $password, [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                ]);
                return $pdo;
            }catch (\PDOException $e)
            {
                throw new \RuntimeException("Database connection failed: " . $e->getMessage());
            }
        }
    }