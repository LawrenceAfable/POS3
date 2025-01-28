@extends('layouts.master')

@section('title', 'User Management')
@section('page-title', 'Edit User')

@section('content')
<div class="container">
  <form method="POST" id="editUserForm" action="{{ route('users.update', $user->id) }}">
    @csrf
    @method('PUT')
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Edit User</h5>
      </div>
      <div class="card-body">
        <!-- Status -->
        <div class="mb-3">
          <label for="edit-status" class="form-label">Status</label>
          <select id="edit-status" class="form-select" name="status" required>
            <option value="">Select Status</option>
            <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Active</option>
            <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>

        <!-- Name -->
        <div class="mb-3">
          <label for="edit-name" class="form-label">Name</label>
          <input type="text" id="edit-name" name="name" class="form-control" value="{{ $user->name }}" required>
        </div>

        <!-- Email -->
        <div class="mb-3">
          <label for="edit-email" class="form-label">Email</label>
          <input type="email" id="edit-email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>

        <!-- Role -->
        @if(auth()->user()->id !== $user->id)
          <div class="mb-3">
            <label for="edit-role" class="form-label">Role</label>
            <select id="edit-role" name="role" class="form-select" readonly>
              <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
              <option value="cashier" {{ $user->role == 'cashier' ? 'selected' : '' }}>Cashier</option>
            </select>
          </div>
        @endif

        <!-- Password (Optional) -->
        <div class="mb-3">
          <label for="edit-password" class="form-label">New Password <small>(Leave blank to keep current)</small></label>
          <input type="password" id="edit-password" name="password" class="form-control" minlength="5" maxlength="15">
          @error('password')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>
        

        <!-- Confirm Password -->
        <div class="mb-3">
          <label for="edit-password-confirm" class="form-label">Confirm New Password</label>
          <input type="password" id="edit-password-confirm" name="password_confirmation" class="form-control"
            minlength="5" maxlength="15">
        </div>
      </div>
      <div class="card-footer">
        <button type="button" class="btn btn-danger" onclick="window.history.back();">Cancel</button>
        <button type="submit" class="btn btn-warning">Update User</button>
      </div>
    </div>
  </form>
</div>
@endsection