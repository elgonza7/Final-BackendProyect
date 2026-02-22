<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    /**
     * Listar todos los comentarios (público)
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 50);
        $postId = $request->input('post_id');

        $query = Comment::with(['user', 'post'])->orderBy('created_at', 'desc');

        if ($postId) {
            $query->where('post_id', $postId);
        }

        $comments = $query->paginate($perPage);

        return response()->json($comments, 200);
    }

    /**
     * Mostrar un comentario específico
     */
    public function show($id)
    {
        $comment = Comment::with(['user', 'post'])->findOrFail($id);

        return response()->json([
            'comment' => $comment,
        ], 200);
    }

    /**
     * Crear un nuevo comentario
     * Usuarios pueden crear comentarios en cualquier post
     */
    public function store(Request $request)
    {
        // Verificar permiso
        if (!$request->user()->can('create comments')) {
            return response()->json([
                'message' => 'No tienes permiso para crear comentarios'
            ], 403);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'post_id' => 'required|exists:posts,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        // Verificar que el post existe
        $post = Post::findOrFail($validatedData['post_id']);

        $data = [
            'name' => $validatedData['name'],
            'content' => $validatedData['content'],
            'post_id' => $validatedData['post_id'],
            'user_id' => $request->user()->id,
        ];

        // Procesar imagen si existe
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('comments', 'public');
        }

        $comment = Comment::create($data);
        $comment->load(['user', 'post']);

        return response()->json([
            'message' => 'Comentario creado exitosamente',
            'comment' => $comment,
        ], 201);
    }

    /**
     * Actualizar un comentario
     * Solo el propietario puede editar su comentario
     */
    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        // Verificar que el usuario sea el propietario
        if ($comment->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'No tienes permiso para editar este comentario'
            ], 403);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        // Actualizar imagen si se proporciona
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($comment->image) {
                Storage::disk('public')->delete($comment->image);
            }
            $validatedData['image'] = $request->file('image')->store('comments', 'public');
        }

        $comment->update($validatedData);
        $comment->load(['user', 'post']);

        return response()->json([
            'message' => 'Comentario actualizado exitosamente',
            'comment' => $comment,
        ], 200);
    }

    /**
     * Eliminar un comentario
     * El propietario o admin pueden eliminar
     */
    public function destroy(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        // Verificar que el usuario sea el propietario o admin
        if ($comment->user_id !== $request->user()->id && !$request->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'No tienes permiso para eliminar este comentario'
            ], 403);
        }

        // Eliminar imagen si existe
        if ($comment->image) {
            Storage::disk('public')->delete($comment->image);
        }

        $comment->delete();

        return response()->json([
            'message' => 'Comentario eliminado exitosamente'
        ], 200);
    }

    /**
     * Obtener comentarios de un post específico
     */
    public function getPostComments($postId)
    {
        $post = Post::findOrFail($postId);
        
        $comments = Comment::with(['user'])
            ->where('post_id', $postId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'post' => $post,
            'comments' => $comments,
        ], 200);
    }

    /**
     * Obtener comentarios del usuario autenticado
     */
    public function myComments(Request $request)
    {
        $perPage = $request->input('per_page', 50);

        $comments = Comment::with(['user', 'post'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json($comments, 200);
    }
}
