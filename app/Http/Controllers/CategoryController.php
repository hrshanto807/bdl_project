<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class CategoryController extends Controller
{
    // category create
    
    public function CreateCategory(Request $request ){

        try {   
            $request->validate([
                'name' => 'required|string|max:50',
            ]);
            $user_id = Auth::id();
            Category::create([
                "name"=>$request->input('name'),
                "user_id"=>$user_id,
            ]);
            return response()->json([
                "status"=> "success",
                "message" => "Category Created Successfully"  
                ]);
        } catch (Exception $e){
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
        }

    }

    public function CategoryList(Request $request){

        $user_id = Auth::id();
        $category = Category::where('user_id','=',$user_id)->get();
        return response()->json([
            "status" => "success",
            "message" => "Category List",
            "data" => $category
        ]);

        
    }

    public function DeleteCategory(Request $request){

        try{
            $request->validate([
                'id' => 'required|string|min:1'
            ]);
            $category_id = $request->input('id');  
            $user_id= Auth::id();
            Category::where('id','=',$category_id)->where('user_id','=',$user_id)->delete();
            return response()->json([
                "status" => "success",
                "message" => "Category Deleted Successfully"
            ]);
        }catch (Exception $e){
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
        }
    }

    public function UpdateCategory(Request $request){

        try {
            $request->validate([
                'name' => 'required|string|max:50',
                'id'=>'required|min:1',
            ]);

            $category_id=$request->input('id');
            $user_id=Auth::id();
            Category::where('id',$category_id)->where('user_id',$user_id)->update([
                'name'=>$request->input('name'),
            ]);
            return response()->json(['status' => 'success', 'message' => "Request Successful"]);
        }catch (Exception $e){
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
        }
    }
}
