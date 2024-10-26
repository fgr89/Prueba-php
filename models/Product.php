<?php
class Product {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }

    
    public function getAll() {
        $stmt = $this->db->query(
            "SELECT p.*, c.name as category_name 
             FROM products p 
             JOIN categories c ON p.category_id = c.id 
             ORDER BY p.created_at DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function find($code) {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name as category_name 
             FROM products p 
             JOIN categories c ON p.category_id = c.id 
             WHERE p.code = ?"
        );
        $stmt->execute([$code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        // Verificar si el código ya existe
        if ($this->codeExists($data['code'])) {
            throw new Exception("El código del producto ya existe");
        }

        $stmt = $this->db->prepare(
            "INSERT INTO products (code, name, category_id, price, created_at) 
             VALUES (?, ?, ?, ?, NOW())"
        );
        return $stmt->execute([
            $data['code'],
            $data['name'],
            $data['category_id'],
            $data['price']
        ]);
    }
    
    public function update($code, $data) {
        // Si el código está cambiando, verificar que el nuevo no exista
        if ($data['code'] !== $code && $this->codeExists($data['code'])) {
            throw new Exception("El nuevo código del producto ya existe");
        }

        $stmt = $this->db->prepare(
            "UPDATE products 
             SET code = ?, name = ?, category_id = ?, price = ?, updated_at = NOW() 
             WHERE code = ?"
        );
        return $stmt->execute([
            $data['code'],
            $data['name'],
            $data['category_id'],
            $data['price'],
            $code
        ]);
    }
    
    public function delete($code) {
        $stmt = $this->db->prepare("DELETE FROM products WHERE code = ?");
        return $stmt->execute([$code]);
    }
    
    public function codeExists($code) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM products WHERE code = ?");
        $stmt->execute([$code]);
        return $stmt->fetchColumn() > 0;
    }
}