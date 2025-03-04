@extends('layout.sidebar')

@section('content')
<div class="container">
    <div class="card p-4 shadow-sm rounded">
        <!-- Search Form -->
        <form method="GET" action="{{ route('invoiceList') }}" class="d-flex justify-content-between align-items-center mb-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by customer name..." class="form-control w-50">
            <button type="submit" class="btn btn-primary rounded-pill px-4">Search</button>
        </form>

        <!-- Invoice List Section -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold">Invoices</h4>
            <a href="{{ url('/test') }}" class="btn btn-primary rounded-pill px-4">CREATE</a>
        </div>

        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif


        <!-- Invoice List Table -->
        @if($invoices->isEmpty())
        <p>No invoices available</p>
        @else
        <div class="table-responsive mt-4">
            <table class="table table-striped">
                <thead class="bg-light">
                    <tr>
                        <th>No</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>VAT</th>
                        <th>Discount</th>
                        <th>Payable</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $index => $invoice)
                    <tr>
                        <td>{{ $index + 1 + ($invoices->currentPage() - 1) * $invoices->perPage() }}</td>
                        <td>{{ $invoice->customer->name }}</td>
                        <td>{{ $invoice->total }}</td>
                        <td>{{ $invoice->vat }}</td>
                        <td>{{ $invoice->discount }}</td>
                        <td>{{ $invoice->payable }}</td>
                        <td>
                            <!-- View Button -->
                            <button onclick="viewInvoice('{{ $invoice->id }}')" class="btn btn-outline-dark btn-sm rounded-pill px-3">VIEW</button>

                            <!-- Delete Button -->
                            <button onclick="confirmDelete(event, '{{ route('invoiceDelete') }}', '{{ $invoice->id }}')"
                                class="btn btn-outline-danger btn-sm rounded-pill px-3">DELETE</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center mt-4">
            {{ $invoices->links() }}
        </div>
        @endif
    </div>
</div>

<!-- resources/views/invoice/invoice_details.blade.php -->
<div class="modal fade" id="invoiceDetailsModal" tabindex="-1" aria-labelledby="invoiceDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="invoiceDetailsLabel">Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6><strong>Billed To:</strong></h6>
                        <p><strong>Name:</strong> <span id="customerName"></span></p>
                        <p><strong>Email:</strong> <span id="customerEmail"></span></p>
                        <p><strong>User ID:</strong> <span id="customerId"></span></p>
                    </div>
                    <div>
                        <img src="/path-to-logo.png" alt="Logo" width="100">
                        <p><strong>Date:</strong> <span id="invoiceDate"></span></p>
                    </div>
                </div>

                <table class="table mt-3">
                    <thead class="bg-light">
                        <tr>
                            <th>Name</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="invoiceProducts">
                        <!-- Dynamic product rows -->
                    </tbody>
                </table>

                <hr>
                <div class="d-flex justify-content-between">
                    <h6><strong>Total:</strong> $<span id="invoiceTotal"></span></h6>
                    <h6><strong>VAT (5%):</strong> $<span id="invoiceVat"></span></h6>
                    <h6><strong>Discount:</strong> $<span id="invoiceDiscount"></span></h6>
                    <h6><strong>Payable:</strong> $<span id="invoicePayable"></span></h6>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success rounded-pill px-4">Print</button>
            </div>
        </div>
    </div>
</div>


<script>
    function viewInvoice(invoiceId) {
        $.ajax({
            url: "{{ route('invoiceDetails') }}",
            method: "GET",
            data: {
                inv_id: invoiceId
            },
            success: function(response) {
                if (response.status === "success") {
                    let invoice = response.rows.invoice;
                    let customer = response.rows.customer;
                    let products = response.rows.product;

                    // Populate customer details
                    $("#customerName").text(customer.name);
                    $("#customerEmail").text(customer.email);
                    $("#customerId").text(customer.id);
                    $("#invoiceDate").text(invoice.date);

                    // Populate invoice details
                    $("#invoiceTotal").text(invoice.total);
                    $("#invoiceVat").text(invoice.vat);
                    $("#invoiceDiscount").text(invoice.discount);
                    $("#invoicePayable").text(invoice.payable);

                    // Populate product details
                    $("#invoiceProducts").empty();
                    products.forEach(product => {
                        $("#invoiceProducts").append(`
                        <tr>
                            <td>${product.product.name}</td>
                            <td>${product.qty}</td>
                            <td>${product.sale_price}</td>
                        </tr>
                    `);
                    });

                    // Show the modal
                    let modal = new bootstrap.Modal(document.getElementById('invoiceDetailsModal'));
                    modal.show();
                } else {
                    alert("Failed to fetch invoice details.");
                }
            },
            error: function() {
                alert("Error fetching invoice details.");
            }
        });
    }
</script>

@endsection