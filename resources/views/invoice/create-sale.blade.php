@extends('layout.sidebar')


@section('content')
<style>
.pagination-btn {
    margin: 0 5px;
}

.page-numbers {
    margin: 0 10px;
    font-weight: bold;
    font-size: 1rem;
    color: #333;
}

.pagination-custom {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
}

.pagination-btn:disabled {
    cursor: not-allowed;
    opacity: 0.5;
}

.pagination-btn i {
    font-size: 14px;
}
</style>
<div class="container-fluid">
    <div class="row">
        <!-- Invoice Section -->
        <div class="col-md-5">
            <div class="card p-4 shadow-sm">
                <div class="row">
                    <!-- Customer Details Section (Billed To) -->
                    <div class="col-8">
                        <span class="text-bold text-dark">BILLED TO </span>
                        <p class="text-xs mx-0 my-1">Name: <span id="CName">Loading...</span></p>
                        <p class="text-xs mx-0 my-1">Email: <span id="CEmail">Loading...</span></p>
                        <p class="text-xs mx-0 my-1">User ID: <span id="CId">Loading...</span></p>
                    </div>
                    <!-- Invoice Info Section -->
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
                <p><strong>TOTAL: </strong> $<span id="totalPrice">0.00</span></p>
                <p><strong>VAT(5%): </strong> $<span id="vat">0.00</span></p>
                <p><strong>Discount: </strong> $<span id="discount">0.00</span></p>
                <div class="mb-3">
                    <label for="discountInput" class="form-label">Discount (%):</label>
                    <input type="number" id="discountInput" class="form-control" value="0">
                </div>
                <button class="btn btn-primary w-100">CONFIRM</button>
            </div>
        </div>
        
        <!-- Product Selection -->
        <div class="col-md-3">
            <div class="card p-3 shadow-sm">
                <h5>Product</h5>
                <input type="text" class="form-control mb-2" placeholder="Search..." id="searchProduct">
                <table class="table">
                    <tbody id="productList">
                        <!-- Products will be loaded here dynamically -->
                    </tbody>
                </table>
                <div id="productPagination" class="pagination-custom d-flex justify-content-center my-3"></div>
            </div>
        </div>
        
        <!-- Customer Selection (Separate from invoice items) -->
        <div class="col-md-3">
            <div class="card p-3 shadow-sm">
                <h5>Customer</h5>
                <input type="text" class="form-control mb-2" placeholder="Search..." id="searchCustomer">
                <table class="table">
                    <tbody id="customerList">
                        <!-- Customers will be loaded here dynamically -->
                    </tbody>
                </table>
                <div id="customerPagination" class="pagination-custom d-flex justify-content-center my-3"></div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemsPerPage = 5;

    // Function to fetch products and customers
    async function fetchData(url, listId, searchInputId, paginationId) {
        try {
            const response = await axios.get(url);
            const items = response.data;
            paginate(listId, items, searchInputId, paginationId);
        } catch (error) {
            console.error(`Error fetching ${url}:`, error);
        }
    }

    // Fetch products & customers
    fetchData('/products', 'productList', 'searchProduct', 'productPagination');
    fetchData('/customers', 'customerList', 'searchCustomer', 'customerPagination');

    // Function to add product to invoice
    function addProductToInvoice(name, price, id) {
        let invoiceItems = document.getElementById('invoiceItems');

        // Check if product already exists
        let existingRow = document.querySelector(`#invoiceItems tr[data-id="${id}"]`);

        if (existingRow) {
            let qtyInput = existingRow.querySelector('.product-qty');
            qtyInput.value = parseInt(qtyInput.value) + 1;
            updateRowTotal(existingRow, price);
        } else {
            let row = document.createElement('tr');
            row.dataset.id = id;
            row.innerHTML = `
                <td>${name}</td>
                <td><input type="number" class="form-control product-qty" value="1" min="1"></td>
                <td class="product-total">${price.toFixed(2)}</td>
                <td><button class="btn btn-danger btn-sm removeProduct">X</button></td>
            `;
            invoiceItems.appendChild(row);

            // Add event listener to update total when quantity changes
            row.querySelector('.product-qty').addEventListener('input', function() {
                updateRowTotal(row, price);
            });
        }

        updateTotal();
    }

    // Function to update the total for a single row
    function updateRowTotal(row, price) {
        let qtyInput = row.querySelector('.product-qty');
        let totalCell = row.querySelector('.product-total');
        let qty = parseInt(qtyInput.value) || 1;
        totalCell.textContent = (price * qty).toFixed(2);
        updateTotal();
    }

    // Function to update the overall total
    function updateTotal() {
        let total = 0;
        document.querySelectorAll('#invoiceItems tr').forEach(row => {
            total += parseFloat(row.querySelector('.product-total').textContent);
        });

        let discount = (parseFloat(document.getElementById('discountInput').value) || 0) / 100;
        let discountedTotal = total - (total * discount);
        let vatAmount = discountedTotal * 0.05;

        document.getElementById('totalPrice').textContent = discountedTotal.toFixed(2);
        document.getElementById('vat').textContent = vatAmount.toFixed(2);
    }

    // Event delegation for adding products to invoice
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('addProduct')) {
            const name = event.target.dataset.name;
            const price = parseFloat(event.target.dataset.price);
            const id = event.target.dataset.id;

            addProductToInvoice(name, price, id);
        }
    });

    // Remove item from invoice
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('removeProduct')) {
            event.target.closest('tr').remove();
            updateTotal();
        }
    });

    // Update total when discount changes
    document.getElementById('discountInput').addEventListener('input', updateTotal);

    // Function to handle customer selection
    async function fetchCustomerDetails(customerId) {
        try {
            const response = await axios.get(`/customers/${customerId}`);
            const customer = response.data;

            document.getElementById('CName').textContent = customer.name || 'N/A';
            document.getElementById('CEmail').textContent = customer.email || 'N/A';
            document.getElementById('CId').textContent = customer.id || 'N/A';
        } catch (error) {
            console.error('Error fetching customer details:', error);
        }
    }

    // Handle selecting a customer
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('addCustomer')) {
            const customerId = event.target.dataset.id;
            fetchCustomerDetails(customerId);
        }
    });

    // Pagination and search for products and customers
    function paginate(listId, items, searchInputId, paginationContainerId) {
        const list = document.getElementById(listId);
        const paginationContainer = document.getElementById(paginationContainerId);
        let currentPage = 1;
        const totalItems = items.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);

        function renderList() {
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const paginatedItems = items.slice(start, end);

            // Render list items
            list.innerHTML = '';
            paginatedItems.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.name}</td>
                    <td><button class="btn btn-sm btn-primary addProduct" data-name="${item.name}" data-price="${item.price}" data-id="${item.id}">ADD</button></td>
                `;
                list.appendChild(row);
            });

            // Render pagination buttons
            paginationContainer.innerHTML = '';

            // Previous Button
            const prevButton = document.createElement('button');
            prevButton.classList.add('btn', 'btn-outline-primary', 'btn-sm', 'pagination-btn');
            prevButton.disabled = currentPage === 1;
            prevButton.innerHTML = `<i class="fa fa-chevron-left"></i> Previous`;
            prevButton.addEventListener('click', () => {
                if (currentPage > 1) currentPage--;
                renderList();
            });

            // Page Numbers
            const pageNumbers = document.createElement('span');
            pageNumbers.classList.add('page-numbers');
            pageNumbers.innerHTML = `${currentPage} / ${totalPages}`;

            // Next Button
            const nextButton = document.createElement('button');
            nextButton.classList.add('btn', 'btn-outline-primary', 'btn-sm', 'pagination-btn');
            nextButton.disabled = currentPage === totalPages;
            nextButton.innerHTML = `Next <i class="fa fa-chevron-right"></i>`;
            nextButton.addEventListener('click', () => {
                if (currentPage < totalPages) currentPage++;
                renderList();
            });

            paginationContainer.appendChild(prevButton);
            paginationContainer.appendChild(pageNumbers);
            paginationContainer.appendChild(nextButton);
        }

        renderList();

        // Re-render the list when the search input changes
        document.getElementById(searchInputId).addEventListener('input', function () {
            const filteredItems = items.filter(item =>
                item.name.toLowerCase().includes(this.value.toLowerCase())
            );
            paginate(listId, filteredItems, searchInputId, paginationContainerId);
        });
    }
});
</script>


@endsection
