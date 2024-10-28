  import { Injectable } from '@angular/core';
  import { HttpClient, HttpHeaders } from '@angular/common/http';
  import { Observable, map } from 'rxjs';

  export interface Product {
    code: string;
    name: string;
    category_id: number;
    category_name?: string;
    price: number;
    created_at?: string;
    updated_at?: string;
  }

  export interface ApiResponse<T> {
    error: boolean;
    data: T;
  }

  @Injectable({
    providedIn: 'root'
  })
  export class ProductService {
    private apiUrl = 'http://127.0.0.1:8080/product-management/backend/products';
    
    private httpOptions = {
      headers: new HttpHeaders({
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      })
    };

    constructor(private http: HttpClient) { }

    getProducts(): Observable<Product[]> {
      return this.http.get<ApiResponse<Product[]>>(this.apiUrl, this.httpOptions)
        .pipe(
          map(response => response.data)
        );
    }

    getProduct(code: string): Observable<Product> {
      return this.http.get<ApiResponse<Product>>(`${this.apiUrl}/${code}`, this.httpOptions)
        .pipe(
          map(response => response.data)
        );
    }

    createProduct(product: Product): Observable<any> {
      return this.http.post<ApiResponse<any>>(this.apiUrl, product, this.httpOptions)
        .pipe(
          map(response => response.data)
        );
    }

    updateProduct(code: string, product: Product): Observable<any> {
      return this.http.put<ApiResponse<any>>(`${this.apiUrl}/${code}`, product, this.httpOptions)
        .pipe(
          map(response => response.data)
        );
    }

    deleteProduct(code: string): Observable<any> {
      return this.http.delete<ApiResponse<any>>(`${this.apiUrl}/${code}`, this.httpOptions)
        .pipe(
          map(response => response.data)
        );
    }
  }