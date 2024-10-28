import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { ReactiveFormsModule, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router, ActivatedRoute } from '@angular/router';
import { ProductService } from '../product.service';

interface ApiResponse {
  error: boolean;
  message: string;
}

@Component({
  selector: 'app-product-edit',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, RouterModule],
  templateUrl: './product-edit.component.html',
  styleUrl: './product-edit.component.css'
})
export class ProductEditComponent implements OnInit {
  productForm!: FormGroup;
  loading = false;
  error = '';
  successMessage = '';
  productCode: string = '';

  constructor(
    private fb: FormBuilder,
    private productService: ProductService,
    private router: Router,
    private route: ActivatedRoute
  ) {}

  ngOnInit(): void {
    this.initForm();
    this.productCode = this.route.snapshot.params['id'];
    this.loadProduct();
  }

  private initForm(): void {
    this.productForm = this.fb.group({
      code: [{ value: '', disabled: true }],
      name: ['', [Validators.required]],
      category_id: ['', [Validators.required]],
      price: [0, [Validators.required, Validators.min(0)]]
    });
  }

  private loadProduct(): void {
    if (this.productCode) {
      this.loading = true;
      this.productService.getProduct(this.productCode).subscribe({
        next: (product) => {
          this.productForm.patchValue({
            code: product.code,
            name: product.name,
            category_id: product.category_id,
            price: product.price
          });
          this.loading = false;
        },
        error: (err) => {
          this.error = 'Error al cargar el producto';
          this.loading = false;
          console.error('Error:', err);
        }
      });
    }
  }

  onSubmit(): void {
    if (this.productForm.valid) {
      this.loading = true;
      this.error = '';
      this.successMessage = '';

      const formData = {
        ...this.productForm.getRawValue(),
        price: Number(this.productForm.get('price')?.value),
        category_id: Number(this.productForm.get('category_id')?.value)
      };

      this.productService.updateProduct(this.productCode, formData).subscribe({
        next: (response: ApiResponse) => {
          this.loading = false;
          
          if (!response.error) {
            this.successMessage = response.message;
            setTimeout(() => {
              this.router.navigate(['/products']);
            }, 1500);
          } else {
            this.error = response.message;
          }
        },
        error: (err) => {
          this.loading = false;
          this.error = err.error?.message || 'Error al actualizar el producto';
          console.error('Error completo:', err);
        }
      });
    } else {
      Object.keys(this.productForm.controls).forEach(key => {
        const control = this.productForm.get(key);
        if (control?.invalid) {
          control.markAsTouched();
        }
      });
    }
  }
}