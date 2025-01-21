<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Products List</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }

    .container {
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 30px;
      margin-top: 50px;
    }

    h1 {
      font-size: 2.5rem;
      font-weight: 600;
      color: #343a40;
    }

    .table th, .table td {
      vertical-align: middle;
    }

    .table {
      border-collapse: collapse;
    }

    .table-dark {
      background-color: #343a40;
      color: white;
    }

    .btn {
      font-weight: 600;
    }

    .btn-sm {
      font-size: 0.875rem;
    }

    .action-btns {
      display: flex;
      justify-content: space-evenly;
    }

    .alert {
      font-weight: 500;
    }

    .table-bordered {
      border: 1px solid #dee2e6;
    }
  </style>
</head>

<body>
  <div class="container mt-4">
    <h1>Online Store Inventory List</h1>

    {{-- Back to Home Button --}}
    <a href="{{ url('/') }}" class="btn btn-secondary mb-4">Back to Home</a>

    {{-- Table Container for Horizontal Scrolling --}}
    <div style="overflow-x:auto;">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>SKU</th>
            <th>Name</th>
            <th>Description</th>
            <th>Category</th>
            <th>Supplier</th>
            <th>Price</th>
            <th>Quantity</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($products as $product)
            <tr>
              <td>{{ $product->sku }}</td>
              <td>{{ $product->name }}</td>
              <td>{{ $product->description }}</td>
              <td>{{ $product->category->name ?? 'N/A' }}</td>
              <td>{{ $product->supplier->name ?? 'N/A' }}</td>
              <td>{{ number_format($product->price, 2) }}</td>
              <td>{{ $product->quantity }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="10" class="text-center">No products available.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
      <a href="{{ route('products.create') }}" class="btn btn-success mt-3">Add Product</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
