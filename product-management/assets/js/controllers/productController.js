productApp.controller('ProductController', function($scope, $http) {
    // Cargar productos
    $scope.loadProducts = function() {
        $http.get('index.php?action=getProducts')
            .then(function(response) {
                if (response.data.success) {
                    $scope.products = response.data.data;
                } else {
                    console.error('Error:', response.data.error);
                }
            })
            .catch(function(error) {
                console.error('Error loading products:', error);
            });
    };
    
    // Eliminar producto
    $scope.deleteProduct = function(code) {
        if (confirm('¿Está seguro de eliminar este producto?')) {
            $http({
                method: 'POST',
                url: 'index.php?action=delete',
                data: { code: code },
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function(response) {
                if (response.data.success) {
                    $scope.loadProducts();
                } else {
                    console.error('Error:', response.data.error);
                }
            })
            .catch(function(error) {
                console.error('Error deleting product:', error);
            });
        }
    };

    // Cargar productos al iniciar
    $scope.loadProducts();
});