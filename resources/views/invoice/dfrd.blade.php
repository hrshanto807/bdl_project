@extends('layout.sidebar')

@section('content')

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

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

<!-- Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        fetchCustomers();
        fetchProducts();

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

        document.addEventListener("click", function (event) {
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

        document.addEventListener("click", function (event) {
            if (event.target.classList.contains("addProduct")) {
                let productId = event.target.dataset.id;
                let productName = event.target.dataset.name;
                let price = parseFloat(event.target.dataset.price);
                let row = document.createElement("tr");
                row.innerHTML = `
                    <td class="item-name" data-product-id="${productId}">${productName}</td>
                    <td class="item-qty">1</td>
                    <td class="item-total">${price.toFixed(2)}</td>
                    <td><button class="btn btn-danger btn-sm removeProduct">X</button></td>
                `;
                document.getElementById("invoiceItems").appendChild(row);
                updateTotals();
            }
        });

        document.addEventListener("click", function (event) {
            if (event.target.classList.contains("removeProduct")) {
                event.target.closest("tr").remove();
                updateTotals();
            }
        });
    });
</script>

@endsection
