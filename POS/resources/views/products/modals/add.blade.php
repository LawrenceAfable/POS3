<!-- Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('POST')

          <div class="mb-3">
            <label for="sku" class="form-label">SKU</label>
            <input type="text" class="form-control" name="sku" placeholder="SKU" required>
          </div>

          <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control" name="name" placeholder="Product Name" required>
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" name="description" placeholder="Product Description"></textarea>
          </div>

          <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" step="0.01" name="price" placeholder="Price" required>
          </div>

          <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select class="form-select" name="category_id"> <!-- required -->
              <option value="">Select Category</option>
              @foreach($categories as $category)
          <option value="{{ $category->category_id }}">{{ $category->name }}</option>
        @endforeach
            </select>
          </div>

          <!-- Update this part to allow file upload -->
          <div class="mb-3">
            <label for="image_url" class="form-label">Product Image (Optional)</label>
            <input type="file" class="form-control" name="image" accept="image/*">
          </div>

          <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" name="quantity" placeholder="Quantity" required>
          </div>

          <div class="mb-3">
            <label for="supplier_id" class="form-label">Supplier (Optional)</label>
            <select class="form-select" name="supplier_id">
              <option value="">Select Supplier</option>
              @foreach($suppliers as $supplier)
          <option value="{{ $supplier->supplier_id }}">{{ $supplier->name }}</option>
        @endforeach
            </select>
          </div>

          <div class="mb-3">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>