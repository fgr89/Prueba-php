var app = angular.module('productApp', []);

// assets/js/controllers/productController.js
app.controller('ProductController', function($scope) {
    // Los productos vienen de PHP
    $scope.products = <?php echo json_encode($products); ?>;
    
    $scope.deleteProduct = function(id) {
        if (confirm('Are you sure you want to delete this product?')) {
            window.location.href = 'index.php?action=delete&id=' + id;
        }
    };
});