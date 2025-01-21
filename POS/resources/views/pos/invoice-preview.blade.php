<!-- pos/invoice_preview.blade.php -->
<div class="invoice-preview">
    <h1>Invoice Preview</h1>

    <h3>Customer Information</h3>
    <p>Name: {{ $invoiceData['customer']->name ?? 'N/A' }}</p>
    <p>Contact Number: {{ $invoiceData['customer']->contact_number ?? 'N/A' }}</p>

    <h3>Products in Cart</h3>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoiceData['cart'] as $productId => $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>${{ number_format($item['price'], 2) }}</td>
                    <td>${{ number_format($item['subtotal'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Summary</h3>
    <p>Total: ${{ number_format($invoiceData['total'], 2) }}</p>
    <p>Tax ({{ $invoiceData['tax'] }}%): ${{ number_format($invoiceData['taxAmount'], 2) }}</p>
    <p>Discount ({{ $invoiceData['discount'] }}%): ${{ number_format($invoiceData['discountAmount'], 2) }}</p>
    <p><strong>Grand Total: ${{ number_format($invoiceData['grandTotal'], 2) }}</strong></p>

    <h3>Payment Method</h3>
    <p>Payment Method: {{ $request->payment_mode }}</p>

    <a href="{{ route('pos.checkout') }}" class="btn btn-primary">Proceed to Checkout</a>
    <a href="{{ route('pos.index') }}" class="btn btn-secondary">Edit Cart</a>
</div>
