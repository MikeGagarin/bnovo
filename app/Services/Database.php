<?php

namespace Mike\Bnovo\Services;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
    private static Database $instance;
    private PDO $connection;

    public function __construct(string $host, string $db, string $user, string $pass) {
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $this->connection = new PDO($dsn, $user, $pass);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function query(string $sql, array $params = []): PDOStatement {
        $statement = $this->connection->prepare($sql);
        $statement->execute($params);
        return $statement;
    }

    public static function instance(): static
    {
        if (!isset(self::$instance)) {
            self::$instance = new static(env('DB_HOST'), env('DB_NAME'), env('DB_USER'), env('DB_PASS'));
        }

        return self::$instance;
    }

    //костыльный метод, не оч хотелось писать механизм миграций в этом тестовом задании
    public static function init(): void
    {
        try {
            self::instance()->query('
                CREATE TABLE guest (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255),
                    last_name VARCHAR(255),
                    email VARCHAR(255) DEFAULT NULL,
                    phone VARCHAR(255),
                    country VARCHAR(255) DEFAULT NULL
                   )
            ');
        } catch (PDOException $e) {}
    }
}