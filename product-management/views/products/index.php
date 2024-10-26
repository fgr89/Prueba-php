<div ng-controller="ProductController">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Products</h1>
        <a href="index.php?action=create" class="btn btn-primary">New Product</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="product in products">
                <td>{{product.code}}</td>
                <td>{{product.name}}</td>
                <td>{{product.category_name}}</td>
                <td>{{product.price | currency}}</td>
                <td>
                    <a href="index.php?action=edit&id={{product.id}}" 
                       class="btn btn-sm btn-warning">Edit</a>
                    <button ng-click="deleteProduct(product.id)" 
                            class="btn btn-sm btn-danger">Delete</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>