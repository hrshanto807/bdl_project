document.addEventListener('DOMContentLoaded', function () {
    let allCustomers = [], customers = [], currentPage = 1, itemsPerPage = 5;
    let selectedCustomer = null;

    async function fetchCustomers() {
        try {
            let res = await axios.get('/customers');
            allCustomers = res.data;
            customers = [...allCustomers];
            paginateCustomers();
        } catch (error) {
            console.error('Error fetching customers:', error);
        }
    }

    function paginateCustomers() {
        const list = document.getElementById('customerList');
        const paginationContainer = document.getElementById('customerPagination');
        let totalPages = Math.ceil(customers.length / itemsPerPage);

        function renderList() {
            list.innerHTML = "";
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const paginatedItems = customers.slice(start, end);

            paginatedItems.forEach(cust => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${cust.name}</td>
                    <td><button class="btn btn-sm btn-primary selectCustomer" data-id="${cust.id}" data-name="${cust.name}" data-email="${cust.email}">SELECT</button></td>
                `;
                list.appendChild(row);
            });

            renderPaginationControls(paginationContainer, totalPages);
        }

        function renderPaginationControls(container, totalPages) {
            container.innerHTML = "";

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
    }

    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('selectCustomer')) {
            selectedCustomer = {
                id: event.target.dataset.id,
                name: event.target.dataset.name,
                email: event.target.dataset.email
            };

            document.getElementById('CName').textContent = selectedCustomer.name || 'N/A';
            document.getElementById('CEmail').textContent = selectedCustomer.email || 'N/A';
            document.getElementById('CId').textContent = selectedCustomer.id || 'N/A';
        }
    });

    document.getElementById('searchCustomer').addEventListener('input', function (event) {
        const searchText = event.target.value.toLowerCase();
        customers = searchText ? allCustomers.filter(cust => cust.name.toLowerCase().includes(searchText)) : [...allCustomers];
        currentPage = 1;
        paginateCustomers();
    });

    fetchCustomers();
});
