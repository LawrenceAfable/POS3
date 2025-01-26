@extends('layouts.master')

@section('title', 'User Management')
@section('page-title', 'Add User')

@section('content')
<div class="container">
  <form action="{{ route('users.store') }}" method="POST">
    @csrf
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Add New User</h5>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <label for="name">Name</label>
          <input type="text" name="name" class="form-control" required>
          @error('name')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>
        <div class="mb-3">
          <label for="email">Email</label>
          <input type="email" name="email" class="form-control" required>
          @error('email')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>
        <div class="mb-3">
          <label for="role">Role</label>
          <select name="role" class="form-select" required>
            <option value="admin">Admin</option>
            <option value="cashier">Cashier</option>
          </select>
          @error('role')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>
        <div class="mb-3">
          <label for="password">Password</label>
          <input type="password" name="password" class="form-control" required>
          @error('password')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary">Add User</button>
      </div>
    </div>
  </form>
</div>
@endsection