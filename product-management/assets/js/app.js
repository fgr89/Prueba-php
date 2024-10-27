(function() {
    'use strict';
    
    // Definir el módulo una sola vez
    angular.module('productApp', [])
        .constant('API_ENDPOINTS', {
            getProducts: 'index.php?action=getProducts',
            deleteProduct: 'index.php?action=delete',
            createProduct: 'index.php?action=create',
            editProduct: 'index.php?action=edit'
        });
})();

// assets/js/controllers/productController.js
(function() {
    'use strict';
    
    // Obtener la referencia al módulo existente
    angular.module('productApp')
        .controller('ProductController', ProductController);
    
    ProductController.$inject = ['$scope', '$http'];
    
    function ProductController($scope, $http) {
        $scope.products = [];
        
        // Cargar productos
        $scope.loadProducts = function() {
            $http.get('index.php?action=getProducts')
                .then(function(response) {
                    $scope.products = response.data.data || [];
                })
                .catch(function(error) {
                    console.error('Error loading products:', error);
                });
        };
        
        // Eliminar producto
        $scope.deleteProduct = function(code) {
            if (confirm('¿Está seguro de eliminar este producto?')) {
                $http.post('index.php?action=delete', { code: code })
                    .then(function(response) {
                        $scope.loadProducts();
                    })
                    .catch(function(error) {
                        console.error('Error deleting product:', error);
                    });
            }
        };
        
        // Inicializar
        $scope.loadProducts();
    }
})();