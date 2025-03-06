<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Exception;

class CustomerController extends Controller
{
    // create customer
    public function CreateCustomer(Request $request)
    {
        try {
            // Validate the input
            $request->validate([
                'name' => 'required|string|max:50',
                'email' => 'required|string|email|max:50',
                'mobile' => 'required|string|min:11', // Validate the mobile field

            ]);

            // Get the authenticated user ID
            $user_id = Auth::id();

            // Create the customer
            Customer::create([
                "name" => $request->input('name'),
                "email" => $request->input("email"),
                "mobile" => $request->input("mobile"), // Using mobile as per validation

                "user_id" => $user_id,
            ]);

            // Redirect to the customer list page with success message
            return redirect()->route('customerList')->with('status', 'Customer Created Successfully');
        } catch (Exception $e) {
            // Handle error and redirect back to the create page with error message
            return redirect()->route('AddCustomer')->with('error', 'Failed to create customer: ' . $e->getMessage());
        }
    }




    // customer list
    public function CustomerList(Request $request)
    {
        // Get the authenticated user's ID
        $user_id = Auth::id();

        // Fetch customers for the authenticated user and paginate them
        $customers = Customer::where('user_id', '=', $user_id)
            ->orderBy('created_at', 'desc')  // Optional: Order by creation date
            ->paginate(5);  // Paginate the results, adjust the number as needed

        // Return the view with the customers data
        return view('customer.list-customer', compact('customers'));
    }

    // customer delete
    public function DeleteCustomer(Request $request)
    {
        try {
            // Validate the ID
            $request->validate([
                'id' => 'required|string|min:1',
            ]);

            $customer_id = $request->input('id');
            $user_id = Auth::id();

            // Find and delete the customer by ID and user_id
            $customer = Customer::where('id', '=', $customer_id)->where('user_id', '=', $user_id)->first();

            // If customer not found, return an error
            if (!$customer) {
                return redirect()->route('customerList')->with('error', 'Customer not found');
            }

            // Delete the customer
            $customer->delete();

            // Redirect to the customer list page with success message
            return redirect()->route('customerList')->with('status', 'Customer Deleted Successfully');
        } catch (Exception $e) {
        }
    }

    // customer update
    public function updateCustomer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:50',
            'mobile' => 'required|string|min:11',

        ]);

        $customer = Customer::findOrFail($request->id);
        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,

        ]);

        return redirect()->route('customerList')->with('status', 'Customer updated successfully!');
    }


    public function AddCustomer()
    {
        return view('customer.create-customer');
    }
}
