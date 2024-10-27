var app = angular.module('productApp', []);

// Controlador para manejar la lógica del producto
app.controller('ProductController', function($scope, $http) {
    // Cargar productos desde el servidor
    $scope.loadProducts = function() {
        $http.get('index.php?action=getProducts')
            .then(function(response) {
                // Asignar la lista de productos al scope
                $scope.products = response.data;
            })
            .catch(function(error) {
                console.error('Error loading products:', error);
            });
    };

    // Función para eliminar un producto usando AJAX
    $scope.deleteProduct = function(code) {
        if (confirm('¿Está seguro de eliminar este producto?')) {
            // Hacer una solicitud POST para eliminar el producto
            $http.post('index.php?action=delete', { code: code })
                .then(function(response) {
                    // Recargar la lista de productos después de eliminar
                    $scope.loadProducts();
                })
                .catch(function(error) {
                    console.error('Error deleting product:', error);
                });
        }
    };

    // Cargar productos al iniciar el controlador
    $scope.loadProducts();
});