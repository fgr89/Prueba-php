<?php
session_start();

// Cargar configuración de base de datos primero
require_once __DIR__ . '/config/Database.php';

// Luego los modelos
require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/models/Category.php';

// Finalmente los controladores
require_once __DIR__ . '/controllers/ProductController.php';

// Crear instancia del controlador usando la base de datos remota
$controller = new ProductController();

// Manejar las rutas
$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'index':
        $controller->index();
        break;
    case 'create':
        $controller->create();
        break;
    default:
        $controller->index();
}