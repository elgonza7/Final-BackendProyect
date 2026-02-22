<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Listar todos los posts (público - usuarios y admins pueden ver)
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $categoryId = $request->input('category');

        $query = Post::with(['user', 'comments', 'categories'])
            ->orderBy('created_at', 'desc');

        if ($categoryId) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        $posts = $query->paginate($perPage);

        return response()->json($posts, 200);
    }

    /**
     * Mostrar un post específico
     */
    public function show($id)
    {
        $post = Post::with(['user', 'comments.user', 'categories'])->findOrFail($id);

        return response()->json([
            'post' => $post,
        ], 200);
    }

    /**
     * Crear un nuevo post (solo usuarios autenticados con permiso)
     */
    public function store(Request $request)
    {
        // Verificar permiso
        if (!$request->user()->can('create posts')) {
            return response()->json([
                'message' => 'No tienes permiso para crear posts'
            ], 403);
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $data = [
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
            'user_id' => $request->user()->id,
        ];

        // Procesar imagen si existe
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post = Post::create($data);

        // Sincronizar categorías
        if (isset($validatedData['categories'])) {
            $post->categories()->sync($validatedData['categories']);
        }

        $post->load(['user', 'categories']);

        return response()->json([
            'message' => 'Post creado exitosamente',
            'post' => $post,
        ], 201);
    }

    /**
     * Actualizar un post existente
     * Solo el propietario o admin pueden editar
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        // Verificar que el usuario sea el propietario o tenga permiso de editar posts
        if ($post->user_id !== $request->user()->id && !$request->user()->can('edit posts')) {
            return response()->json([
                'message' => 'No tienes permiso para editar este post'
            ], 403);
        }

        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        // Actualizar imagen si se proporciona
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $validatedData['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($validatedData);

        // Actualizar categorías si se proporcionan
        if (isset($validatedData['categories'])) {
            $post->categories()->sync($validatedData['categories']);
        }

        $post->load(['user', 'categories']);

        return response()->json([
            'message' => 'Post actualizado exitosamente',
            'post' => $post,
        ], 200);
    }

    /**
     * Eliminar un post
     * Solo el propietario o admin pueden eliminar
     */
    public function destroy(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        // Verificar que el usuario sea el propietario o admin
        if ($post->user_id !== $request->user()->id && !$request->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'No tienes permiso para eliminar este post'
            ], 403);
        }

        // Eliminar imagen si existe
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return response()->json([
            'message' => 'Post eliminado exitosamente'
        ], 200);
    }

    /**
     * Obtener posts del usuario autenticado
     */
    public function myPosts(Request $request)
    {
        $perPage = $request->input('per_page', 15);

        $posts = Post::with(['user', 'comments', 'categories'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json($posts, 200);
    }
}
