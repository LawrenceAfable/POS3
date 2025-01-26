@extends('layouts.master')

@section('title', 'Sale')
@section('page-title', 'Order Details')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-8">

      <!-- Order Details Card -->
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <!-- Order Details Title -->
          <h5 class="mb-0">Order No: {{ $order->order_id }}</h5>

          <!-- Buttons on the Right -->
          <div class="d-flex gap-2">
            <!-- Back Button -->
            <a href="{{ route('sales.index') }}" class="btn btn-secondary">Back</a>

            <!-- Generate Reports Button -->
            <a href="{{ route('saleReport', ['order_id' => $order->order_id]) }}" class="btn btn-info">
              Download PDF
            </a>

          </div>
        </div>

        <div class="card-body">
          <!-- Customer Information -->
          <div class="text-center mb-4">
            <h5 class="section-heading">Customer Information</h5>
          </div>
          <div class="row">
            <div class="col-md-4"><strong>Customer Name:</strong></div>
            <div class="col-md-8">{{ $order->customer_name }}</div>
          </div>
          <div class="row">
            <div class="col-md-4"><strong>Order Date:</strong></div>
            <div class="col-md-8">{{ \Carbon\Carbon::parse($order->order_date)->format('F j, Y') }}</div>
          </div>
          <hr>

          <!-- Payment Information -->
          <div class="text-center my-4">
            <h5 class="section-heading">Payment Information</h5>
          </div>
          <div class="row">
            <div class="col-md-4"><strong>Amount Paid:</strong></div>
            <div class="col-md-8">{{ number_format($order->amount_paid, 2) }}</div>
          </div>
          <div class="row">
            <div class="col-md-4"><strong>Change:</strong></div>
            <div class="col-md-8">{{ number_format($order->change, 2) }}</div>
          </div>
          <div class="row">
            <div class="col-md-4"><strong>Payment Method:</strong></div>
            <div class="col-md-8">{{ $order->payment_method }}</div>
          </div>
          <hr>

          <!-- Products Purchased -->
          <div class="text-center my-4">
            <h5 class="section-heading">Products Purchased</h5>
          </div>
          <div class="row">
            <div class="col-md-12">
              <ul>
                @foreach(explode(',', $order->products_brought) as $product)
          <li>{{ $product }}</li>
        @endforeach
              </ul>
            </div>
          </div>
          <hr>

          <!-- Order Totals -->
          <div class="text-center my-4">
            <h5 class="section-heading">Order Totals</h5>
          </div>
          <div class="row">
            <div class="col-md-4"><strong>Total Amount:</strong></div>
            <div class="col-md-8">{{ number_format($order->total_amount, 2) }}</div>
          </div>
          <div class="row">
            <div class="col-md-4"><strong>Discount:</strong></div>
            <div class="col-md-8">{{ number_format($order->discount, 2) }}</div>
          </div>
          <div class="row">
            <div class="col-md-4"><strong>Tax:</strong></div>
            <div class="col-md-8">{{ number_format($order->tax, 2) }}</div>
          </div>
          <hr>

          <!-- Processed By -->
          <div class="text-center my-4">
            <h5 class="section-heading">Processed By</h5>
          </div>
          <div class="row">
            <div class="col-md-4"><strong>Username:</strong></div>
            <div class="col-md-8">{{ $order->username ?? 'N/A' }}</div>
          </div>
          <div class="row">
            <div class="col-md-4"><strong>Position:</strong></div>
            <div class="col-md-8">{{ $order->user_role ?? 'N/A' }}</div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection