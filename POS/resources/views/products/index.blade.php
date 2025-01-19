@extends('layouts.master')

@section('title', 'Products')
@section('page-title', 'Products List')

@section('content')
<div>
  <!-- Search Form -->
  <form id="searchForm" method="GET" action="{{ route('products.index') }}">
    <input type="text" name="search" id="search" class="form-control mb-3" placeholder="Search products...">
  </form>

  <!-- Product Table -->
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
    <tbody id="productTableBody">
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
          <td>
            <a href="{{ route('products.view', $product->product_id) }}" class="btn btn-info">View</a>
          </td>
          <td>
            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editProductModal"
            data-id="{{ $product->product_id }}" data-sku="{{$product->sku}}" data-name="{{ $product->name }}"
            data-description="{{ $product->description }}" data-category="{{ $product->category_id }}"
            data-supplier="{{ $product->supplier_id }}" data-price="{{ $product->price }}"
            data-quantity="{{ $product->quantity }}">
            Edit
            </button>
          </td>
          <td>
            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
            data-bs-target="#deleteProductModal" data-id="{{ $product->product_id }}" data-name="{{ $product->name }}">
            Delete
            </button>
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

<script>
  document.getElementById('search').addEventListener('input', function() {
    const query = this.value;

    // Use fetch to send the query and get results
    fetch(`{{ route('products.index') }}?search=${query}`, {
      method: 'GET',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      }
    })
    .then(response => response.json())
    .then(data => {
      const tbody = document.getElementById('productTableBody');
      tbody.innerHTML = ''; // Clear previous results
      if (data.products.length > 0) {
        data.products.forEach(product => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
            <td>${product.sku}</td>
            <td>${product.name}</td>
            <td>${product.description}</td>
            <td>${product.category.name ?? 'N/A'}</td>
            <td>${product.supplier.name ?? 'N/A'}</td>
            <td>${product.price}</td>
            <td>${product.quantity}</td>
            <td>
              <a href="/products/view/${product.product_id}" class="btn btn-info">View</a>
            </td>
            <td>
              <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editProductModal"
              data-id="${product.product_id}" data-sku="${product.sku}" data-name="${product.name}"
              data-description="${product.description}" data-category="${product.category_id}"
              data-supplier="${product.supplier_id}" data-price="${product.price}"
              data-quantity="${product.quantity}">
              Edit
              </button>
            </td>
            <td>
              <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
              data-bs-target="#deleteProductModal" data-id="${product.product_id}" data-name="${product.name}">
              Delete
              </button>
            </td>
          `;
          tbody.appendChild(tr);
        });
      } else {
        tbody.innerHTML = '<tr><td colspan="10" class="text-center">No products found.</td></tr>';
      }
    })
    .catch(error => console.error('Error:', error));
  });
</script>
