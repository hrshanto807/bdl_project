
@extends('layout.sidebar')

@section('title', 'Dashboard')

@section('content')
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card p-3 text-center">
            <i class="fas fa-box fa-2x mb-2 text-primary"></i>
            <div>0 Products</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 text-center">
            <i class="fas fa-list fa-2x mb-2 text-secondary"></i>
            <div>0 Categories</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 text-center">
            <i class="fas fa-users fa-2x mb-2 text-success"></i>
            <div>0 Customers</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 text-center">
            <i class="fas fa-file-invoice fa-2x mb-2 text-danger"></i>
            <div>0 Invoices</div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-4">
        <div class="card p-3 text-center">
            <i class="fas fa-dollar-sign fa-2x mb-2 text-info"></i>
            <div>$0 Total Sale</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 text-center">
            <i class="fas fa-receipt fa-2x mb-2 text-warning"></i>
            <div>$0 VAT Collection</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 text-center ">
            <i class="fas fa-wallet fa-2x mb-2 text-dark"></i>
            <div>$0 Total Collection</div>
        </div>
    </div>
</div>
@endsection