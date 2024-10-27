<div ng-controller="ProductController">
    <!-- Lista de Productos -->
    <div ng-show="!showCreateForm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Products</h1>
            <button ng-click="showCreate()" class="btn btn-primary">New Product</button>
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
                        <button ng-click="editProduct(product.code)" 
                                class="btn btn-sm btn-warning">Edit</button>
                        <button ng-click="deleteProduct(product.code)" 
                                class="btn btn-sm btn-danger">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Formulario de Creación -->
    <div ng-show="showCreateForm" class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h2>Create New Product</h2>
                    </div>
                    <div class="card-body">
                        <form ng-submit="createProduct()" name="productForm" novalidate>
                            <div class="mb-3">
                                <label for="code" class="form-label">Product Code</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="code" 
                                       name="code"
                                       ng-model="newProduct.code"
                                       ng-pattern="/^[A-Za-z0-9-]+$/"
                                       required>
                                <div class="invalid-feedback" 
                                     ng-show="productForm.code.$dirty && productForm.code.$invalid">
                                    Please provide a valid product code (letters, numbers, and hyphens only).
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="name" 
                                       name="name"
                                       ng-model="newProduct.name"
                                       required>
                                <div class="invalid-feedback" 
                                     ng-show="productForm.name.$dirty && productForm.name.$invalid">
                                    Please provide a product name.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-select" 
                                        id="category_id" 
                                        name="category_id"
                                        ng-model="newProduct.category_id"
                                        required>
                                    <option value="">Select a category</option>
                                    <option ng-repeat="category in categories" 
                                            value="{{category.id}}">
                                        {{category.name}}
                                    </option>
                                </select>
                                <div class="invalid-feedback" 
                                     ng-show="productForm.category_id.$dirty && productForm.category_id.$invalid">
                                    Please select a category.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           class="form-control" 
                                           id="price" 
                                           name="price"
                                           ng-model="newProduct.price"
                                           step="0.01" 
                                           min="0.01"
                                           required>
                                    <div class="invalid-feedback" 
                                         ng-show="productForm.price.$dirty && productForm.price.$invalid">
                                        Please provide a valid price greater than 0.
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="button" 
                                        class="btn btn-secondary"
                                        ng-click="cancelCreate()">Cancel</button>
                                <button type="submit" 
                                        class="btn btn-primary"
                                        ng-disabled="productForm.$invalid">Create Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>