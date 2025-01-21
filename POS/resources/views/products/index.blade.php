@extends('layouts.master')

@section('title', 'Products')
@section('page-title', 'Products List')

@section('content')
<div>
  <!-- Search and Category Filter Form -->
  <form method="GET" action="{{ route('products.index') }}" class="mb-3">
    <div class="row">
      <div class="col-md-8">
        <input type="text" name="search" class="form-control" placeholder="Search products..."
          value="{{ $search ?? '' }}">
      </div>
      <div class="col-md-4">
        <select name="category_id" class="form-control">
          <option value="">All Categories</option>
          @foreach($categories as $category)
        <option value="{{ $category->category_id }}" {{ $category->category_id == $selectedCategoryId ? 'selected' : '' }}>
        {{ $category->name }}
        </option>
      @endforeach
        </select>
      </div>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Filter</button>
  </form>


  <!-- Table Container for Horizontal Scrolling -->
  <div style="overflow-x:auto;">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>SKU</th>
          <th>Name</th>
          <th>Description</th>
          <th>Category</th>
          <th>Supplier</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>View</th>
          <th>Edit</th>
          <th>Delete</th>
        </tr>
      </thead>
      <tbody>
        @forelse($products as $product)
      <tr>
        <td>{{ $product->sku }}</td>
        <td>{{ $product->name }}</td>
        <td>{{ $product->description }}</td>
        <td>{{ $product->category->name ?? 'N/A' }}</td>
        <td>{{ $product->supplier->name ?? 'N/A' }}</td>
        <td>{{ $product->price }}</td>
        <td>{{ $product->quantity }}
        @if($product->quantity < $product->low_stock_threshold)
      <span class="text-danger">Low Stock</span>
    @endif
        </td>
        <td><a href="{{ route('products.view', $product->product_id) }}" class="btn btn-info">View</a></td>
        <td><button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
          data-bs-target="#editProductModal" data-id="{{ $product->product_id }}" data-sku="{{ $product->sku }}"
          data-name="{{ $product->name }}" data-description="{{ $product->description }}"
          data-category="{{ $product->category_id }}" data-supplier="{{ $product->supplier_id }}"
          data-price="{{ $product->price }}" data-quantity="{{ $product->quantity }}">Edit</button></td>
        <td><button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
          data-bs-target="#deleteProductModal" data-id="{{ $product->product_id }}"
          data-name="{{ $product->name }}">Delete</button></td>
      </tr>
    @empty
    <tr>
      <td colspan="10">No products available.</td>
    </tr>
  @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Add Product Button -->
<div>
  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
    Add a new product
  </button>
</div>

<!-- Include Modals -->
@include('products.modals.add')
@include('products.modals.edit')
@include('products.modals.delete')

@endsection


<!-- Dynamic Modal Script -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Edit Modal Logic
    const editProductModal = document.getElementById('editProductModal');
    const editForm = document.getElementById('editProductForm');
    const editSKUInput = document.getElementById('edit-sku'); // new
    const editNameInput = document.getElementById('edit-name');
    const editDescriptionInput = document.getElementById('edit-description');
    const editCategoryInput = document.getElementById('edit-category');
    const editSupplierInput = document.getElementById('edit-supplier');
    const editPriceInput = document.getElementById('edit-price');
    const editQuantityInput = document.getElementById('edit-quantity');

    editProductModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;

      editForm.action = `/product/${button.getAttribute('data-id')}/update`;
      editSKUInput.value = button.getAttribute('data-sku'); // new
      editNameInput.value = button.getAttribute('data-name');
      editDescriptionInput.value = button.getAttribute('data-description');
      editCategoryInput.value = button.getAttribute('data-category');
      editSupplierInput.value = button.getAttribute('data-supplier');
      editPriceInput.value = button.getAttribute('data-price');
      editQuantityInput.value = button.getAttribute('data-quantity');
    });

    // Delete Modal Logic
    const deleteProductModal = document.getElementById('deleteProductModal');
    const deleteForm = document.getElementById('deleteProductForm');
    const deleteProductName = document.getElementById('delete-product-name');

    deleteProductModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;

      deleteForm.action = `/product/${button.getAttribute('data-id')}/destroy`;
      deleteProductName.textContent = button.getAttribute('data-name');
    });
  });
</script>