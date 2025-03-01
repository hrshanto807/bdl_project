@extends('layout.sidebar')

@section('content')
<div class="container mx-auto mt-4 p-6 bg-white rounded-lg shadow">
    <h2 class="text-2xl font-semibold mb-6">Create Product</h2>

    <!-- Display Success or Error Flash Message -->
    @if (session('success'))
    <div class="mb-4 text-green-600">
        {{ session('success') }}
    </div>
    @elseif (session('error'))
    <div class="mb-4 text-red-600">
        {{ session('error') }}
    </div>
    @endif

    <!-- Display Validation Errors -->
    @if ($errors->any())
    <div class="mb-4">
        <ul class="text-red-600">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('createProduct') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

        <!-- Product Name -->
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Product Name</label>
            <input type="text" id="name" name="name" class="w-full p-2 border rounded" value="{{ old('name') }}" required>
        </div>

        <!-- Category -->
        <div class="mb-4">
            <label for="category_id" class="block text-gray-700">Category</label>
            <select class="form-control form-select" id="productCategory" name="category_id" required>
                <option value="">Select Category</option>
                <!-- Categories will be dynamically populated here -->
            </select>
        </div>

        <!-- Price -->
        <div class="mb-4">
            <label for="price" class="block text-gray-700">Price</label>
            <input type="text" id="price" name="price" class="w-full p-2 border rounded" value="{{ old('price') }}" required>
        </div>

        <!-- Unit -->
        <div class="mb-4">
            <label for="unit" class="block text-gray-700">Unit</label>
            <input type="text" id="unit" name="unit" class="w-full p-2 border rounded" value="{{ old('unit') }}" required>
        </div>

        <!-- Image Upload -->
        <div class="mb-4">
            <label for="img_url" class="block text-gray-700">Product Image</label>
            <input type="file" id="img_url" name="img_url" class="w-full p-2 border rounded" required onchange="previewImage(event)">

            <!-- Image Preview -->
            <div class="mt-3">
                <img id="imagePreview" src="#" alt="Image Preview" class="hidden w-32 h-32 rounded shadow">
            </div>
        </div>


        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create Product</button>
        </div>
    </form>
</div>

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script>
    async function FillCategoryDropDown() {
        try {
            let res = await axios.get("/get-categories");
            res.data['rows'].forEach(function(item) {
                let option = `<option value="${item['id']}">${item['name']}</option>`;
                document.getElementById("productCategory").innerHTML += option;
            });
        } catch (error) {
            console.error("Error fetching categories:", error);
        }
    }

    FillCategoryDropDown();

    function previewImage(event) {
        let reader = new FileReader();
        reader.onload = function() {
            let preview = document.getElementById("imagePreview");
            preview.src = reader.result;
            preview.classList.remove("hidden");
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endsection