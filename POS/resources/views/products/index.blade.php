@extends('layouts.master')

@section('title', 'Products')
@section('page-title', 'Products List')

@section('content')
<!-- Search and Category Filter Form -->
<form method="GET" action="{{ route('products.index') }}" class="mb-3">
  <div class="row">
    <div class="col-md-4">
      <input type="text" name="search" class="form-control" placeholder="Search products..."
        value="{{ $search ?? '' }}">
    </div>

    <!-- Category Search -->
    <div class="col-md-2">
      <select name="category_id" class="form-control">
        <option value="">All Categories</option>
        @foreach($categories as $category)
      <option value="{{ $category->category_id }}" {{ $category->category_id == $selectedCategoryId ? 'selected' : '' }}>
        {{ $category->name }}
      </option>
    @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <!-- Filter button on the left -->
      <button type="submit" class="btn btn-primary">Filter</button>
    </div>

    <!-- Quantity Filter -->
    <div class="col-md-2">
      <select name="quantity_filter" class="form-control">
        <option value="">All Quantities</option>
        <option value="0" {{ $quantityFilter === '0' ? 'selected' : '' }}>Out of Stock (0)</option>
        <option value="1" {{ $quantityFilter === '1' ? 'selected' : '' }}>In Stock (1 or more)</option>
      </select>
    </div>
    <div class="col-md-2">
      <!-- Filter button for quantity -->
      <button type="submit" class="btn btn-primary">Filter</button>
    </div>

  </div>

  <hr>

  <div class="row mt-2">
    <div>
      <!-- Add Product and Generate Reports buttons on the right -->
      <div class="d-flex gap-2">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
          +
        </button>
        <a href="{{ route('productReport', [
                  'search' => $search ?? 'null',
                  'category_id' => $selectedCategoryId ?? 'null',
                  'quantity_filter' => $quantityFilter ?? 'null',
                  'sort' => $sort ?? 'asc',
                ]) }}" class="btn btn-info">
          Generate Report
        </a>
      </div>
    </div>
  </div>

</form>

<!-- Table -->
<div style="overflow-x:auto;">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>SKU</th>
        <th>Name</th>
        <th>Description</th>
        <th>Category</th>
        <th>Supplier</th>
        <th>
          <!-- Price Header with Sorting -->
          <a href="{{ route('products.index', ['search' => $search, 'category_id' => $selectedCategoryId, 'sort' => $sort === 'desc' ? 'asc' : 'desc',]) }}"
            class="text-decoration-none">
            Price
            @if ($sort === 'desc')
        <i class="fas fa-arrow-down"></i> <!-- Down arrow for descending -->
      @else
    <i class="fas fa-arrow-up"></i> <!-- Up arrow for ascending -->
  @endif
          </a>
        </th>
        <th>Quantity</th>
        <th>Action</th>
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
      <td>
        {{ $product->quantity }}
        @if($product->quantity < $product->low_stock_threshold)
      <span class="text-danger">Low Stock</span>
    @endif
      </td>
      <td>
        <!-- Action buttons grouped together -->
        <div class="d-flex gap-2">
        <a href="{{ route('products.view', $product->product_id) }}" class="btn btn-info btn-sm">View</a>
        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
          data-bs-target="#editProductModal" data-id="{{ $product->product_id }}" data-sku="{{ $product->sku }}"
          data-name="{{ $product->name }}" data-description="{{ $product->description }}"
          data-category="{{ $product->category_id }}" data-supplier="{{ $product->supplier_id }}"
          data-price="{{ $product->price }}" data-quantity="{{ $product->quantity }}">
          Edit
        </button>
        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
          data-bs-target="#deleteProductModal" data-id="{{ $product->product_id }}"
          data-name="{{ $product->name }}">
          Delete
        </button>
        </div>
      </td>
      </tr>
    @empty
      <tr>
      <td colspan="8">No products available.</td>
      </tr>
    @endforelse
    </tbody>
  </table>
</div>

<!-- Pagination Links -->
<div>
  {{ $products->appends([
  'search' => $search,
  'category_id' => $selectedCategoryId,
  'sort' => $sort,
  'quantity_filter' => $quantityFilter,
])->links('pagination::bootstrap-5') }}
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