@extends('layouts.master')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard Overview')

@section('content')
<div class="row">
  <!-- Admin Panel Overview Cards -->
  <div class="col-md-3 mb-3">
    <div class="card bg-primary text-white shadow-sm">
      <div class="card-body d-flex align-items-center">
        <i class="bi bi-people-fill fs-2 me-3"></i>
        <div>
          <h6 class="card-title mb-0">Total Users</h6>
          <p class="card-text fw-bold fs-5">{{ $totalUsers }}</p>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3 mb-3">
    <div class="card bg-success text-white shadow-sm">
      <div class="card-body d-flex align-items-center">
        <i class="bi bi-box-seam fs-2 me-3"></i>
        <div>
          <h6 class="card-title mb-0">Total Products</h6>
          <p class="card-text fw-bold fs-5">{{ $totalProducts }}</p>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3 mb-3">
    <div class="card bg-info text-white shadow-sm">
      <div class="card-body d-flex align-items-center">
        <i class="bi bi-cart-fill fs-2 me-3"></i>
        <div>
          <h6 class="card-title mb-0">Total Orders</h6>
          <p class="card-text fw-bold fs-5">{{ $totalOrders }}</p>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3 mb-3">
    <div class="card bg-warning text-white shadow-sm">
      <div class="card-body d-flex align-items-center">
        <i class="bi bi-cash-stack fs-2 me-3"></i>
        <div>
          <h6 class="card-title mb-0">Total Sales</h6>
          <p class="card-text fw-bold fs-5">{{ number_format($totalSales, 2) }}</p>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3 mb-3">
    <div class="card bg-danger text-white shadow-sm">
      <div class="card-body d-flex align-items-center">
        <i class="bi bi-tags-fill fs-2 me-3"></i>
        <div>
          <h6 class="card-title mb-0">Total Categories</h6>
          <p class="card-text fw-bold fs-5">{{ $totalCategories }}</p>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3 mb-3">
    <div class="card bg-dark text-white shadow-sm">
      <div class="card-body d-flex align-items-center">
        <i class="bi bi-truck fs-2 me-3"></i>
        <div>
          <h6 class="card-title mb-0">Total Suppliers</h6>
          <p class="card-text fw-bold fs-5">{{ $totalSuppliers }}</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Sales Comparison (Line Chart) and Product Stock (Bar Chart) in One Row -->
<div class="row">
  <div class="col-md-6 mt-2">
    <div class="card shadow-sm">
      <div class="card-header">
        <h5>Sales Comparison</h5>
      </div>
      <div class="card-body">
        <canvas id="salesChart" height="180"></canvas> <!-- Reduced height -->
      </div>
    </div>
  </div>

  <div class="col-md-6 mt-2">
    <div class="card shadow-sm">
      <div class="card-header">
        <h5>Product Stock Levels</h5>
      </div>
      <div class="card-body">
        <canvas id="productChart" height="180"></canvas> <!-- Reduced height -->
      </div>
    </div>
  </div>
</div>

<!-- Categories Distribution (Pie Chart) -->
<div class="col-md-5 mt-4">
  <div class="card shadow-sm">
    <div class="card-header">
      <h5>Categories Distribution</h5>
    </div>
    <div class="card-body">
      <canvas id="categoryChart" width="300" height="200"></canvas> <!-- Set smaller width and height -->
    </div>
  </div>
</div>

<!-- Welcome Message -->
<div class="col-md-12 mt-4">
  <div class="card">
    <div class="card-body">
      <p>Welcome to the admin panel! You can manage users, products, orders, and view recent activities from this
        dashboard.</p>
    </div>
  </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Bar Chart for Product Stock Levels
  const productCtx = document.getElementById('productChart').getContext('2d');
  const productChart = new Chart(productCtx, {
    type: 'bar',
    data: {
      labels: @json($productData['labels']),
      datasets: [{
        label: 'Stock',
        data: @json($productData['data']),
        backgroundColor: 'rgba(75, 192, 192, 0.6)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      },
      plugins: {
        legend: {
          labels: {
            font: {
              size: 10 // Reduce font size for labels
            }
          }
        }
      }
    }
  });

  // Pie Chart for Categories Distribution
  const categoryCtx = document.getElementById('categoryChart').getContext('2d');
  const categoryChart = new Chart(categoryCtx, {
    type: 'pie',
    data: {
      labels: @json($categoryData['labels']),
      datasets: [{
        data: @json($categoryData['data']),
        backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6c757d', '#17a2b8']
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
          labels: {
            font: {
              size: 10 // Reduce font size for labels
            }
          }
        }
      }
    }
  });

  // Line Chart for Sales Comparison
  const salesCtx = document.getElementById('salesChart').getContext('2d');
  const salesChart = new Chart(salesCtx, {
    type: 'line',
    data: {
      labels: @json($salesData['labels']),
      datasets: [{
        label: 'Sales ($)',
        data: @json($salesData['data']),
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 2,
        fill: true,
        tension: 0.4
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      },
      plugins: {
        legend: {
          labels: {
            font: {
              size: 10 // Reduce font size for labels
            }
          }
        }
      }
    }
  });
</script>
@endsection