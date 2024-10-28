<?php
class Category {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Obtener todas las categorías para usarlas en el formulario de selección de productos
    public function getAll() {
        $stmt = $this->db->query("SELECT id, name FROM categories ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar una categoría específica por ID (si necesitas mostrar el nombre en la vista de productos)
    public function find($id) {
        $stmt = $this->db->prepare("SELECT id, name FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}