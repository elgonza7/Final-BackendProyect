<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // GET /category - Listar todas las categorías con sus posts
    public function index()
    {
        $categories = Category::with('posts')->get();
        return response()->json($categories);
    }

    // GET /category/create - Obtener categorías (para formulario)
    public function create()
    {
        $categories = Category::with('posts')->get();
        return response()->json($categories);
    }

    // POST /category - Crear nueva categoría
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug', // slug debe ser único
        ]);

        $category = Category::create($validatedData);
        return response()->json($category, 201);
    }

    // GET /category/{category} - Ver una categoría específica
    // Laravel hace Route Model Binding automáticamente
    public function show(Category $category)
    {
        $categories = Category::with('posts')->findOrFail($category->id);
        return response()->json($categories);
    }

    // GET /category/{category}/edit - Obtener categoría para editar
    public function edit(Category $category)
    {
        $categories = Category::with('posts')->findOrFail($category->id);
        return response()->json($categories);
    }

    // PUT/PATCH /category/{category} - Actualizar categoría
    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:categories,slug,' . $category->id,
        ]);

        $category->update($validatedData);
        return response()->json($category);
    }

    // DELETE /category/{category} - Eliminar categoría
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(null, 204);
    }
}
