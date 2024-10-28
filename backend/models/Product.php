<?php
class Product {
    private $conn;
    private $table_name = "products";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT p.*, c.name as category_name 
                 FROM " . $this->table_name . " p
                 LEFT JOIN categories c ON p.category_id = c.id
                 ORDER BY p.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getByCode($code) {
        $query = "SELECT p.*, c.name as category_name 
                 FROM " . $this->table_name . " p
                 LEFT JOIN categories c ON p.category_id = c.id
                 WHERE p.code = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $code);
        $stmt->execute();
        return $stmt;
    }

    public function create($data) {
        try {
            // Validar datos requeridos
            if(empty($data['code']) || empty($data['name']) || 
               !isset($data['category_id']) || !isset($data['price'])) {
                throw new Exception("Faltan campos requeridos");
            }

            $query = "INSERT INTO " . $this->table_name . " 
                     (code, name, category_id, price) 
                     VALUES (?, ?, ?, ?)";

            $stmt = $this->conn->prepare($query);
            
            // Convertir price a float y category_id a int
            $price = (float) $data['price'];
            $category_id = (int) $data['category_id'];

            return $stmt->execute([
                $data['code'],
                $data['name'],
                $category_id,
                $price
            ]);
        } catch (PDOException $e) {
            error_log("Error en create: " . $e->getMessage());
            throw new Exception("Error al crear el producto: " . $e->getMessage());
        }
    }

    public function update($code, $data) {
        try {
            // Validar datos requeridos
            if(empty($data['name']) || !isset($data['category_id']) || !isset($data['price'])) {
                throw new Exception("Faltan campos requeridos");
            }

            $query = "UPDATE " . $this->table_name . " 
                     SET name = ?, category_id = ?, price = ?, updated_at = CURRENT_TIMESTAMP
                     WHERE code = ?";

            $stmt = $this->conn->prepare($query);

            // Convertir price a float y category_id a int
            $price = (float) $data['price'];
            $category_id = (int) $data['category_id'];
            
            return $stmt->execute([
                $data['name'],
                $category_id,
                $price,
                $code
            ]);
        } catch (PDOException $e) {
            error_log("Error en update: " . $e->getMessage());
            throw new Exception("Error al actualizar el producto: " . $e->getMessage());
        }
    }

    public function delete($code) {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE code = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$code]);
        } catch (PDOException $e) {
            error_log("Error en delete: " . $e->getMessage());
            throw new Exception("Error al eliminar el producto: " . $e->getMessage());
        }
    }

    public function exists($code) {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE code = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$code]);
        return $stmt->fetchColumn() > 0;
    }

    public function validateData($data, $isUpdate = false) {
        $errors = [];

        if (!$isUpdate && empty($data['code'])) {
            $errors[] = "El código es requerido";
        }
        if (empty($data['name'])) {
            $errors[] = "El nombre es requerido";
        }
        if (!isset($data['category_id']) || $data['category_id'] === '') {
            $errors[] = "La categoría es requerida";
        }
        if (!isset($data['price']) || $data['price'] === '') {
            $errors[] = "El precio es requerido";
        }
        if (isset($data['price']) && $data['price'] < 0) {
            $errors[] = "El precio no puede ser negativo";
        }

        return $errors;
    }
}