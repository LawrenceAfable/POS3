<!-- Modal for Order Preview -->
<div class="modal fade" id="orderPreviewModal" tabindex="-1" aria-labelledby="orderPreviewModalLabel"
  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="orderPreviewModalLabel">Order Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Order Summary -->
        <div class="order-summary">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody id="orderItems">
             
              @foreach($cart as $productId => $item)
                <tr>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>{{ $item['subtotal'] }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="checkout-btn" class="btn btn-primary">Confirm Checkout</button>
      </div>
    </div>
  </div>
</div>