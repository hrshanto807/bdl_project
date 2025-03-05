@extends('layout.sidebar')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold">Invoices</h2>

        <!-- Search Form -->
        <form method="GET" action="{{ route('invoiceList') }}" class="flex space-x-4 items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by customer name..." class="p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-600">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600">Search</button>
        </form>

        <a href="{{ route('editInvoice') }}" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-600">CREATE</a>
    </div>

    <!-- Success and Error Alerts -->
    @if(session('status'))
    <div class="alert alert-success mb-4 p-3 bg-green-100 text-green-800 rounded">
        {{ session('status') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger mb-4 p-3 bg-red-100 text-red-800 rounded">
        {{ session('error') }}
    </div>
    @endif

    <!-- Invoice Table -->
    @if($invoices->isEmpty())
    <p class="text-gray-600">No invoices available.</p>
    @else
    <div class="overflow-x-auto">
        <table class="min-w-full table-auto border-collapse border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border border-gray-300">No</th>
                    <th class="px-4 py-2 border border-gray-300">Customer</th>
                    <th class="px-4 py-2 border border-gray-300">Total</th>
                    <th class="px-4 py-2 border border-gray-300">VAT</th>
                    <th class="px-4 py-2 border border-gray-300">Discount</th>
                    <th class="px-4 py-2 border border-gray-300">Payable</th>
                    <th class="px-4 py-2 border border-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $index => $invoice)
                <tr>
                    <td class="px-4 py-2 border border-gray-300">{{ $index + 1 + ($invoices->currentPage() - 1) * $invoices->perPage() }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ $invoice->customer->name }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ $invoice->total }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ $invoice->vat }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ $invoice->discount }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ $invoice->payable }}</td>                    
                    <td class="px-4 py-2 border border-gray-300">
                        <!-- View Button -->
                        <button onclick="viewInvoiceDetails('{{ $invoice->customer->id }}', '{{ $invoice->id }}')" class="bg-gray-600 text-white rounded px-4 py-2 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">VIEW</button>

                        <!-- Delete Button -->
                        <button onclick="confirmDelete(event, '{{ route('deleteInvoice') }}', '{{ $invoice->id }}')"
                                class="bg-red-600 text-white rounded px-4 py-2 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 ml-2">DELETE</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="mt-4">
        {{ $invoices->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@include('componands.sale-reportTem')
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    // Confirm Delete
    function confirmDelete(event, url, id) {
        event.preventDefault();

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');  // Fetch the CSRF token

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                
                // Add CSRF token and other necessary hidden fields
                form.innerHTML = `<input type='hidden' name='_token' value='${csrfToken}'>
                                  <input type='hidden' name='id' value='${id}'>`;

                // Append form and submit it
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function viewInvoiceDetails(cusId, invId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Fetch CSRF token

    axios.post('{{ route('invoiceDetails') }}', {
        cus_id: cusId,
        inv_id: invId,
        _token: csrfToken
    })
    .then(function (response) {
        if (response.data.status === 'success') {
            const data = response.data.rows;
            const customer = data.customer;
            const invoice = data.invoice;
            const products = data.product;

            document.getElementById('customerName').innerText = customer.name;
            document.getElementById('customerEmail').innerText = customer.email;
            document.getElementById('customerId').innerText = customer.id;
            document.getElementById('invoiceTotal').innerText = invoice.total;
            document.getElementById('invoiceVat').innerText = invoice.vat;
            document.getElementById('invoiceDiscount').innerText = invoice.discount;
            document.getElementById('invoicePayable').innerText = invoice.payable;

            // Add products to the modal
            const productsList = document.getElementById('productsList');
            productsList.innerHTML = ''; // Clear existing products
            products.forEach(product => {
                const row = `<tr>
                                <td class="border p-2">${product.product.name}</td>
                                <td class="border p-2">${product.qty}</td>
                                <td class="border p-2">$${product.product.price}</td>
                            </tr>`;
                productsList.innerHTML += row;
            });

            // Show modal
            document.getElementById('invoiceModal').classList.remove('hidden');
        } else {
            alert('Error: ' + response.data.message);
        }
    })
    .catch(function (error) {
        console.error('There was an error making the request:', error);
        alert('An error occurred while fetching invoice details.');
    });
}

function closeModal() {
    document.getElementById('invoiceModal').classList.add('hidden');
}

function printInvoice() {
    const modalContent = document.querySelector('#invoiceModal .p-6').innerHTML; // Select the modal content

    const printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write(`
        <html>
            <head>
                <title>Invoice</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid black; padding: 8px; text-align: left; }
                    h5 { margin-bottom: 10px; }
                </style>
            </head>
            <body>
                <h5>Invoice Details</h5>
                ${modalContent}
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

</script>

@endsection
