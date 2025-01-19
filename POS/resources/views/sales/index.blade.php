@extends('layouts.master')

@section('title', 'Sale')
@section('page-title', 'Sales Transaction')

@section('content')
<div>
  <input type="text" class="form-control mb-3" placeholder="Search Sales Transaction...">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Transaction ID</th>
        <th>Order ID</th>
        <th>User Name</th>
        <th>User Role</th>
        <th>Product SKU</th>
        <th>Product Name</th>
        <th>Amount Paid</th>
        <th>Order Date</th>
      </tr>
    </thead>
    <tbody>
      @forelse($transactions as $transaction)
      <tr>
        <td>{{ $transaction->transaction_id }}</td>
        <td>{{ $transaction->order_id }}</td>
        <td>{{ $transaction->user_name }}</td>
        <td>{{ $transaction->user_role }}</td>
        <td>{{ $transaction->product_sku }}</td>
        <td>{{ $transaction->product_name }}</td>
        <td>{{ number_format($transaction->amount_paid, 2) }}</td>
        <td>{{ $transaction->order_date }}</td>
      </tr>
      @empty
        <tr>
          <td colspan="8">No sales transactions available.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
