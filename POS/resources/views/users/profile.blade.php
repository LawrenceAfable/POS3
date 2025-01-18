@extends('layouts.master')

@section('title', 'User Profile')

@section('page-title', 'User Profile')

@section('content')
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Profile</h4>
      <p>Name: {{ Auth::user()->name }}</p>
      <p>Email: {{ Auth::user()->email }}</p>

      <!-- Form to edit user profile -->
      <form action="{{ route('users1.update', Auth::user()->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- To specify a PUT method for updating the data -->
        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" required>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <button type="submit" class="btn btn-primary">Update Profile</button>
        </div>
      </form>
    </div>
  </div>
@endsection
