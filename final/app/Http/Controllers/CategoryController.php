<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('posts')->get();
        return response()->json($categories);
    }
    public function create()
    {
        $categories = Category::with('posts')->get();
        return response()->json($categories);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug', // slug debe ser único
        ]);

        $category = Category::create($validatedData);
        return response()->json($category, 201);
    }
    public function show(Category $category)
    {

        $categories = Category::with('posts')->findOrFail($category->id);
        return response()->json($categories);

    }
    public function edit(Category $category)
    {
        $categories = Category::with('posts')->findOrFail($category->id);
        return response()->json($categories);
    }
    public function update(Request $request, Category $category)
    {


        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:categories,slug,' . $category->id,
        ]);
        $category->update($validatedData);
        return response()->json($category);

    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(null, 204);
    }
}
