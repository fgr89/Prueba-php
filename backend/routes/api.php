<?php
require_once __DIR__ . '/../controllers/ProductController.php';

$controller = new ProductController();

// Obtener y limpiar la ruta
$request_uri = $_SERVER['REQUEST_URI'];
$base_path = '/product-management/backend';
$path = str_replace($base_path, '', $request_uri);
$path_parts = explode('/', trim($path, '/'));

// Debug logs
error_log('Request URI: ' . $request_uri);
error_log('Path after base: ' . $path);
error_log('Path parts: ' . print_r($path_parts, true));

$method = $_SERVER['REQUEST_METHOD'];

try {
    // Verificar si estamos en la ruta de productos
    if (count($path_parts) > 0 && $path_parts[0] === 'products') {
        switch ($method) {
            case 'GET':
                if (isset($path_parts[1])) {
                    // GET /products/{code}
                    $controller->show($path_parts[1]);
                } else {
                    // GET /products
                    $controller->index();
                }
                break;

            case 'POST':
                // POST /products
                $data = json_decode(file_get_contents("php://input"), true);
                if ($data === null) {
                    throw new Exception('Invalid JSON data');
                }
                $controller->store($data);
                break;

            case 'PUT':
                // PUT /products/{code}
                if (!isset($path_parts[1])) {
                    throw new Exception('Product code is required');
                }
                $data = json_decode(file_get_contents("php://input"), true);
                if ($data === null) {
                    throw new Exception('Invalid JSON data');
                }
                $controller->update($path_parts[1], $data);
                break;

            case 'DELETE':
                // DELETE /products/{code}
                if (!isset($path_parts[1])) {
                    throw new Exception('Product code is required');
                }
                $controller->destroy($path_parts[1]);
                break;

            default:
                throw new Exception('Method not allowed');
        }
    } else {
        throw new Exception('Route not found');
    }
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}