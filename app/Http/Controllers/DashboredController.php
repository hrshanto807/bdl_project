<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Sale; // Assuming you have a Sale model for total sales
use App\Models\Vat;   // Assuming you have a Vat model for VAT collection


class DashboredController extends Controller
{
    
public function dashboard()
{
    $productCount = Product::count();
    $categoryCount = Category::count();
    $customerCount = Customer::count();
    $invoiceCount = Invoice::count();
    // $totalSale = Sale::sum('amount');  
    // $vatCollection = Vat::sum('amount'); 
    // $totalCollection = $totalSale + $vatCollection;

    return view('dashboard', compact(
        'productCount', 
        'categoryCount', 
        'customerCount', 
        'invoiceCount',
        'totalSale', 
        'vatCollection', 
        'totalCollection'
    ));
}

}
