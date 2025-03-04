@extends('layout.sidebar')

@section('content')
<div class="container">
    <div class="card p-4" id="invoice-container">
        <h3 class="text-primary">Invoice</h3>
        <p class="text-muted">Date: {{ \Carbon\Carbon::now()->format('Y-m-d') }}</p>

        <div class="mb-4" id="customer-info">
            <!-- Customer details will be inserted here dynamically -->
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody id="product-list">
                <!-- Products will be inserted here dynamically -->
            </tbody>
        </table>

        <div class="text-end">
            <p><strong>Total:</strong> $<span id="total-amount">0.00</span></p>
            <p><strong>VAT (5%):</strong> $<span id="vat">0.00</span></p>
            <p><strong>Discount:</strong> $<span id="discount">0.00</span></p>
            <p><strong>Payable:</strong> $<span id="payable-amount">0.00</span></p>
        </div>

        <div class="d-flex justify-content-end">
            <button class="btn btn-danger">Close</button>
            <button class="btn btn-success" onclick="window.print()">Print</button>
        </div>
    </div>
</div>

<script>
    axios.get('/invoice/details', {
        params: {
            cus_id: 1, // Replace with actual customer ID
            inv_id: 1  // Replace with actual invoice ID
        }
    })
    .then(response => {
        const data = response.data;
        if (data.status === 'success') {
            const customer = data.customer;
            const products = data.products;
            const total = data.invoice.total_amount; // Assuming you have a total_amount field
            const discount = data.invoice.discount; // Assuming you have a discount field

            // Populate customer details
            document.getElementById('customer-info').innerHTML = `
                <h5><strong>Billed To</strong></h5>
                <p>Name: ${customer.name}</p>
                <p>Email: ${customer.email}</p>
                <p>User ID: ${customer.id}</p>
            `;

            // Populate products
            let productListHTML = '';
            products.forEach(product => {
                const total = (product.sale_price * product.qty).toFixed(2);
                productListHTML += `
                    <tr>
                        <td>${product.product.name}</td>
                        <td>${product.qty}</td>
                        <td>$${total}</td>
                    </tr>
                `;
            });
            document.getElementById('product-list').innerHTML = productListHTML;

            // Update totals
            document.getElementById('total-amount').textContent = total.toFixed(2);
            document.getElementById('vat').textContent = (total * 0.05).toFixed(2);
            document.getElementById('discount').textContent = discount.toFixed(2);
            document.getElementById('payable-amount').textContent = (total + (total * 0.05) - discount).toFixed(2);
        }
    })
    .catch(error => {
        console.error('Error fetching invoice details:', error);
    });
</script>
@endsection
