document.addEventListener('DOMContentLoaded', function () {
    const itemsPerPage = 5;
    let currentPage = 1;
    let allProducts = [];

    // Fetch product data
    async function fetchData(url, listId, searchInputId, paginationId) {
        try {
            const response = await axios.get(url);
            allProducts = response.data;
            paginate(listId, allProducts, searchInputId, paginationId);
        } catch (error) {
            console.error(`Error fetching ${url}:`, error);
        }         
    }

    fetchData('/products', 'productList', 'searchProduct', 'productPagination');

    // Paginate function
    function paginate(listId, items, searchInputId, paginationContainerId) {
        const list = document.getElementById(listId);
        const paginationContainer = document.getElementById(paginationContainerId);
        let totalPages = Math.ceil(items.length / itemsPerPage);

        function renderList() {
            list.innerHTML = "";
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const paginatedItems = items.slice(start, end);

            // Create table rows for paginated items
            paginatedItems.forEach(item => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.name}</td>
                    <td><button class="btn btn-sm btn-primary addProduct" data-name="${item.name}" data-price="${item.price}" data-id="${item.id}">ADD</button></td>
                `;
                list.appendChild(row);
            });

            renderPaginationControls(paginationContainer, items);
        }

        // Render pagination controls
        function renderPaginationControls(container, items) {
            container.innerHTML = "";
            totalPages = Math.ceil(items.length / itemsPerPage);

            const prevButton = document.createElement("button");
            prevButton.classList.add("btn", "btn-outline-primary", "btn-sm");
            prevButton.innerHTML = `<i class="fa fa-chevron-left"></i>`;
            prevButton.disabled = currentPage === 1;
            prevButton.addEventListener("click", () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderList();
                }
            });

            const pageNumbers = document.createElement("span");
            pageNumbers.textContent = `Page ${currentPage} of ${totalPages}`;

            const nextButton = document.createElement("button");
            nextButton.classList.add("btn", "btn-outline-primary", "btn-sm");
            nextButton.innerHTML = `<i class="fa fa-chevron-right"></i>`;
            nextButton.disabled = currentPage === totalPages;
            nextButton.addEventListener("click", () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    renderList();
                }
            });

            container.appendChild(prevButton);
            container.appendChild(pageNumbers);
            container.appendChild(nextButton);
        }

        renderList();

        // Handle search filter
        document.getElementById(searchInputId).addEventListener('input', function () {
            const filteredItems = allProducts.filter(item =>
                item.name.toLowerCase().includes(this.value.toLowerCase())
            );
            currentPage = 1; // Reset pagination on search
            paginate(listId, filteredItems, searchInputId, paginationContainerId);
        });
    }

    // Handle adding products to invoice
    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("addProduct")) {
            const name = event.target.dataset.name;
            const price = parseFloat(event.target.dataset.price);
            const id = event.target.dataset.id;
            addProductToInvoice(name, price, id);
        }
    });

    // Add product to the invoice table
    function addProductToInvoice(name, price, id) {
        let invoiceItems = document.getElementById("invoiceItems");
        let existingRow = document.querySelector(`#invoiceItems tr[data-id="${id}"]`);

        if (existingRow) {
            let qtyInput = existingRow.querySelector(".product-qty");
            qtyInput.value = parseInt(qtyInput.value) + 1;
            updateRowTotal(existingRow, price);
        } else {
            let row = document.createElement("tr");
            row.dataset.id = id;
            row.innerHTML = `
                <td>${name}</td>
                <td><input type="number" class="form-control product-qty" value="1" min="1"></td>
                <td class="product-total">${price.toFixed(2)}</td>
                <td><button class="btn btn-danger btn-sm removeProduct">X</button></td>
            `;
            invoiceItems.appendChild(row);

            row.querySelector(".product-qty").addEventListener("input", function () {
                updateRowTotal(row, price);
            });
        }
        updateTotal();
    }

    // Update row total when quantity changes
    function updateRowTotal(row, price) {
        let qtyInput = row.querySelector(".product-qty");
        let totalCell = row.querySelector(".product-total");
        let qty = parseInt(qtyInput.value) || 1;
        totalCell.textContent = (price * qty).toFixed(2);
        updateTotal();
    }

    // Update the total price and VAT
    function updateTotal() {
        let total = 0;
        document.querySelectorAll("#invoiceItems tr").forEach(row => {
            total += parseFloat(row.querySelector(".product-total").textContent);
        });

        let discount = (parseFloat(document.getElementById("discountInput").value) || 0) / 100;
        let discountedTotal = total - total * discount;
        let vatAmount = discountedTotal * 0.05;

        document.getElementById("totalPrice").textContent = discountedTotal.toFixed(2);
        document.getElementById("vat").textContent = vatAmount.toFixed(2);
    }

    // Event listener for discount input
    document.getElementById("discountInput").addEventListener("input", updateTotal);

    // Remove product from invoice
    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("removeProduct")) {
            event.target.closest("tr").remove();
            updateTotal();
        }
    });
});
