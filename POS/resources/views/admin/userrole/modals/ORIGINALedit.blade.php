<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" id="editUserForm">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
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
            <input type="text" id="edit-name" name="name" class="form-control" required>
          </div>

          <!-- Email -->
          <div class="mb-3">
            <label for="edit-email" class="form-label">Email</label>
            <input type="email" id="edit-email" name="email" class="form-control" required>
          </div>

          <!-- Role -->
          @if(auth()->user()->id !== $user->id)
        <div class="mb-3">
        <label for="edit-role" class="form-label">Role</label>
        <select id="edit-role" name="role" class="form-select" required>
          <option value="admin">Admin</option>
          <option value="cashier">Cashier</option>
        </select>
        </div>
      @endif

          <!-- Password (Optional) -->
          <div class="mb-3">
            <label for="edit-password" class="form-label">New Password <small>(Leave blank to keep
                current)</small></label>
            <input type="password" id="edit-password" name="password" class="form-control" minlength="5" maxlength="15">
          </div>

          <!-- Confirm Password -->
          <div class="mb-3">
            <label for="edit-password-confirm" class="form-label">Confirm New Password</label>
            <input type="password" id="edit-password-confirm" name="password_confirmation" class="form-control"
              minlength="5" maxlength="15">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning">Update User</button>
        </div>
      </div>
    </form>
  </div>
</div>