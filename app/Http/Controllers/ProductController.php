<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\File;



class ProductController extends Controller
{
    // create product

    public function CreateProduct(Request $request) {
      try {
          $request->validate([
              'name' => 'required|string|max:50',
              'user_id' => 'required|string|min:1',
              'category_id' => 'required|string|min:1',
              'price' => 'required|string|min:1',
              'unit' => 'required|string|min:1',                            
          ]);
  
          $user_id = Auth::id();
          $category_id = $request->input('category_id');
          $img = $request->file('img_url');
  
          if (!$img) {
              return response()->json(['status' => 'fail', 'message' => 'Image file is missing.']);
          }
  
          $file_name = $img->getClientOriginalName();
          $img_name = "{$user_id}-" . time() . "-{$file_name}";
          $img_url = "uploads/{$img_name}";
  
          $img->move(public_path('uploads'), $img_name);
  
          $product = Product::create([
              'name' => $request->input('name'),
              'user_id' => $user_id,
              'category_id' => $category_id,
              'price' => $request->input('price'),
              'unit' => $request->input('unit'),
              'img_url' => $img_url,
          ]);
  
          return response()->json(['status' => 'success', 'product' => $product]);
      } catch (Exception $e) {
          return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
      }
  }

  public function ProductDelete(Request $request)
{
    try {    
        $request->validate([
            "id" => 'required|string',           
        ]);         

        $user_id = Auth::id();

        // Fetch the product to get the image URL
        $product = Product::where('id', $request->input('id'))->where('user_id', $user_id)->first();

        if (!$product) {
            return response()->json(['status' => 'fail', 'message' => 'Product not found.']);
        }

        // Get the absolute path of the image
        $filePath = public_path($product->img_url);

        // Check if the file exists and delete it
        if (file_exists($filePath)) {
            unlink($filePath); // Delete the file
        }

        // Delete the product from the database
        $product->delete();

        return response()->json(['status' => 'success', 'message' => "Product and image deleted successfully."]);
    
    } catch (Exception $e) {
        return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
    }
}


    public function ProductList(Request $request) {
        $user_id = Auth::id();
        $product = Product::where('user_id','=',$user_id)->get();
        return response()->json([
            "status" => "success",
            "message" => "Product List",
            "data" => $product
        ]);
    }


       
    public function ProductUpdate(Request $request)
    {
        try {
            $user_id = Auth::id();
            $product_id = $request->input('id');
    
            // Validate input
            $request->validate([
                'id' => 'required|exists:products,id',
                'name' => 'required|string|max:50',
                'price' => 'required|string|min:1',
                'unit' => 'required|string|min:1',
                'category_id' => 'required|string|min:1',
               
            ]);
    
            // Retrieve the product
            $product = Product::where('id', $product_id)->where('user_id', $user_id)->first();
    
            if (!$product) {
                return response()->json(['status' => 'fail', 'message' => 'Product not found.']);
            }
    
            // Prepare updated data
            $updateData = [
                'name' => $request->input('name'),
                'price' => $request->input('price'),
                'unit' => $request->input('unit'),
                'category_id' => $request->input('category_id'),
            ];
    
            // Handle image update
            if ($request->hasFile('img')) {
                // Delete the old image if it exists
                if ($product->img_url) {
                    $oldImagePath = public_path($product->img_url);
                    if (File::exists($oldImagePath)) {
                        File::delete($oldImagePath);
                    }
                }
    
                // Upload the new image
                $img = $request->file('img');
                $file_name = time() . '-' . $img->getClientOriginalName();
                $img->move(public_path('uploads'), $file_name);
                $img_url = "uploads/{$file_name}";
    
                // Update the image URL in the database
                $updateData['img_url'] = $img_url;
            }
    
            // Update product details
            $product->update($updateData);
    
            return response()->json(['status' => 'success', 'message' => 'Product updated successfully.', 'product' => $product]);
    
        } catch (Exception $e) {
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()]);
        }
    }












    // view product
             

}
