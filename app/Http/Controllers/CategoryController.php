<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class CategoryController extends Controller
{
    public function CreateCategory(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:50',
            ]);
            $user_id = Auth::id();
            Category::create([
                "name" => $request->input('name'),
                "user_id" => $user_id,
            ]);

            // Redirect with success message
            return redirect()->route('categoryList')->with('status', 'Category Created Successfully');
        } catch (Exception $e) {
            // Redirect with error message
            return redirect()->route('AddCategory')->with('status', 'Failed to create category: ' . $e->getMessage());
        }
    }


    public function CategoryList(Request $request)
{
    $user_id = Auth::id();

    // Start the query for categories
    $query = Category::where('user_id', '=', $user_id)
                     ->orderBy('created_at', 'desc');

    // Apply search filter if search term is provided
    if ($request->has('search') && $request->search != '') {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // Get the paginated categories
    $categories = $query->paginate(5);

    return view('categories.category-list', compact('categories'));
}

    public function DeleteCategory(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|string|min:1'
            ]);

            $category_id = $request->input('id');
            $user_id = Auth::id();

            // Delete the category that matches the ID and user ID
            Category::where('id', '=', $category_id)->where('user_id', '=', $user_id)->delete();

            return redirect()->route('categoryList')->with('status', 'Category Deleted Successfully');
        } catch (Exception $e) {
            return redirect()->route('categoryList')->with('status', 'Failed to delete category');
        }
    }


    public function UpdateCategory(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:50',
                'id' => 'required|min:1',
            ]);

            $category_id = $request->input('id');
            $user_id = Auth::id();

            Category::where('id', $category_id)->where('user_id', $user_id)->update([
                'name' => $request->input('name'),
            ]);

            return redirect()->route('categoryList')->with('status', 'Category updated successfully!');
        } catch (Exception $e) {
            return redirect()->route('categoryList')->with('error', $e->getMessage());
        }
    }



    // view category

    public function CategoryPage()
    {
        return view('page.categoryPage');
    }

    public function addCategory()
    {
        return view('categories.CreateCategories'); // Ensure you return the view here
    }
    public function ListCategory()
    {
        return view('categories.category-list');
    }


    public function getCategories()
    {
        $categories = Category::all();
        return response()->json(['rows' => $categories]);
    }
}
