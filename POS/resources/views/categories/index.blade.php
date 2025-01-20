@extends('layouts.master')

@section('title', 'Categories')
@section('page-title', 'Categories List')

@section('content')
<div>
  <!-- Search Form -->
  <form method="GET" action="{{ route('categories.index') }}" class="mb-3">
    <input 
      type="text" 
      name="search" 
      class="form-control" 
      placeholder="Search categories..." 
      value="{{ $search ?? '' }}"  
    >
  </form>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Edit</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>
      @forelse($categories as $category)
      <tr>
      <td>{{ $category->name }}</td>
      <td>{{ $category->description }}</td>
      <td>
        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
        data-bs-target="#editCategoryModal" data-id="{{ $category->category_id }}" data-name="{{ $category->name }}"
        data-description="{{ $category->description }}">
        Edit
        </button>
      </td>
      <td>
        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
        data-bs-target="#deleteCategoryModal" data-id="{{ $category->category_id }}"
        data-name="{{ $category->name }}">
        Delete
        </button>
      </td>
      </tr>
    @empty
      <tr>
      <td colspan="4">No categories available.</td>
      </tr>
    @endforelse
    </tbody>
  </table>
</div>

<!-- Trigger Modal -->
<div>
  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
    Add a new category
  </button>
</div>

<!-- Include Add Modal -->
@include('categories.modals.add')

<!-- Include Edit Modal -->
@include('categories.modals.edit')

<!-- Include Delete Modal -->
@include('categories.modals.delete')

@endsection

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Edit Modal Logic
    const editCategoryModal = document.getElementById('editCategoryModal');
    const editForm = document.getElementById('editCategoryForm');
    const editNameInput = document.getElementById('edit-name');
    const editDescriptionInput = document.getElementById('edit-description');

    editCategoryModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      const categoryId = button.getAttribute('data-id');
      const categoryName = button.getAttribute('data-name');
      const categoryDescription = button.getAttribute('data-description');

      editForm.action = `/category/${categoryId}/update`;
      editNameInput.value = categoryName;
      editDescriptionInput.value = categoryDescription;
    });

    // Delete Modal Logic
    const deleteCategoryModal = document.getElementById('deleteCategoryModal');
    const deleteForm = document.getElementById('deleteCategoryForm');
    const deleteCategoryName = document.getElementById('delete-category-name');

    deleteCategoryModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      const categoryId = button.getAttribute('data-id');
      const categoryName = button.getAttribute('data-name');

      deleteForm.action = `/category/${categoryId}/destroy`;
      deleteCategoryName.textContent = categoryName;
    });
  });
</script>