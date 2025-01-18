<!-- Modal for Adding Supplier -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addSupplierModalLabel">Add Supplier</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addSupplierForm" action="{{ route('suppliers.store') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label for="add-name" class="form-label">Name</label>
            <input type="text" class="form-control" id="add-name" name="name" required>
          </div>
          <div class="mb-3">
            <label for="add-email" class="form-label">Email</label>
            <input type="email" class="form-control" id="add-email" name="email" required>
          </div>
          <div class="mb-3">
            <label for="add-phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="add-phone" name="phone" required>
          </div>
          <div class="mb-3">
            <label for="add-address" class="form-label">Address</label>
            <textarea class="form-control" id="add-address" name="address" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>
