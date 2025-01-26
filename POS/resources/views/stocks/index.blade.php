@extends('layouts.master')

@section('title', 'Stock')
@section('page-title', 'Stock Management')

@section('content')
@include('components.alert')

<!-- Restock Side Panel -->
<div class="row">
    <div class="col-md-9">
        <!-- Table -->
        <div style="overflow-x:auto;">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Name</th>
                        <th>Created By</th>
                        <th>Updated By</th>
                        <!-- <th>Deleted By</th> -->
                        <th>Quantity</th>
                        <!-- <th>Action</th> -->
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td>
                            {{ $product->created_by_name ?? 'N/A' }} <!-- Display created by user's name -->
                            <br>
                            <small class="text-muted">{{ $product->created_by_role ?? 'N/A' }}</small> <!-- Display role -->
                        </td>
                        <td>
                            {{ $product->updated_by_name ?? 'N/A' }} <!-- Display updated by user's name -->
                            <br>
                            <small class="text-muted">{{ $product->updated_by_role ?? 'N/A' }}</small> <!-- Display role -->
                        </td>
                        <!-- <td>
                            {{ $product->deleted_by_name ?? 'N/A' }} 
                            <br>
                            <small class="text-muted">{{ $product->deleted_by_role ?? 'N/A' }}</small> 
                        </td> -->
                        <td>
                            {{ $product->quantity }}
                            @if($product->quantity < $product->low_stock_threshold) <!-- Check low stock threshold -->
                                <span class="text-danger">Low Stock</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">No products available.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Restock Side Panel -->
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Restock Product</h5>
            </div>
            <div class="card-body">
                <!-- Search Bar -->
                <div class="mb-3">
                    <input type="text" id="restockSearch" class="form-control" placeholder="Search product by SKU or name...">
                </div>

                <!-- Product List -->
                <div id="restockProductList" style="max-height: 300px; overflow-y: auto;">
                    @foreach($products as $product)
                        <div class="restock-product-item mb-2" data-id="{{ $product->product_id }}" data-name="{{ $product->product_name }}" data-sku="{{ $product->sku }}">
                            <strong>{{ $product->sku }}</strong> - {{ $product->product_name }}
                            <button class="btn btn-sm btn-primary float-end restock-add-btn">Add</button>
                        </div>
                    @endforeach
                </div>

                <!-- Restock Form -->
                <form id="restockForm" action="{{ route('stocks.restock') }}" method="POST" class="mt-3">
                    @csrf
                    <div id="restockSelectedProducts">
                        <!-- Selected products will appear here -->
                    </div>
                    <button type="submit" class="btn btn-success w-100">Restock</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Restock Functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('restockSearch');
        const productList = document.getElementById('restockProductList');
        const selectedProducts = document.getElementById('restockSelectedProducts');

        // Search functionality
        searchInput.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            const productItems = productList.querySelectorAll('.restock-product-item');

            productItems.forEach(item => {
                const productName = item.getAttribute('data-name').toLowerCase();
                const productSKU = item.getAttribute('data-sku').toLowerCase();

                if (productName.includes(searchTerm) || productSKU.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Add product to restock list
        productList.addEventListener('click', function (e) {
            if (e.target.classList.contains('restock-add-btn')) {
                const productItem = e.target.closest('.restock-product-item');
                const productId = productItem.getAttribute('data-id');
                const productName = productItem.getAttribute('data-name');
                const productSKU = productItem.getAttribute('data-sku');

                // Check if the product is already selected
                if (!document.querySelector(`#restockSelectedProducts input[value="${productId}"]`)) {
                    const inputField = `
                        <div class="mb-2">
                            <label>${productSKU} - ${productName}</label>
                            <input type="number" name="products[${productId}][quantity]" class="form-control" placeholder="Quantity" required>
                            <input type="hidden" name="products[${productId}][product_id]" value="${productId}">
                        </div>
                    `;
                    selectedProducts.insertAdjacentHTML('beforeend', inputField);
                }
            }
        });
    });
</script>

@endsection