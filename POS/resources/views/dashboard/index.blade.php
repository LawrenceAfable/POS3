@extends('layouts.master')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard Overview')

@section('content')
  <div class="row">
    <!-- Admin Panel Overview Cards -->
    <div class="col-md-3 mb-3">
      <div class="card bg-primary text-white">
        <div class="card-body">
          <h5 class="card-title">Total Users</h5>
          <p class="card-text">{{ $totalUsers }}</p>
        </div>
      </div>
    </div>

    <div class="col-md-3 mb-3">
      <div class="card bg-success text-white">
        <div class="card-body">
          <h5 class="card-title">Total Products</h5>
          <p class="card-text">{{ $totalProducts }}</p>
        </div>
      </div>
    </div>

    <div class="col-md-3 mb-3">
      <div class="card bg-info text-white">
        <div class="card-body">
          <h5 class="card-title">Total Orders</h5>
          <p class="card-text">{{ $totalOrders }}</p>
        </div>
      </div>
    </div>

    <div class="col-md-3 mb-3">
      <div class="card bg-warning text-white">
        <div class="card-body">
          <h5 class="card-title">Total Sales</h5>
          <p class="card-text">{{ number_format($totalSales, 2) }}</p>
        </div>
      </div>
    </div>

    <div class="col-md-3 mb-3">
      <div class="card bg-danger text-white">
        <div class="card-body">
          <h5 class="card-title">Total Categories</h5>
          <p class="card-text">{{ $totalCategories }}</p>
        </div>
      </div>
    </div>

    <div class="col-md-3 mb-3">
      <div class="card bg-dark text-white">
        <div class="card-body">
          <h5 class="card-title">Total Suppliers</h5>
          <p class="card-text">{{ $totalSuppliers }}</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Welcome Message -->
  <div class="card mt-4">
    <div class="card-body">
      <p>Welcome to the admin panel! You can manage users, products, orders, and view recent activities from this dashboard.</p>
    </div>
  </div>
@endsection
