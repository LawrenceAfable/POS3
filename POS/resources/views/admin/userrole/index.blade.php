@extends('layouts.master')

@section('title', 'User Management')
@section('page-title', 'Users List')

@section('content')
<div>
  <!-- Search Bar -->
  <input type="text" class="form-control mb-3" placeholder="Search users...">

  <!-- Users Table -->
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Edit</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>
      @forelse($users as $user)
      <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ ucfirst($user->role) }}</td>
        <td>
          <button type="button" class="btn btn-warning btn-sm" 
                  data-bs-toggle="modal" 
                  data-bs-target="#editUserModal" 
                  data-id="{{ $user->id }}" 
                  data-name="{{ $user->name }}"
                  data-email="{{ $user->email }}" 
                  data-role="{{ $user->role }}">
            Edit
          </button>
        </td>
        <td>
          @if(auth()->user()->id !== $user->id) {{-- Prevent self-deletion --}}
          <button type="button" class="btn btn-danger btn-sm" 
                  data-bs-toggle="modal" 
                  data-bs-target="#deleteUserModal" 
                  data-id="{{ $user->id }}" 
                  data-name="{{ $user->name }}">
            Delete
          </button>
          @endif
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="5">No users available.</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

<!-- Add User Button -->
<div>
  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
    Add a New User
  </button>
</div>

<!-- Include Modals -->
@include('admin.userrole.modals.add')
@include('admin.userrole.modals.edit')
@include('admin.userrole.modals.delete')
@endsection

<!-- JavaScript for Modals -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Edit Modal Logic
    const editUserModal = document.getElementById('editUserModal');
    const editForm = document.getElementById('editUserForm');
    const editNameInput = document.getElementById('edit-name');
    const editEmailInput = document.getElementById('edit-email');
    const editRoleSelect = document.getElementById('edit-role');
    const editPasswordInput = document.getElementById('edit-password');
    const editPasswordConfirmInput = document.getElementById('edit-password-confirm');

    editUserModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      const userId = button.getAttribute('data-id');
      const userName = button.getAttribute('data-name');
      const userEmail = button.getAttribute('data-email');
      const userRole = button.getAttribute('data-role');

      // Set form action dynamically
      editForm.action = `/user/${button.getAttribute('data-id')}/update`;
      // editForm.action = `/user/${button.getAttribute('data-id')}/update`;


      // Populate form fields
      editNameInput.value = userName;
      editEmailInput.value = userEmail;
      editRoleSelect.value = userRole;

      // Clear password fields on modal open
      editPasswordInput.value = '';
      editPasswordConfirmInput.value = '';
    });

    // Delete Modal Logic
    const deleteUserModal = document.getElementById('deleteUserModal');
    const deleteForm = document.getElementById('deleteUserForm');
    const deleteUserName = document.getElementById('delete-user-name');

    deleteUserModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      const userId = button.getAttribute('data-id');
      const userName = button.getAttribute('data-name');

      // Set form action for deletion
      deleteForm.action = "{{ url('/user') }}/" + button.getAttribute('data-id') + "/destroy";
      deleteUserName.textContent = userName;
    });
  });
</script>
