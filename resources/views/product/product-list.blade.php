@extends('layout.sidebar')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-lg shadow">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold">Product</h2>

        <!-- Search Form -->
        <form method="GET" action="{{ route('productList') }}" class="flex space-x-4 items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Product" class="p-2 border rounded">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Search</button>
        </form>

        <a href="{{ route('productAdd') }}" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">CREATE</a>
    </div>

    @if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="w-full border-collapse border border-gray-200">
        <thead class="bg-gray-100">
            <tr class="text-center">
                <th class="border px-4 py-2">Image</th>
                <th class="border px-4 py-2">Name</th>
                <th class="border px-4 py-2">Category</th>
                <th class="border px-4 py-2">Price</th>
                <th class="border px-4 py-2">Unit</th>
                <th class="border px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr class="text-center">
                <td class="border px-4 py-2"><img src="{{ asset($product->img_url) }}" alt="Product" class="w-10 h-10 mx-auto"></td>
                <td class="border px-4 py-2">{{ $product->name }}</td>
                <td class="border px-4 py-2">{{ $product->category->name ?? 'N/A' }}</td>
                <td class="border px-4 py-2">{{ $product->price }}</td>
                <td class="border px-4 py-2">{{ $product->unit }}</td>
                <td class="border px-4 py-2">
                    <button onclick="openEditModal({{ json_encode($product) }})" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">EDIT</button>
                    <button onclick="confirmDelete(event, '{{ route('deleteProduct') }}', '{{ $product->id }}', '{{ $product->category_id }}')" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">DELETE</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $products->appends(request()->query())->links() }}
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
        <h2 class="text-xl font-semibold mb-4">Edit Product</h2>
        <form id="editProductForm" action="{{ route('updateProduct') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="editProductId" name="id">
            <label class="block text-gray-700">Name</label>
            <input type="text" id="editName" name="name" class="w-full p-2 border rounded mb-3">
            <label class="block text-gray-700">Category</label>
            <select id="editCategory" name="category_id" class="w-full p-2 border rounded mb-3"></select>
            <label class="block text-gray-700">Price</label>
            <input type="text" id="editPrice" name="price" class="w-full p-2 border rounded mb-3">
            <label class="block text-gray-700">Unit</label>
            <input type="text" id="editUnit" name="unit" class="w-full p-2 border rounded mb-3">
            <label class="block text-gray-700">Product Image</label>
            <input type="file" id="editImgUrl" name="img_url" class="w-full p-2 border rounded mb-3" onchange="previewImage(event)">
            <img id="editImagePreview" class="w-20 h-20 mt-2 hidden" alt="Preview">
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    async function openEditModal(product) {
        document.getElementById("editProductId").value = product.id;
        document.getElementById("editName").value = product.name;
        document.getElementById("editPrice").value = product.price;
        document.getElementById("editUnit").value = product.unit;
        if (product.img_url) {
            document.getElementById("editImagePreview").src = "{{ asset('') }}" + product.img_url;
            document.getElementById("editImagePreview").classList.remove("hidden");
        }
        let response = await axios.get("/get-categories");
        let categoryDropdown = document.getElementById("editCategory");
        categoryDropdown.innerHTML = '<option value="">Select Category</option>';
        response.data.rows.forEach(category => {
            let selected = category.id == product.category_id ? 'selected' : '';
            categoryDropdown.innerHTML += `<option value="${category.id}" ${selected}>${category.name}</option>`;
        });
        document.getElementById("editModal").classList.remove("hidden");
    }

    function closeEditModal() {
        document.getElementById("editModal").classList.add("hidden");
    }

    function previewImage(event) {
        let reader = new FileReader();
        reader.onload = function() {
            let preview = document.getElementById("editImagePreview");
            preview.src = reader.result; // Set the preview to the selected file
            preview.classList.remove("hidden"); // Ensure it's visible
        };
        reader.readAsDataURL(event.target.files[0]); // Read the selected file
    }

    function confirmDelete(event, url, id, categoryId) {
        event.preventDefault();
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
                form.innerHTML = `<input type='hidden' name='_token' value='{{ csrf_token() }}'>
                              <input type='hidden' name='id' value='${id}'>
                              <input type='hidden' name='category_id' value='${categoryId}'>`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endsection