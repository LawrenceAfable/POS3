@extends('layouts.master')

@section('title', 'User Management')
@section('page-title', 'Users List')

@section('content')
@include('components.alert')
<div>
  <!-- Search Form -->
  <form method="GET" action="{{ route('admin.userrole.index') }}" class="mb-3">
    <input type="text" name="search" class="form-control" placeholder="Search users.." value="{{ $search ?? '' }}">
  </form>

  <!-- Add User Button -->
  <div>
    <a href="{{ route('admin.userrole.modals.add') }}" class="btn btn-success my-2">+</a>
  </div>

  <div style="overflow-x:auto;">
    <!-- Users Table -->
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $user)
      <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ ucfirst($user->role) }}</td>
        <td style="color: {{ $user->status == 0 ? 'green' : 'red' }};">
        {{ $user->status == 0 ? 'Active' : 'Inactive' }}
        </td>
        <td>
        <div class="d-flex gap-2">
          <!-- Edit Button -->
          <form action="{{ route('admin.userrole.modals.edit', $user->id) }}" method="GET">
          <button type="submit" class="btn btn-warning btn-sm">Edit</button>
          </form>

          <!-- Delete Button -->
          @if(auth()->user()->id !== $user->id) {{-- Prevent self-deletion --}}
        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
        onsubmit="return confirm('Are you sure you want to delete this user?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
        </form>
      @endif
        </div>
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
</div>

<!-- Pagination Links -->
<div>
  {{ $users->appends(['search' => $search])->links('pagination::bootstrap-5') }}
</div>

@endsection