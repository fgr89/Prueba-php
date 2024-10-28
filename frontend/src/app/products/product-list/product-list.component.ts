// product-list.component.ts
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ProductService } from '../product.service';

@Component({
  selector: 'app-product-list',
  templateUrl: './product-list.component.html',
  styleUrls: ['./product-list.component.css']
})
export class ProductListComponent implements OnInit {
  products: any[] = [];
  loading = false;
  error = '';

  constructor(
    private productService: ProductService,
    private router: Router
  ) { }

  ngOnInit(): void {
    this.loadProducts();
  }

  loadProducts(): void {
    this.loading = true;
    this.error = '';

    this.productService.getProducts().subscribe({
      next: (data) => {
        this.products = data;
        this.loading = false;
      },
      error: (err) => {
        this.error = 'Error al cargar los productos: ' + err.message;
        this.loading = false;
      }
    });
  }

  deleteProduct(code: string): void {
    if (confirm('¿Está seguro de eliminar este producto?')) {
      this.productService.deleteProduct(code).subscribe({
        next: () => {
          this.products = this.products.filter(p => p.code !== code);
        },
        error: (err) => {
          this.error = 'Error al eliminar el producto: ' + err.message;
        }
      });
    }
  }
}

// products.module.ts (asegúrate que este archivo tenga estas importaciones)
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';  // Importante!

import { ProductsRoutingModule } from '../products-routing.module';

@NgModule({
  declarations: [
    ProductListComponent
  ],
  imports: [
    CommonModule,  // Esta importación es crucial
    ProductsRoutingModule
  ]
})
export class ProductsModule { }