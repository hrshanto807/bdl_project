@extends('layout.sidebar')

@section('title', 'Dashboard')

@section('content')
<div class="row mt-4">
    <div class="col-md-3">
        <a href="{{ route('productList') }}" class="card p-3 text-center hover-effect">
            <i class="fas fa-box fa-2x mb-2 text-primary"></i>
            <div>{{ \App\Models\Product::count() }} Products</div> <!-- Dynamic product count -->
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ route('categoryList') }}" class="card p-3 text-center hover-effect">
            <i class="fas fa-list fa-2x mb-2 text-secondary"></i>
            <div>{{ \App\Models\Category::count() }} Categories</div> <!-- Dynamic category count -->
        </a>
    </div>
    <div class="col-md-3">
        <a href="#" class="card p-3 text-center hover-effect">
            <i class="fas fa-users fa-2x mb-2 text-success"></i>
            <div>{{ \App\Models\Customer::count() }} Customers</div> <!-- Dynamic customer count -->
        </a>
    </div>
    <div class="col-md-3">
        <a href="#" class="card p-3 text-center hover-effect">
            <i class="fas fa-file-invoice fa-2x mb-2 text-danger"></i>
            <div>{{ \App\Models\Invoice::count() }} Invoices</div> <!-- Dynamic invoice count -->
        </a>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-4">
        <div class="card p-3 text-center hover-effect">
            <i class="fas fa-dollar-sign fa-2x mb-2 text-info"></i>
            <div>0 Total Sale</div> <!-- Dynamic total sale -->
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 text-center hover-effect">
            <i class="fas fa-receipt fa-2x mb-2 text-warning"></i>
            <div>0 VAT Collection</div> <!-- Dynamic VAT collection -->
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 text-center hover-effect">
            <i class="fas fa-wallet fa-2x mb-2 text-dark"></i>
            <div>0 Total Collection</div> <!-- Dynamic total collection -->
        </div>
    </div>
</div>
@endsection


