<!DOCTYPE html>
<html>

<head>
  <title>{{ config('app.name') }}</title>
  <style>
    body {
      font-family: 'Courier New', Courier, monospace;
      font-size: 14px;
      margin: 0;
      padding: 20px;
    }

    .header {
      text-align: center;
      margin-bottom: 20px;
    }

    .header h1 {
      color: orange;
      font-size: 24px;
      margin: 0;
    }

    .header p {
      margin: 5px 0;
      font-size: 14px;
    }

    .order-details {
      margin-bottom: 20px;
    }

    .order-details h3 {
      font-size: 18px;
      margin: 10px 0;
    }

    .section {
      margin-bottom: 20px;
    }

    .section h2 {
      font-size: 16px;
      margin: 10px 0;
      border-bottom: 1px solid #ddd;
      padding-bottom: 5px;
    }

    .section p {
      margin: 5px 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    th,
    td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }

    .totals {
      margin-bottom: 20px;
    }

    .totals p {
      margin: 5px 0;
    }

    .footer {
      text-align: center;
      margin-top: 20px;
      font-size: 12px;
      color: #777;
    }
  </style>
</head>

<body>
  <div class="header">
    <h1>{{ config('app.name') }}</h1>
    <p>Product Report</p>
    <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
  </div>

  <div class="order-details">
    <h3>Order No: {{ $order->order_id }}</h3>
  </div>

  <div class="section">
    <h2>Customer Information</h2>
    <p><strong>Customer Name:</strong> {{ $order->customer_name }}</p>
    <p><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($order->order_date)->format('F j, Y') }}</p>
  </div>

  <div class="section">
    <h2>Payment Information</h2>
    <p><strong>Amount Paid:</strong> {{ number_format($order->amount_paid, 2) }}</p>
    <p><strong>Change:</strong> {{ number_format($order->change, 2) }}</p>
    <p><strong>Payment Method:</strong> {{ $order->payment_method }}</p>
  </div>

  <div class="section">
    <h2>Products Purchased</h2>
    <table>
      <thead>
        <tr>
          <th>Product Name</th>
          <th>Quantity</th>
          <th>Price</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        @foreach(explode(', ', $order->products_brought) as $product)
          @php
            // Extract product details from the concatenated string
            preg_match('/(.*) \(Qty: (\d+) x (.*)\)/', $product, $matches);
            $productName = $matches[1] ?? '';
            $quantity = $matches[2] ?? 0;
            $price = $matches[3] ?? 0;
            $total = $quantity * $price;
          @endphp
          <tr>
            <td>{{ $productName }}</td>
            <td>{{ $quantity }}</td>
            <td>{{ number_format($price, 2) }}</td>
            <td>{{ number_format($total, 2) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="section totals">
    <h2>Order Totals</h2>
    <p><strong>Total Amount:</strong> {{ number_format($order->total_amount, 2) }}</p>
    <p><strong>Discount:</strong> {{ number_format($order->discount, 2) }}</p>
    <p><strong>Tax:</strong> {{ number_format($order->tax, 2) }}</p>
  </div>

  <div class="section">
    <h2>Processed By</h2>
    <p><strong>Username:</strong> {{ $order->username ?? 'N/A' }}</p>
    <p><strong>Position:</strong> {{ $order->user_role ?? 'N/A' }}</p>
  </div>

  <div class="footer">
    <p>Thank you for your purchase!</p>
    <p>{{ config('app.name') }} - Your Trusted Partner</p>
  </div>
</body>

</html>