@extends('layout.sidebar')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Invoice Section -->
        <div class="col-md-5">
            <div class="card p-4 shadow-sm">
                <div class="row">
                    <div class="col-8">
                        <span class="text-bold text-dark">BILLED TO </span>
                        <p class="text-xs mx-0 my-1">Name: <span id="CName"></span></p>
                        <p class="text-xs mx-0 my-1">Email: <span id="CEmail"></span></p>
                        <p class="text-xs mx-0 my-1">User ID: <span id="CId"></span></p>
                    </div>
                    <div class="col-4">
                        <span class="text-bold text-dark">INVOICE </span>
                        <p class="text-bold mx-0 my-1 text-dark">Invoice </p>
                        <p class="text-xs mx-0 my-1">Date: {{ date('Y-m-d') }}</p>
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody id="invoiceItems"></tbody>
                </table>
                <p><strong>TOTAL: </strong> $<span id="totalPrice">0</span></p>
                <p><strong>VAT(5%): </strong> $<span id="vat">0</span></p>
                <p><strong>Discount: </strong> $<span id="discount">0</span></p>
                <div class="mb-3">
                    <label for="discount" class="form-label">Discount (%):</label>
                    <input type="number" id="discountInput" class="form-control" value="0">
                </div>
                <button class="btn btn-primary w-100">CONFIRM</button>
            </div>
        </div>
        
        <!-- Product Selection -->
        <div class="col-md-3">
            <div class="card p-3 shadow-sm">
                <h5>Product</h5>
                <input type="text" class="form-control mb-2" placeholder="Search...">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Product 1 ($100)</td>
                            <td><button class="btn btn-sm btn-primary addProduct" data-name="Product 1" data-price="100">ADD</button></td>
                        </tr>
                        <tr>
                            <td>Product 2 ($200)</td>
                            <td><button class="btn btn-sm btn-primary addProduct" data-name="Product 2" data-price="200">ADD</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Customer Selection -->
        <div class="col-md-3">
            <div class="card p-3 shadow-sm">
                <h5>Customer</h5>
                <input type="text" class="form-control mb-2" placeholder="Search...">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>John Doe</td>
                            <td>
                                <button class="btn btn-sm btn-primary addCustomer" 
                                    data-name="John Doe" 
                                    data-email="john@example.com" 
                                    data-id="123">ADD</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Jane Smith</td>
                            <td>
                                <button class="btn btn-sm btn-primary addCustomer" 
                                    data-name="Jane Smith" 
                                    data-email="jane@example.com" 
                                    data-id="456">ADD</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let invoiceItems = document.getElementById('invoiceItems');
    let totalPrice = document.getElementById('totalPrice');
    let vat = document.getElementById('vat');
    let discountInput = document.getElementById('discountInput');

    // Function to update the total amount
    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.product-total').forEach(item => {
            total += parseFloat(item.textContent);
        });

        let discount = (parseFloat(discountInput.value) || 0) / 100;
        let discountedTotal = total - (total * discount);
        let vatAmount = discountedTotal * 0.05;

        totalPrice.textContent = discountedTotal.toFixed(2);
        vat.textContent = vatAmount.toFixed(2);
    }

    // Add product to the invoice
    document.querySelectorAll('.addProduct').forEach(button => {
        button.addEventListener('click', function() {
            let productName = this.dataset.name;
            let productPrice = parseFloat(this.dataset.price);

            let existingRow = document.querySelector(`#invoiceItems tr[data-name="${productName}"]`);
            
            if (existingRow) {
                let qtyInput = existingRow.querySelector('.product-qty');
                let totalCell = existingRow.querySelector('.product-total');
                qtyInput.value = parseInt(qtyInput.value) + 1;
                totalCell.textContent = (qtyInput.value * productPrice).toFixed(2);
            } else {
                let row = document.createElement('tr');
                row.setAttribute('data-name', productName);
                row.innerHTML = `
                    <td>${productName}</td>
                    <td><input type="number" class="form-control product-qty" value="1" min="1" style="width: 50px;"></td>
                    <td class="product-total">${productPrice.toFixed(2)}</td>
                    <td><button class="btn btn-sm btn-danger removeProduct">X</button></td>
                `;
                invoiceItems.appendChild(row);
            }

            updateTotal();
        });
    });

    // Remove product from the invoice
    invoiceItems.addEventListener('click', function(event) {
        if (event.target.classList.contains('removeProduct')) {
            event.target.closest('tr').remove();
            updateTotal();
        }
    });

    // Update product total when quantity is changed
    invoiceItems.addEventListener('input', function(event) {
        if (event.target.classList.contains('product-qty')) {
            let row = event.target.closest('tr');
            let productPrice = parseFloat(document.querySelector(`.addProduct[data-name="${row.dataset.name}"]`).dataset.price);
            let totalCell = row.querySelector('.product-total');
            totalCell.textContent = (event.target.value * productPrice).toFixed(2);
            updateTotal();
        }
    });

    // Update the total when discount changes
    discountInput.addEventListener('input', updateTotal);

    // Add customer details to the BILLED TO section
    document.querySelectorAll('.addCustomer').forEach(button => {
        button.addEventListener('click', function() {
            let customerName = this.dataset.name;
            let customerEmail = this.dataset.email;
            let customerId = this.dataset.id;

            // Update the BILLED TO section with the selected customer details
            document.getElementById('CName').textContent = customerName;
            document.getElementById('CEmail').textContent = customerEmail;
            document.getElementById('CId').textContent = customerId;
        });
    });
});
</script>

@endsection
