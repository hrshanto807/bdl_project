@extends('layout.sidebar')

@section('content')
<div class="container">
    <div class="card p-4 shadow-sm rounded">
        <!-- Category List Section -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold">Category</h4>
            <a href="{{ route('AddCategory') }}" class="btn btn-primary rounded-pill px-4">CREATE</a>
        </div>

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


        <!-- Category List Table -->
        @if($categories->isEmpty())  <!-- Check if there are categories -->
            <p>No categories available</p>
        @else
            <div class="table-responsive mt-4">
                <table class="table table-striped">
                    <thead class="bg-light">
                        <tr>
                            <th>No</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $index => $category)
                            <tr>
                                <td>{{ $index + 1 + ($categories->currentPage() - 1) * $categories->perPage() }}</td> <!-- Adjusted index for pagination -->
                                <td>{{ $category->name }}</td>
                                <td>                                   
                                <button onclick="openEditModal('{{ $category->id }}', '{{ $category->name }}')" class="btn btn-outline-success btn-sm rounded-pill px-3">EDIT</button>

                                    <!-- Delete Button -->
                                    <button onclick="confirmDelete(event,'{{ route('deleteCategory') }}', '{{ $category->id }}')" 
                                        class="btn btn-outline-danger btn-sm rounded-pill px-3">DELETE</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div class="d-flex justify-content-center mt-4">
                {{ $categories->links() }} <!-- Pagination links -->
            </div>
        @endif
    </div>
</div>
<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryLabel">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCategoryForm" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="editCategoryId">

                    <div class="mb-3">
                        <label for="editCategoryName" class="form-label">Category Name *</label>
                        <input type="text" class="form-control" name="name" id="editCategoryName" required>
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
    function openEditModal(id, name) {
        document.getElementById('editCategoryId').value = id;
        document.getElementById('editCategoryName').value = name;

        let form = document.getElementById('editCategoryForm');
        form.action = "{{ route('updateCategory') }}"; // Ensure the form submits normally

        let modal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
        modal.show();
    }
    function confirmDelete(event, url, id) {
        event.preventDefault(); // Prevent default form submission
        
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!",
            showClass: {
                popup: 'swal2-show animate__animated animate__fadeIn' // Add fade-in animation
            },
            hideClass: {
                popup: 'swal2-hide animate__animated animate__fadeOut' // Add fade-out animation
            }
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