@extends('layouts.master')

@section('title', 'Customers')
@section('page-title', 'Customers List')

@section('content')
<div>
  <!-- Search Form -->
  <form method="GET" action="{{ route('customers.index') }}" class="mb-3">
    <input type="text" name="search" class="form-control" placeholder="Search customers.." value="{{ $search ?? '' }}">
  </form>

  <!-- Trigger Modal -->
  <div>
    <button type="button" class="btn btn-success my-2" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
      +
    </button>
  </div>

  <div style="overflow-x:auto;">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Name</th>
          <th>Contact Number</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($customers as $customer)
      <tr>
        <td>{{ $customer->name }}</td>
        <td>{{ $customer->contact_number }}</td>
        <td>
        <!-- Action buttons grouped together -->
        <div class="d-flex gap-2">
          <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
          data-bs-target="#editCustomerModal" data-id="{{ $customer->customer_id }}"
          data-name="{{ $customer->name }}" data-contact_number="{{ $customer->contact_number }}">
          Edit
          </button>
          <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
          data-bs-target="#deleteCustomerModal" data-id="{{ $customer->customer_id }}"
          data-name="{{ $customer->name }}">
          Delete
          </button>
        </div>
        </td>
      </tr>
    @empty
    <tr>
      <td colspan="4">No customers available.</td>
    </tr>
  @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Pagination Links -->
<div>
  {{ $customers->appends([
  'search' => $search,
])->links('pagination::bootstrap-5')}}
</div>

<!-- Include Add Modal -->
@include('customers.modals.add')

<!-- Include Edit Modal -->
@include('customers.modals.edit')

<!-- Include Delete Modal -->
@include('customers.modals.delete')

@endsection

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Edit Modal Logic
    const editCustomerModal = document.getElementById('editCustomerModal');
    const editForm = document.getElementById('editCustomerForm');
    const editNameInput = document.getElementById('edit-name');
    const editContactNumberInput = document.getElementById('edit-contact_number');

    editCustomerModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      const customerId = button.getAttribute('data-id');
      const customerName = button.getAttribute('data-name');
      const customerContactNumber = button.getAttribute('data-contact_number');

      editForm.action = `/customer/${customerId}/update`;
      editNameInput.value = customerName;
      editContactNumberInput.value = customerContactNumber;
    });

    // Delete Modal Logic
    const deleteCustomerModal = document.getElementById('deleteCustomerModal');
    const deleteForm = document.getElementById('deleteCustomerForm');
    const deleteCustomerName = document.getElementById('delete-customer-name');

    deleteCustomerModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      const customerId = button.getAttribute('data-id');
      const customerName = button.getAttribute('data-name');

      deleteForm.action = `/customer/${customerId}/destroy`;
      deleteCustomerName.textContent = customerName;
    });
  });
</script>