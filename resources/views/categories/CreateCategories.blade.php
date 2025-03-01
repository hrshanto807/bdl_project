@extends('layout.sidebar')

@section('content')
<div class="container">
    <div class="card p-4 shadow-sm rounded">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold">Create Category</h5>
        </div>

        <form id="create-category-form" action="{{ route('createCategory') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Category Name *</label>
                <input type="text" class="form-control" name="name" id="category-name" required>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('categoryList') }}" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-success">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection