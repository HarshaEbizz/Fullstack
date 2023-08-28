<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
            'category_image' => 'required|mimes:jpeg,png,jpg|max:2048'
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'code' => 202, 'message' => implode("<br>", $validator->errors()->all())], 401);
        }
        $category = new Category();
        $category->category_name = $request->category_name;
        if ($request->hasFile('category_image') && $request->file('category_image')->isValid()) {
            $fileName = random_int(1000000000, 9999999999)  . '.png';
            $request->category_image->storeAs('/public/categories/' . $fileName);
            $category->category_image = 'categories/' . $fileName;
        }
        $category->save();
        return Response(['status' => true, 'message' => 'Category added successfully.', 'data' => $category], 200);
    }

    public function show()
    {
        return Category::orderBy('id', 'desc')->get();
    }

    public function edit(Category $category)
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category)
    {
        // dd($request);
        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
            'category_image' => 'mimes:jpeg,png,jpg|max:2048'
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'code' => 202, 'message' => implode("<br>", $validator->errors()->all())], 202);
        }
        $category->category_name = $request->category_name;
        if ($request->hasFile('category_image') && $request->file('category_image')->isValid()) {
            $path = storage_path() . '/app/public/' . $category->category_image;
            if ($category->category_image) {
                if (File::exists($path)) {
                    unlink($path);
                }
            }

            $fileName = random_int(1000000000, 9999999999)  . '.png';
            $request->category_image->storeAs('/public/categories/' . $fileName);
            $category->category_image = 'categories/' . $fileName;
        }
        $category->save();
        
        return response()->json(['success' => true, 'message' => 'Category updated successfully', 'data' => []], 200);
    }

    public function destroy(Category $category)
    {
        $path = storage_path() . '/app/public/' . $category->category_image;
        if ($category->category_image) {
            if (File::exists($path)) {
                unlink($path);
            }
        }
        if ($category->delete()) {
            return response()->json(['success' => true, 'message' => 'Category has been deleted.', 'data' => []], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Something went wrong.', 'data' => []], 200);
        }
    }
}