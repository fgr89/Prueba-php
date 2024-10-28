<?php
class Database {
    private $connection;
    private static $instance;

    private function __construct() {
        $config = [
            'host' => 'sql12.freesqldatabase.com',
            'db'   => 'sql12740970',
            'user' => 'sql12740970',
            'pass' => 'NL4ACW1vUS'
        ];
        try {
            $this->connection = new PDO(
                "mysql:host={$config['host']};dbname={$config['db']}",
                $config['user'],
                $config['pass']
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance->connection;
    }
}