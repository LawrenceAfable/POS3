@extends('layouts.master')

@section('title', 'Suppliers')
@section('page-title', 'Suppliers List')

@section('content')
<div>
  <!-- Search Form -->
  <form method="GET" action="{{ route('suppliers.index') }}" class="mb-3">
    <input type="text" name="search" class="form-control" placeholder="Search suppliers..." value="{{ $search ?? '' }}">
  </form>

  <!-- Trigger Modal -->
  <div>
    <button type="button" class="btn btn-success my-2" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
      +
    </button>
  </div>

  <div style="overflow-x:auto;">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Address</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($suppliers as $supplier)
      <tr>
        <td>{{ $supplier->name }}</td>
        <td>{{ $supplier->email }}</td>
        <td>{{ $supplier->phone }}</td>
        <td>{{ $supplier->address }}</td>
        <td style="color: {{ $supplier->status == 0 ? 'green' : 'red' }};">
        {{ $supplier->status == 0 ? 'Active' : 'Inactive' }}
        </td>
        <td>
        <div class="d-flex gap-2">
          <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
          data-bs-target="#editSupplierModal" data-id="{{ $supplier->supplier_id }}"
          data-name="{{ $supplier->name }}" data-email="{{ $supplier->email }}"
          data-phone="{{ $supplier->phone }}" data-address="{{ $supplier->address }}"
          data-status="{{ $supplier->status }}">
          Edit
          </button>
          <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
          data-bs-target="#deleteSupplierModal" data-id="{{ $supplier->supplier_id }}"
          data-name="{{ $supplier->name }}">
          Delete
          </button>
        </div>
        </td>
      </tr>
    @empty
    <tr>
      <td colspan="7">No suppliers available.</td>
    </tr>
  @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Pagination Links -->
<div>
  {{ $suppliers->appends([
  'search' => $search,
])->links('pagination::bootstrap-5')}}
</div>

<!-- Include Add Modal -->
@include('suppliers.modals.add')

<!-- Include Edit Modal -->
@include('suppliers.modals.edit')

<!-- Include Delete Modal -->
@include('suppliers.modals.delete')

@endsection

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Edit Modal Logic
    const editSupplierModal = document.getElementById('editSupplierModal');
    const editForm = document.getElementById('editSupplierForm');
    const editNameInput = document.getElementById('edit-name');
    const editEmailInput = document.getElementById('edit-email');
    const editPhoneInput = document.getElementById('edit-phone');
    const editAddressInput = document.getElementById('edit-address');
    const editStatusInput = document.getElementById('edit-status');

    editSupplierModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      const supplierId = button.getAttribute('data-id');
      const supplierName = button.getAttribute('data-name');
      const supplierEmail = button.getAttribute('data-email');
      const supplierPhone = button.getAttribute('data-phone');
      const supplierAddress = button.getAttribute('data-address');
      const supplierStatus = button.getAttribute('data-status');

      editForm.action = `/supplier/${supplierId}/update`;
      editNameInput.value = supplierName;
      editEmailInput.value = supplierEmail;
      editPhoneInput.value = supplierPhone;
      editAddressInput.value = supplierAddress;
      editStatusInput.value = supplierStatus;
    });

    // Delete Modal Logic
    const deleteSupplierModal = document.getElementById('deleteSupplierModal');
    const deleteForm = document.getElementById('deleteSupplierForm');
    const deleteSupplierName = document.getElementById('delete-supplier-name');

    deleteSupplierModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      const supplierId = button.getAttribute('data-id');
      const supplierName = button.getAttribute('data-name');

      deleteForm.action = `/supplier/${supplierId}/destroy`;
      deleteSupplierName.textContent = supplierName;
    });
  });
</script>