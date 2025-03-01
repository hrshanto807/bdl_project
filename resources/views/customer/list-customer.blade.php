@extends('layout.sidebar')

@section('title', 'Customer List')

@section('content')
<div class="container">
    <div class="card p-4 shadow-sm rounded">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold">Customer List</h4>
            <a href="{{ route('AddCustomer') }}" class="btn btn-primary rounded-pill px-4">Create Customer</a>
        </div>

        <!-- Display Success or Error Messages -->
        @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <!-- Customer List Table -->
        @if($customers->isEmpty())
        <p>No customers available</p>
        @else
        <div class="table-responsive mt-4">
            <table class="table table-striped">
                <thead class="bg-light">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $index => $customer)
                    <tr>
                        <td>{{ $index + 1 + ($customers->currentPage() - 1) * $customers->perPage() }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->mobile }}</td>
                        <td>{{ $customer->address }}</td>
                        <td>
                            <button onclick="openEditModal('{{ $customer->id }}', '{{ $customer->name }}', '{{ $customer->email }}', '{{ $customer->mobile }}', '{{ $customer->address }}')" class="btn btn-outline-success btn-sm rounded-pill px-3">EDIT</button>

                            <!-- Delete Button -->
                            <button onclick="confirmDelete(event,'{{ route('deleteCustomer') }}', '{{ $customer->id }}')"
                                class="btn btn-outline-danger btn-sm rounded-pill px-3">DELETE</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center mt-4">
            {{ $customers->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Edit Customer Modal -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCustomerLabel">Edit Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCustomerForm" method="POST" action="{{ route('editCustomer') }}">
                    @csrf
                    <input type="hidden" name="id" id="editCustomerId">

                    <div class="mb-3">
                        <label for="editCustomerName" class="form-label">Customer Name *</label>
                        <input type="text" class="form-control" name="name" id="editCustomerName" value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="editCustomerEmail" class="form-label">Customer Email *</label>
                        <input type="email" class="form-control" name="email" id="editCustomerEmail" value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="editCustomerMobile" class="form-label">Customer Mobile *</label>
                        <input type="text" class="form-control" name="mobile" id="editCustomerMobile" value="{{ old('mobile') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="editCustomerAddress" class="form-label">Customer Address *</label>
                        <input type="text" class="form-control" name="address" id="editCustomerAddress" value="{{ old('address') }}" required>

                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-success">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to open the Edit Modal and fill in the data
    function openEditModal(id, name, email, mobile, address) {
        document.getElementById('editCustomerId').value = id;
        document.getElementById('editCustomerName').value = name;
        document.getElementById('editCustomerEmail').value = email;
        document.getElementById('editCustomerMobile').value = mobile;
        document.getElementById('editCustomerAddress').value = address;

        let form = document.getElementById('editCustomerForm');
        form.action = "{{ route('editCustomer') }}"; // Ensure the form submits normally

        // Show the modal
        let modal = new bootstrap.Modal(document.getElementById('editCustomerModal'));
        modal.show();
    }

    // Delete Confirmation Function
    function confirmDelete(event, url, id) {
        event.preventDefault(); // Prevent default form submission

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

                let csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);

                let idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'id';
                idInput.value = id;
                form.appendChild(idInput);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

@endsection
