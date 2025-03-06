<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Illuminate\Http\Request;
use App\Models\Product;



class InvoiceController extends Controller
{
    public function invoiceCreate(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validate input
            $request->validate([
                'total' => 'required|numeric',
                'discount' => 'required|numeric',
                'vat' => 'required|numeric',
                'payable' => 'required|numeric',
                'customer_id' => 'required|exists:customers,id',
                'products' => 'required|array|min:1', // Ensure at least one product
                'products.*.product_id' => 'required|exists:products,id',
                'products.*.qty' => 'required|integer|min:1',
                'products.*.sale_price' => 'required|numeric|min:0',
            ]);

            // Retrieve request data
            $user_id = Auth::id();
            $total = $request->input('total');
            $discount = $request->input('discount');
            $vat = $request->input('vat');
            $payable = $request->input('payable');
            $customer_id = $request->input('customer_id');

            // Create invoice
            $invoice = Invoice::create([
                'total' => $total,
                'discount' => $discount,
                'vat' => $vat,
                'payable' => $payable,
                'user_id' => $user_id,
                'customer_id' => $customer_id,
            ]);

            if (!$invoice) {
                throw new Exception('Invoice creation failed!');
            }

            $invoiceID = $invoice->id;
            $products = $request->input('products');

            foreach ($products as $EachProduct) {
                // Fetch the product model
                $productModel = Product::find($EachProduct['product_id']);

                if (!$productModel) {
                    throw new Exception('Product not found: ' . $EachProduct['product_id']);
                }

                // Check stock availability
                if ($productModel->unit < $EachProduct['qty']) {
                    throw new Exception('Not enough stock for product: ' . $productModel->name);
                }

                // Create invoice product entry
                InvoiceProduct::create([
                    'invoice_id' => $invoiceID,
                    'user_id' => $user_id,
                    'product_id' => $EachProduct['product_id'],
                    'qty' => $EachProduct['qty'],
                    'sale_price' => $EachProduct['sale_price'],
                ]);

                // Reduce product stock
                $productModel->decrement('unit', $EachProduct['qty']);
            }

            DB::commit();

            return response()->json(['status' => 'success', 'message' => "Invoice created successfully!", 'invoice_id' => $invoiceID], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()], 400);
        }
    }

    


    public function invoiceSelect(Request $request)
{
    try {
        $user_id = Auth::id();
        $rows = Invoice::where('user_id', $user_id)->with('customer')->get();
        return response()->json(['status' => 'success', 'rows' => $rows]);
    } catch (Exception $e) {
        return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);  
    }
}



    public function InvoiceDetails(Request $request){
        try {
            $user_id=Auth::id();
            $customerDetails=Customer::where('user_id',$user_id)->where('id',$request->input('cus_id'))->first();
            $invoiceTotal=Invoice::where('user_id','=',$user_id)->where('id',$request->input('inv_id'))->first();
            $invoiceProduct=InvoiceProduct::where('invoice_id',$request->input('inv_id'))->where('user_id',$user_id)->with('product')->get();
            $rows= array(
                'customer'=>$customerDetails,
                'invoice'=>$invoiceTotal,
                'product'=>$invoiceProduct,
            );
            return response()->json(['status' => 'success', 'rows' => $rows]);
        }
        catch (Exception $e){
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
        }
    }

    public function invoiceDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            $user_id = Auth::id();
            $invoice_id = $request->input('id');  // This should match the input name from the JavaScript
    
            InvoiceProduct::where('invoice_id', $invoice_id)->where('user_id', $user_id)->delete();
            Invoice::where('id', $invoice_id)->delete();
    
            DB::commit();
            return back()->with('status', 'Invoice deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
    


    public function customer_list(Request $request)
{
    $search = $request->query('search'); // Get search query

    $customers = Customer::when($search, function($query, $search) {
        return $query->where('name', 'like', "%{$search}%");
    })->get(); // Return all matching customers

    return response()->json($customers);
}

    public function invoiceList(Request $request)
{
    try {
        $user_id = Auth::id();
        $search = $request->input('search');
        
        $query = Invoice::where('user_id', $user_id)->with('customer');
        
        if ($search) {
            $query->whereHas('customer', function($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }

        $invoices = $query->paginate(10); // Add pagination
        
        return view('invoice.invoice-list', compact('invoices'));
    } catch (Exception $e) {
        return back()->with('error', $e->getMessage());
    }
}

public function      showInvoice($cus_id, $inv_id)
{
    return view('invoice.invoice-details', compact('cus_id', 'inv_id'));
}

public function editInvoice()                  
{
    return view('invoice.create-sale');
}



 

}