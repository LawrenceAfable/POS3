@extends('layouts.master')

@section('title', 'Sale')
@section('page-title', 'Sales Transaction')

@section('content')

<div class="row align-items-center mb-3">
  <!-- Generate Reports=-->
  <div class="col-md-4">
    <a href="{{ route('saleTransactionReport', [
          'start_date' => request('start_date'),
          'end_date' => request('end_date'),
        ]) }}" class="btn btn-info">
              Download PDF
    </a>
  </div>

  <!-- Date Filter Form-->
  <form method="GET" action="{{ route('sales.index') }}" class="col-md-8 d-flex justify-content-end gap-2">
    <!-- Start Date Input -->
    <div>
      <label for="start_date">Start Date</label>
      <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
    </div>

    <!-- End Date Input -->
    <div>
      <label for="end_date">End Date</label>
      <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
    </div>

    <!-- Filter and Reload Buttons -->
    <div class="d-flex gap-2 align-self-end">
      <button type="submit" class="btn btn-primary">Filter</button>
      <a href="{{ route('sales.index') }}" class="btn btn-secondary" title="Reload">
        <i class="bi bi-arrow-repeat"></i>
      </a>
    </div>
  </form>
</div>

<!-- Table -->
<div>
  <!-- <input type="text" class="form-control mb-3" placeholder="Search Sales Transaction..."> -->
  <div style="overflow-x:auto;">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Order No</th>
          <th>Order Date</th>
          <th>Total Amount</th>
          <th>Discount</th>
          <th>Tax</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $order)
      <tr>
        <td>{{ $order->order_id }}</td>
        <td>{{ $order->order_date }}</td>
        <td>{{ $order->total_amount }}</td>
        <td>{{ $order->discount }}</td>
        <td>{{ $order->tax }}</td>
        <td> <a href="{{ route('sales.view', ['order_id' => $order->order_id]) }}"
          class="btn btn-info btn-sm">View</a></td>
      </tr>
    @empty
    <tr>
      <td colspan="8">No sales transactions available.</td>
    </tr>
  @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Pagination Links -->
<div>
  {{ $orders->appends([
  'start_date' => $startDate,
  'end_date' => $endDate,
])->links('pagination::bootstrap-5')}}
</div>

@endsection