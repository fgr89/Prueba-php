<?php
class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        try {
            // Configuración única para la base de datos remota
            $config = [
                'host' => 'sql12.freesqldatabase.com',
                'db'   => 'sql12740970',
                'user' => 'sql12740970',
                'pass' => 'NL4ACW1vUS'
            ];
            
            $dsn = "mysql:host={$config['host']};dbname={$config['db']};charset=utf8mb4";
            
            $this->connection = new PDO($dsn, $config['user'], $config['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
            
        } catch(PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->connection;
    }
}