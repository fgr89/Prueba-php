var app = angular.module('productApp', []);

app.controller('ProductController', function($scope, $http) {
    // Estado inicial
    $scope.products = [];
    $scope.categories = [];
    $scope.showCreateForm = false;
    $scope.newProduct = {};
    
    // Cargar productos
    $scope.loadProducts = function() {
        console.log('2. Iniciando carga de productos');
        
        $http.get('index.php?action=getProducts')
            .then(function(response) {
                console.log('3. Respuesta recibida:', response.data);
                $scope.products = response.data.data || [];
                console.log('4. Products actualizados:', $scope.products);
            })
            .catch(function(error) {
                console.error('Error en la petición:', error);
            });
    };
    
    // Cargar categorías
    $scope.loadCategories = function() {
        $http.get('index.php?action=getCategories')
            .then(function(response) {
                $scope.categories = response.data || [];
            })
            .catch(function(error) {
                console.error('Error cargando categorías:', error);
            });
    };
    
    // Mostrar formulario de creación
    $scope.showCreate = function() {
        $scope.showCreateForm = true;
        if($scope.categories.length === 0) {
            $scope.loadCategories();
        }
    };
    
    // Cancelar creación
    $scope.cancelCreate = function() {
        $scope.showCreateForm = false;
        $scope.newProduct = {};
    };
    
    // Crear producto
    $scope.createProduct = function() {
        $http.post('index.php?action=create', $scope.newProduct)
            .then(function(response) {
                alert('Producto creado exitosamente');
                $scope.showCreateForm = false;
                $scope.newProduct = {};
                $scope.loadProducts();
            })
            .catch(function(error) {
                alert('Error al crear el producto: ' + (error.data.error || 'Error desconocido'));
            });
    };
    
    // Eliminar producto
    $scope.deleteProduct = function(code) {
        if (confirm('¿Está seguro de eliminar este producto?')) {
            $http.post('index.php?action=delete', { code: code })
                .then(function(response) {
                    console.log('6. Respuesta eliminación:', response.data);
                    $scope.loadProducts();
                })
                .catch(function(error) {
                    console.error('Error eliminando:', error);
                });
        }
    };
    
    // Cargar datos iniciales
    $scope.loadProducts();
});