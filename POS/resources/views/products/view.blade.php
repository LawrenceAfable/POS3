@extends('layouts.master')

@section('title', 'Products')   
@section('page-title', 'Products Details')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <!-- <h2>Product Details</h2> -->

            <div class="card">
                <div class="card-header">
                    <h4>{{ $product->name }}</h4>
                </div>
                <div class="card-body">
                    <p><strong>SKU:</strong> {{ $product->sku }}</p>
                    <p><strong>Description:</strong> {{ $product->description }}</p>
                    <p><strong>Price:</strong> {{ $product->price }}</p>
                    <p><strong>Quantity in Stock:</strong> {{ $product->quantity }}</p>
                    <p><strong>Category:</strong> {{ $product->category->name ?? 'No Category' }}</p>
                    <!-- Assuming you have a category relationship -->
                    <p><strong>Supplier:</strong> {{ $product->supplier->name ?? 'No Supplier' }}</p>
                    <!-- Assuming you have a supplier relationship -->

                    @if ($product->image_url)
                        <img src="{{ asset('storage/' . $product->image_url) }}" alt="Product Image"
                            style="max-width: 300px;">
                    @else
                        <p>No image available.</p>
                    @endif
                </div>
            </div>

            <a href="{{ route('products.index') }}" class="btn btn-secondary mt-3">Back to Products</a>
        </div>
    </div>
</div>
@endsection