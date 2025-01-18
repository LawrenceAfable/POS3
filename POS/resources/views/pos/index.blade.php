@extends('layouts.master')

@section('title', 'Point of Sale')
@section('page-title', 'Point of Sale')

@section('content')
<div class="container mt-5">
  <div class="row">

    <!-- Product List Section -->
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h3>Product List</h3>
        </div>
        <div class="card-body">
          <input type="text" class="form-control mb-3" placeholder="Search products...">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th></th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <!-- Loop through products here -->
              @forelse($products as $product)
              <tr>
               <td>
                  @if ($product->image_url)
                    <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}" width="50" height="50">
                  @else
                    <p>No image</p>
                  @endif
                </td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->quantity }}</td>
                <td>
                  <input type="number" class="form-control" min="1" value="1">
                </td>
                <td>
                  <button class="btn btn-primary">Add to Cart</button>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center">No products available.</td>
              </tr>
              @endforelse
              <!-- Loop end here -->

            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Cart Section -->
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h3>Cart</h3>
        </div>
        <div class="card-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Subtotal</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <!-- Loop through cart items here -->
            </tbody>
          </table>
          <div class="mt-3">
            <h4>Total: <!-- Display total amount --></h4>
            <button class="btn btn-success btn-block">Checkout</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection