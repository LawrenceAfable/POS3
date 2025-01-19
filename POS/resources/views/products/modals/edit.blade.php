<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editProductForm" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label for="edit-sku" class="form-label">SKU</label>
            <input type="text" id="edit-sku" class="form-control" name="sku" placeholder="SKU" required>
          </div>

          <div class="mb-3">
            <label for="edit-name" class="form-label">Product Name</label>
            <input type="text" id="edit-name" class="form-control" name="name" placeholder="Product Name" required>
          </div>

          <div class="mb-3">
            <label for="edit-description" class="form-label">Description</label>
            <textarea id="edit-description" class="form-control" name="description"
              placeholder="Product Description"></textarea>
          </div>

          <div class="mb-3">
            <label for="edit-price" class="form-label">Price</label>
            <input type="number" id="edit-price" class="form-control" step="0.01" name="price" placeholder="Price"
              required>
          </div>

          <div class="mb-3">
            <label for="edit-category" class="form-label">Category</label>
            <select id="edit-category" class="form-select" name="category_id"> <!-- required -->
              <option value="">Select Category</option>
              @foreach($categories as $category)
          <option value="{{ $category->category_id }}">{{ $category->name }}</option>
        @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label for="edit-image" class="form-label">Product Image (Optional)</label>
            <input type="file" id="edit-image" class="form-control" name="image">
            <small class="form-text text-muted">If you want to update the image, select a new one.</small>
          </div>

          @if(isset($product->image_url))
        <div class="mb-3">
        <label for="current-image" class="form-label">Current Image</label>
        <div>
          <img src="{{ asset('storage/' . $product->image_url) }}" alt="Current Product Image"
          style="max-width: 200px;">
        </div>
        </div>
      @endif

          <div class="mb-3">
            <label for="edit-quantity" class="form-label">Quantity</label>
            <input type="number" id="edit-quantity" class="form-control" name="quantity" placeholder="Quantity"
              required>
          </div>

          <div class="mb-3">
            <label for="edit-supplier" class="form-label">Supplier (Optional)</label>
            <select id="edit-supplier" class="form-select" name="supplier_id">
              <option value="">Select Supplier</option>
              @foreach($suppliers as $supplier)
          <option value="{{ $supplier->supplier_id }}">{{ $supplier->name }}</option>
        @endforeach
            </select>
          </div>

          <div class="mb-3">
            <button type="submit" class="btn btn-primary">Update Product</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>