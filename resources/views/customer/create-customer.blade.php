@extends('layout.sidebar')

@section('title', 'Create Customer')

@section('content')
<div class="container">
    <div class="card p-4 shadow-sm rounded">
        <h4 class="fw-bold mb-4">Create Customer</h4>

        <!-- Show Success/Error Messages -->
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

        <!-- Customer Creation Form -->
        <form action="{{ route('CreateCustomer') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="customerName" class="form-label">Customer Name *</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="customerName" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="customerEmail" class="form-label">Customer Email *</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="customerEmail" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="customerMobile" class="form-label">Customer Mobile *</label>
                <input type="text" class="form-control @error('mobile') is-invalid @enderror" name="mobile" id="customerMobile" value="{{ old('mobile') }}" required>
                @error('mobile')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            

            <div class="mb-3">
                <button type="submit" class="btn btn-success">Create Customer</button>
                <a href="{{ route('customerList') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
