@extends('layout.sidebar')

@section('content')

<div class="container-fluid">
    <div class="row">
        <!-- Invoice Section -->
        <div class="col-md-5">
            <div class="card p-4 shadow-sm">
                <div class="row">
                    <div class="col-8">
                        <span class="text-bold text-dark">BILLED TO</span>
                        <p>Name: <span id="CName">Select a Customer</span></p>
                        <p>Email: <span id="CEmail">-</span></p>
                        <p>User ID: <span id="CId" data-id="">-</span></p>
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
                <p><strong>TOTAL:</strong> $<span id="totalPrice">0.00</span></p>
                <p><strong>VAT(5%):</strong> $<span id="vat">0.00</span></p>
                <p><strong>Discount:</strong> $<span id="discount">0.00</span></p>
                <div class="mb-3">
                    <label for="discountInput" class="form-label">Discount (%):</label>
                    <input type="number" id="discountInput" class="form-control" value="0">
                </div>
                <button class="btn btn-primary w-100" id="confirmInvoice">CONFIRM</button>
            </div>
        </div>

        <!-- Product Selection -->
        <div class="col-md-3">
            <div class="card p-3 shadow-sm">
                <h5>Product</h5>
                <input type="text" class="form-control mb-2" placeholder="Search..." id="searchProduct">
                <table class="table">
                    <tbody id="productList"></tbody>
                </table>
            </div>
        </div>

        <!-- Customer Selection -->
        <div class="col-md-3">
            <div class="card p-3 shadow-sm">
                <h5>Customer</h5>
                <input type="text" class="form-control mb-2" placeholder="Search..." id="searchCustomer">
                <table class="table">
                    <tbody id="customerList"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetchCustomers();
        fetchProducts();

        // Fetch customers
        function fetchCustomers() {
            axios.get("{{ url('/customers') }}")
                .then(response => {
                    let customers = response.data;
                    let customerList = document.getElementById("customerList");
                    customerList.innerHTML = "";
                    customers.forEach(customer => {
                        let row = `
                            <tr>
                                <td>${customer.name}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary selectCustomer"
                                        data-id="${customer.id}"
                                        data-name="${customer.name}"
                                        data-email="${customer.email}">
                                        Select
                                    </button>
                                </td>
                            </tr>
                        `;
                        customerList.innerHTML += row;
                    });
                })
                .catch(error => console.error("Error fetching customers:", error));
        }

        // Fetch products
        function fetchProducts() {
            axios.get("{{ url('/products') }}")
                .then(response => {
                    let products = response.data;
                    let productList = document.getElementById("productList");
                    productList.innerHTML = "";
                    products.forEach(product => {
                        let row = `
                            <tr>
                                <td>
                                    <img src="${product.img_url}" alt="${product.name}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                </td>
                                <td>
                                    ${product.name} ($${product.price})
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-success addProduct"
                                        data-id="${product.id}"
                                        data-name="${product.name}"
                                        data-price="${product.price}">
                                        Add
                                    </button>
                                </td>
                            </tr>
                        `;
                        productList.innerHTML += row;
                    });
                })
                .catch(error => console.error("Error fetching products:", error));
        }

        // Select Customer
        document.addEventListener("click", function(event) {
            if (event.target.classList.contains("selectCustomer")) {
                let customerId = event.target.dataset.id;
                let customerName = event.target.dataset.name;
                let customerEmail = event.target.dataset.email;

                document.getElementById("CName").textContent = customerName;
                document.getElementById("CEmail").textContent = customerEmail;
                document.getElementById("CId").textContent = customerId;
                document.getElementById("CId").setAttribute("data-id", customerId);
            }
        });

        // Add Product to Invoice
        document.addEventListener("click", function(event) {
            if (event.target.classList.contains("addProduct")) {
                let productId = event.target.dataset.id;
                let productName = event.target.dataset.name;
                let price = parseFloat(event.target.dataset.price);

                let row = document.createElement("tr");
                row.innerHTML = `
                    <td class="item-name" data-product-id="${productId}" data-price="${price}">${productName}</td>
                    <td class="item-qty">
                        <button class="btn btn-sm btn-outline-secondary qty-decrease">-</button>
                        <span class="qty-value">1</span>
                        <button class="btn btn-sm btn-outline-secondary qty-increase">+</button>
                    </td>
                    <td class="item-total">${price.toFixed(2)}</td>
                    <td><button class="btn btn-danger btn-sm removeProduct">X</button></td>
                `;
                document.getElementById("invoiceItems").appendChild(row);
                updateTotals();
            }
        });

        // Listen for quantity change
        document.addEventListener("click", function(event) {
            if (event.target.classList.contains("qty-increase") || event.target.classList.contains("qty-decrease")) {
                let qtySpan = event.target.closest("td").querySelector(".qty-value");
                let currentQty = parseInt(qtySpan.textContent);

                if (event.target.classList.contains("qty-increase")) {
                    qtySpan.textContent = currentQty + 1;
                } else if (event.target.classList.contains("qty-decrease") && currentQty > 1) {
                    qtySpan.textContent = currentQty - 1;
                }

                updateItemTotal(event.target.closest("tr"));
                updateTotals();
            }
        });

        // Update Individual Item Total
        function updateItemTotal(row) {
            let basePrice = parseFloat(row.querySelector(".item-name").getAttribute("data-price"));
            let qty = parseInt(row.querySelector(".qty-value").textContent);
            let total = basePrice * qty;
            row.querySelector(".item-total").textContent = isNaN(total) ? "0.00" : total.toFixed(2);
        }

        // Remove Product from Invoice
        document.addEventListener("click", function(event) {
            if (event.target.classList.contains("removeProduct")) {
                event.target.closest("tr").remove();
                updateTotals();
            }
        });

        // Update Totals
        function updateTotals() {
            let total = 0;
            document.querySelectorAll("#invoiceItems tr").forEach(row => {
                let itemTotal = parseFloat(row.querySelector(".item-total").textContent);
                total += isNaN(itemTotal) ? 0 : itemTotal;
            });

            let vat = total * 0.05; // VAT is 5%
            let discount = parseFloat(document.getElementById("discountInput").value) || 0;
            let payable = total + vat - discount;

            document.getElementById("totalPrice").textContent = total.toFixed(2);
            document.getElementById("vat").textContent = vat.toFixed(2);
            document.getElementById("discount").textContent = discount.toFixed(2);
        }

        // Update totals when discount is applied
        document.getElementById("discountInput").addEventListener("input", updateTotals);

        // Confirm Invoice
        document.getElementById("confirmInvoice").addEventListener("click", function() {
            let customerId = document.getElementById("CId").getAttribute("data-id");
            let discount = parseFloat(document.getElementById("discountInput").value) || 0;
            let totalPrice = parseFloat(document.getElementById("totalPrice").textContent);
            let vat = parseFloat(document.getElementById("vat").textContent);
            let payable = totalPrice + vat - discount;

            if (!customerId) {
                alert("Please select a customer.");
                return;
            }

            let invoiceItems = [];
            document.querySelectorAll("#invoiceItems tr").forEach(row => {
                invoiceItems.push({
                    product_id: row.querySelector(".item-name").dataset.productId,
                    qty: parseInt(row.querySelector(".qty-value").textContent), // Updated qty
                    sale_price: parseFloat(row.querySelector(".item-total").textContent) // Updated total
                });


            });

            axios.post("{{ route('invoiceCreate') }}", {
                    customer_id: customerId,
                    total: totalPrice,
                    discount: discount,
                    vat: vat,
                    payable: payable,
                    products: invoiceItems
                })
                .then(response => {
                    alert("Invoice Created Successfully!");
                    window.location.href = "{{ route('invoiceList') }}";
                })
                .catch(error => {
                    alert("Error creating invoice. Please try again.");
                });
        });
    });
</script>

@endsection