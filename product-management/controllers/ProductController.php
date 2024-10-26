<?php
/**
 * Controlador para manejar todas las operaciones relacionadas con productos
 */
class ProductController {
    /** @var Product Instancia del modelo de productos */
    private $productModel;
    
    /** @var Category Instancia del modelo de categorías */
    private $categoryModel;
    
    /**
     * Constructor del controlador
     * Inicializa las instancias de los modelos necesarios
     */
    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }
    
    /**
     * Método para mostrar la lista de productos
     * Carga la vista principal con todos los productos y categorías
     */
    public function index() {
        $products = $this->productModel->getAll();
        $categories = $this->categoryModel->getAll();
        
        // Carga las vistas necesarias
        require 'views/layout/main.php';
        require 'views/products/index.php';
    }
    
    /**
     * Método para crear un nuevo producto
     * Maneja tanto la visualización del formulario como el procesamiento del mismo
     */
    public function create() {
        // Verifica si es una petición POST (envío del formulario)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Valida los datos del formulario
                $data = $this->validateProductData($_POST);
                
                // Intenta crear el producto
                if ($this->productModel->create($data)) {
                    $_SESSION['success'] = "Producto creado exitosamente";
                    header('Location: index.php');
                    exit;
                }
            } catch (Exception $e) {
                // Captura cualquier error durante el proceso
                $_SESSION['error'] = $e->getMessage();
            }
        }
        
        // Obtiene las categorías para el formulario
        $categories = $this->categoryModel->getAll();
        
        // Carga las vistas del formulario
        require 'views/layout/main.php';
        require 'views/products/create.php';
    }
    
    /**
     * Método para editar un producto existente
     * @param string $code Código del producto a editar
     */
    public function edit($code) {
        // Verifica si es una petición POST (envío del formulario)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Valida los datos del formulario
                $data = $this->validateProductData($_POST);
                
                // Intenta actualizar el producto
                if ($this->productModel->update($code, $data)) {
                    $_SESSION['success'] = "Producto actualizado exitosamente";
                    header('Location: index.php');
                    exit;
                }
            } catch (Exception $e) {
                // Captura cualquier error durante el proceso
                $_SESSION['error'] = $e->getMessage();
            }
        }
        
        // Busca el producto a editar
        $product = $this->productModel->find($code);
        
        // Verifica si el producto existe
        if (!$product) {
            $_SESSION['error'] = "Producto no encontrado";
            header('Location: index.php');
            exit;
        }
        
        // Obtiene las categorías para el formulario
        $categories = $this->categoryModel->getAll();
        
        // Carga las vistas del formulario de edición
        require 'views/layout/main.php';
        require 'views/products/edit.php';
    }
    
    /**
     * Método para eliminar un producto
     * @param string $code Código del producto a eliminar
     */
    public function delete($code) {
        try {
            // Intenta eliminar el producto
            if ($this->productModel->delete($code)) {
                $_SESSION['success'] = "Producto eliminado exitosamente";
            }
        } catch (Exception $e) {
            // Captura cualquier error durante el proceso
            $_SESSION['error'] = $e->getMessage();
        }
        
        // Redirecciona a la página principal
        header('Location: index.php');
        exit;
    }
    
    /**
     * Método privado para validar los datos del producto
     * @param array $data Datos del producto a validar
     * @return array Datos validados
     * @throws Exception Si hay errores de validación
     */
    private function validateProductData($data) {
        $errors = [];
        
        // Valida el código del producto
        if (empty($data['code'])) {
            $errors[] = "El código es requerido";
        } elseif (!preg_match('/^[A-Za-z0-9-]+$/', $data['code'])) {
            $errors[] = "El código solo puede contener letras, números y guiones";
        }
        
        // Valida el nombre del producto
        if (empty($data['name'])) {
            $errors[] = "El nombre es requerido";
        }
        
        // Valida la categoría
        if (empty($data['category_id'])) {
            $errors[] = "La categoría es requerida";
        }
        
        // Valida el precio
        if (!isset($data['price']) || $data['price'] <= 0) {
            $errors[] = "El precio debe ser mayor que 0";
        }
        
        // Si hay errores, lanza una excepción
        if (!empty($errors)) {
            throw new Exception(implode("<br>", $errors));
        }
        
        return $data;
    }
}