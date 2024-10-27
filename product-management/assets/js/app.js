// assets/js/app.js
var app = angular.module('productApp', []);

// assets/js/controllers/productController.js
app.controller('ProductController', function($scope, $http, $window) {
    // Habilitar depuración
    $scope.debug = true;
    
    // Cargar productos desde el servidor
    $scope.loadProducts = function() {
        $http.get('index.php?action=getProducts')
            .then(function(response) {
                $scope.products = response.data;
                if ($scope.debug) {
                    console.log('Products loaded:', $scope.products);
                }
            })
            .catch(function(error) {
                console.error('Error loading products:', error);
            });
    };
    
    // Función para eliminar producto
    $scope.deleteProduct = function(code) {
        if ($window.confirm('¿Está seguro de eliminar este producto?')) {
            $http.post('index.php?action=delete', { code: code })
                .then(function(response) {
                    if ($scope.debug) {
                        console.log('Delete response:', response);
                    }
                    $scope.loadProducts();
                })
                .catch(function(error) {
                    console.error('Error deleting product:', error);
                });
        }
    };
    
    // Inicializar cargando productos
    $scope.loadProducts();
});