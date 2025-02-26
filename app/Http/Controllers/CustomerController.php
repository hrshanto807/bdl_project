<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Exception;

class CustomerController extends Controller
{
    // create customer
    public function CreateCustomer(Request $request){

        try{$request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:50',
            'mobile' => 'required|string|min:11'
        ]);

  $user_id = Auth::id();

  Customer::create([
    "name"=>$request->input('name'),
    "email"=>$request->input("email"),
    "mobile"=>$request->input("mobile"),
    "user_id"=>$user_id,  
  ]);

  return response()->json([
      "status"=> "success",
      "message" => "Customer Created Successfully"  
  ]);}
  catch (Exception $e){
    return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
}

    }

    // customer list
    public function CustomerList(){
      $user_id = Auth::id();
      $customer = Customer::where('user_id','=',$user_id)->get();
      return response()->json([
        "status" => "success",
        "message" => "Customer List",
        "data" => $customer
      ]);
    }

    // customer delete

    public function DeleteCustomer(Request $request){

       try{
        $request->validate([
            'id' => 'required|string|min:1'
        ]);
      $customer_id = $request->input('id');  
      $user_id= Auth::id();
      Customer::where('id','=',$customer_id)->where('user_id','=',$user_id)->delete();
      return response()->json([
        "status" => "success",
        "message" => "Customer Deleted Successfully"
      ]);
       }catch (Exception $e){
        return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
       }
     
    }

    // customer update
    public function UpdateCustomer(Request $request){

        try {
            $request->validate([
                'name' => 'required|string|max:50',
                'email' => 'required|string|email|max:50',
                'mobile' => 'required|string|min:11',
                'id'=>'required|min:1',
            ]);

            $customer_id=$request->input('id');
            $user_id=Auth::id();
            Customer::where('id',$customer_id)->where('user_id',$user_id)->update([
                'name'=>$request->input('name'),
                'email'=>$request->input('email'),
                'mobile'=>$request->input('mobile'),
            ]);
            return response()->json(['status' => 'success', 'message' => "Request Successful"]);
        }catch (Exception $e){
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
        }
   }
}
