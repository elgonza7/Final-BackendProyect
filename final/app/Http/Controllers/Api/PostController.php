<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

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
    public function show($id)
    {
        $post = Post::with(['user', 'comments.user', 'categories'])->findOrFail($id);

        return response()->json([
            'post' => $post,
        ], 200);
    }
    public function store(Request $request)
    {
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
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }


        $post = Post::create($data);
        if (isset($validatedData['categories'])) {
            $post->categories()->sync($validatedData['categories']);
        }

        $post->load(['user', 'categories']);
        return response()->json([
            'message' => 'Post creado exitosamente',
            'post' => $post,
        ], 201);
    }

    public function postsbycategory($id)
    {
        $posts = Post::with(['user', 'comments', 'categories'])
            ->whereHas('categories', function ($q) use ($id) {
                $q->where('categories.id', $id);
            })
            ->orderBy('created_at', 'desc')
            ->get();



        return response()->json($posts, 200);
    }



    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
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
        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $validatedData['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($validatedData);
        if (isset($validatedData['categories'])) {
            $post->categories()->sync($validatedData['categories']);
        }

        $post->load(['user', 'categories']);

        return response()->json([
            'message' => 'Post actualizado exitosamente',
            'post' => $post,
        ], 200);
    }
    public function destroy(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        if ($post->user_id !== $request->user()->id && !$request->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'No tienes permiso para eliminar este post'
            ], 403);
        }
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return response()->json([
            'message' => 'Post eliminado exitosamente'
        ], 200);
    }

    /**

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
