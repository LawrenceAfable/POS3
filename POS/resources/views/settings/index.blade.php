@extends('layouts.master')

@section('title', 'Setting')

@section('page-title', 'Setting Page')

@section('content')
<div class="card">
  <div class="card-body">
    <form action="{{ route('settings.update') }}" method="POST">
      @csrf
      @method('PUT')
      <div class="mb-3">
        <label for="tax" class="form-label">Tax</label>
        <input type="text" name="tax" class="form-control" value="{{ old('tax', $settings->tax ?? '') }}" required>
      </div>
      <div class="mb-3">
        <label for="discount" class="form-label">Discount</label>
        <input type="text" name="discount" class="form-control" value="{{ old('discount', $settings->discount ?? '') }}" required>
      </div>
      <div class="d-flex justify-content-between align-items-center">
          <button type="submit" class="btn btn-primary">Update Setting</button>
        </div>
    </form>
  </div>
</div>
@endsection
