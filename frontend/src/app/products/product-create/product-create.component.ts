import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { ReactiveFormsModule, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { ProductService } from '../product.service';

interface ApiResponse {
  error: boolean;
  message: string;
}

@Component({
  selector: 'app-product-create',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, RouterModule],
  templateUrl: './product-create.component.html',
  styleUrl: './product-create.component.css'
})
export class ProductCreateComponent implements OnInit {
  productForm!: FormGroup;
  loading = false;
  error = '';
  successMessage = '';  // Agregamos esta propiedad

  constructor(
    private fb: FormBuilder,
    private productService: ProductService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.initForm();
  }

  private initForm(): void {
    this.productForm = this.fb.group({
      code: ['', [Validators.required]],
      name: ['', [Validators.required]],
      category_id: ['', [Validators.required]],
      price: [0, [Validators.required, Validators.min(0)]]
    });
  }

  onSubmit(): void {
    if (this.productForm.valid) {
      this.loading = true;
      this.error = '';
      this.successMessage = '';
  
      const formData = {
        ...this.productForm.value,
        price: Number(this.productForm.value.price),
        category_id: Number(this.productForm.value.category_id)
      };
  
      this.productService.createProduct(formData).subscribe({
        next: (response: ApiResponse) => {
          this.loading = false;
  
          if (!response.error) {
            this.successMessage = response.message;
            // Usamos un alert para mostrar el mensaje
            alert(this.successMessage); // Muestra el mensaje de éxito
  
            // Redirigir a la lista de productos después de un breve retraso
            setTimeout(() => {
              this.router.navigate(['products']); // Cambié aquí a ['products']
            }, 1500);
          } else {
            this.error = response.message;
          }
        },
        error: (err) => {
          this.loading = false;
          this.error = err.error?.message || 'Error al crear el producto';
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