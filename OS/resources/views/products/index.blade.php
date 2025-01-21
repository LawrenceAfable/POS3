<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
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

        .alert {
            font-weight: 500;
        }

        .btn {
            font-weight: 600;
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
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">API Fetch Inventory List</h1>

        {{-- Display errors if any --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Success or error messages --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- Action buttons --}}
        <div class="d-flex justify-content-between mb-4">
            {{-- Back to Home Button --}}
            <a href="{{ url('/') }}" class="btn btn-secondary">Back to Home</a>

            {{-- Save to Database Button --}}
            <a href="{{ route('saveFromApi') }}" class="btn btn-primary">Save Products to Database</a>
        </div>

        {{-- Display products if available --}}
        @if (!empty($products) && count($products) > 0)
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>SKU</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Supplier</th>
                        <th>Price</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product['sku'] }}</td>
                            <td>{{ $product['name'] }}</td>
                            <td>{{ $product['category']['name'] ?? 'N/A' }}</td>
                            <td>{{ $product['supplier']['name'] ?? 'N/A' }}</td>
                            <td>{{ $product['price'] }}</td>
                            <td>{{ $product['quantity'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info">
                No products found.
            </div>
        @endif
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
