    <?php
    require_once __DIR__ . '/../config/Database.php';
    require_once __DIR__ . '/../models/Product.php';

    class ProductController {
        private $db;
        private $product;

        public function __construct() {
            try {
                // Usar getInstance() en lugar de new Database()
                $this->db = Database::getInstance();
                $this->product = new Product($this->db);
            } catch (Exception $e) {
                $this->sendError("Error de conexión: " . $e->getMessage());
                exit();
            }

            // Manejar la solicitud
            $this->handleRequest();
        }

        private function handleRequest() {
            $method = $_SERVER['REQUEST_METHOD'];
            $path = isset($_GET['path']) ? $_GET['path'] : '';
            $pathParts = explode('/', trim($path, '/'));

            try {
                switch ($method) {
                    case 'GET':
                        if (!empty($pathParts[0])) {
                            $this->show($pathParts[0]);
                        } else {
                            $this->index();
                        }
                        break;

                    case 'POST':
                        $data = json_decode(file_get_contents("php://input"), true);
                        if ($data === null) {
                            throw new Exception('Invalid JSON data');
                        }
                        $this->store($data);
                        break;

                    case 'PUT':
                        if (empty($pathParts[0])) {
                            throw new Exception('Product code is required');
                        }
                        $data = json_decode(file_get_contents("php://input"), true);
                        if ($data === null) {
                            throw new Exception('Invalid JSON data');
                        }
                        $this->update($pathParts[0], $data);
                        break;

                    case 'DELETE':
                        if (empty($pathParts[0])) {
                            throw new Exception('Product code is required');
                        }
                        $this->destroy($pathParts[0]);
                        break;

                    default:
                        throw new Exception('Method not allowed');
                }
            } catch (Exception $e) {
                $this->sendError($e->getMessage());
            }
        }

        private function index() {
            try {
                $stmt = $this->product->getAll();
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo json_encode([
                    'error' => false,
                    'data' => $products
                ]);
            } catch (Exception $e) {
                $this->sendError($e->getMessage());
            }
        }

        private function show($code) {
            try {
                $stmt = $this->product->getByCode($code);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($product) {
                    echo json_encode([
                        'error' => false,
                        'data' => $product
                    ]);
                } else {
                    $this->sendError('Product not found', 404);
                }
            } catch (Exception $e) {
                $this->sendError($e->getMessage());
            }
        }

        private function store($data) {
            try {
                if ($this->product->create($data)) {
                    http_response_code(201);
                    echo json_encode([
                        'error' => false,
                        'message' => 'Product created successfully'
                    ]);
                } else {
                    throw new Exception('Unable to create product');
                }
            } catch (Exception $e) {
                $this->sendError($e->getMessage());
            }
        }

        private function update($code, $data) {
            try {
                if ($this->product->update($code, $data)) {
                    echo json_encode([
                        'error' => false,
                        'message' => 'Product updated successfully'
                    ]);
                } else {
                    throw new Exception('Unable to update product');
                }
            } catch (Exception $e) {
                $this->sendError($e->getMessage());
            }
        }

        private function destroy($code) {
            try {
                if ($this->product->delete($code)) {
                    echo json_encode([
                        'error' => false,
                        'message' => 'Product deleted successfully'
                    ]);
                } else {
                    throw new Exception('Unable to delete product');
                }
            } catch (Exception $e) {
                $this->sendError($e->getMessage());
            }
        }

        private function sendError($message, $code = 500) {
            http_response_code($code);
            echo json_encode([
                'error' => true,
                'message' => $message
            ]);
        }
    }

    // Inicializar el controlador
    new ProductController();