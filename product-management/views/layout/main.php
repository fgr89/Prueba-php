<!DOCTYPE html>
<html ng-app="productApp">
<head>
    <title>Product Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.8.2/angular.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script src="assets/js/controllers/productController.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Product Management</a>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($content)) echo $content; ?>
    </div>
</body>
</html>