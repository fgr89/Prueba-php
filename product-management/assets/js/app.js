var app = angular.module('productApp', []);

app.controller('ProductController', function($scope, $http) {
    // Cargar productos
    $scope.loadProducts = function() {
        $http.get('index.php?action=getProducts')
            .then(function(response) {
                $scope.products = response.data;
            })
            .catch(function(error) {
                console.error('Error loading products:', error);
            });
    };
    
    // Eliminar producto usando AJAX
    $scope.deleteProduct = function(id) {
        if (confirm('Are you sure you want to delete this product?')) {
            $http.post('index.php?action=delete', { id: id })
                .then(function(response) {
                    // Recargar productos después de eliminar
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