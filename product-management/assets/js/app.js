// assets/js/app.js
var app = angular.module('productApp', []);

// assets/js/controllers/productController.js
app.controller('ProductController', function($scope, $http) {
    console.log('1. Controlador iniciado');
    $scope.products = [];
    
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
    
    $scope.deleteProduct = function(code) {
        console.log('5. Intentando eliminar producto:', code);
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

    $scope.loadProducts();
});