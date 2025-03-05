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



    public function CreateProduct(Request $request)
    {
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
                return redirect()->back()->with('error', 'Image file is missing.')->withInput();
            }

            $file_name = $img->getClientOriginalName();
            $img_name = "{$user_id}-" . time() . "-{$file_name}";
            $img_url = "uploads/{$img_name}";

            $img->move(public_path('uploads'), $img_name);

            Product::create([
                'name' => $request->input('name'),
                'user_id' => $user_id,
                'category_id' => $category_id,
                'price' => $request->input('price'),
                'unit' => $request->input('unit'),
                'img_url' => $img_url,
            ]);

            // Flash success message and redirect to the product list page
            return redirect()->route('productList')->with('success', 'Product created successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to create product. ' . $e->getMessage())->withInput();
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

            return redirect()->route('productList')->with('status', 'Product deleted successfully.');
        } catch (Exception $e) {
            return redirect()->route('productList')->with('status', 'Failed to delete Product');
        }
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
                'category_id' => 'required|exists:categories,id', // Ensure it exists in categories table
            ]);


            // Retrieve the product
            $product = Product::where('id', $product_id)->where('user_id', $user_id)->first();

            if (!$product) {
                return redirect()->back()->with('error', 'Product not found.');
            }

            // Prepare updated data
            $updateData = [
                'name' => $request->input('name'),
                'price' => $request->input('price'),
                'unit' => $request->input('unit'),
                'category_id' => $request->input('category_id'),
            ];

            // Handle image update
            if ($request->hasFile('img_url')) {
                // Delete the old image if it exists
                if ($product->img_url) {
                    $oldImagePath = public_path($product->img_url);
                    if (File::exists($oldImagePath)) {
                        File::delete($oldImagePath);
                    }
                }

                // Upload the new image
                $img = $request->file('img_url');
                $file_name = time() . '-' . $img->getClientOriginalName();
                $img_name = "{$user_id}-" . time() . "-{$file_name}";
                $img->move(public_path('uploads'), $img_name);
                $img_url = "uploads/{$img_name}";

                // Update the image URL in the database
                $updateData['img_url'] = $img_url;
            }

            // Update product details
            $product->update($updateData);

            return redirect()->route('productList')->with('success', 'Product updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->with('status', 'Failed to delete category');
        }
    }



    public function ProductList(Request $request)
    {
        $user_id = Auth::id();

        // Start the query
        $query = Product::query();
        // Apply search filter if query is provided
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;

            $query->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('price', 'like', '%' . $searchTerm . '%')
                    ->orWhere('unit', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('category', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        // Fetch the filtered and paginated products
        $products = $query->with('category') // Ensure category is eager loaded
            ->latest() // Order by latest
            ->paginate(5); // Paginate the filtered results

        // Pass the products to the view
        return view('Product.product-list', compact('products'));
    }


    // view product
    public function ProductPage()
    {
        return view('product.product-list');
    }

    public function productAdd()
    {
        return view('product.product-add');
    }

    public function product_list()
    {
        $products = Product::all();  // Fetch all products from the database
        return response()->json($products);
    }
}
