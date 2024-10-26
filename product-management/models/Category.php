<?php
class Category {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAll() {
        return $this->db->query("SELECT * FROM categories ORDER BY name")
                       ->fetchAll(PDO::FETCH_ASSOC);
    }
}