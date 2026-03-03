<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{

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
    public function show($id)
    {
        $comment = Comment::with(['user', 'post'])->findOrFail($id);

        return response()->json([
            'comment' => $comment,
        ], 200);
    }
    public function store(Request $request)
    {
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
        $post = Post::findOrFail($validatedData['post_id']);

        $data = [
            'name' => $validatedData['name'],
            'content' => $validatedData['content'],
            'post_id' => $validatedData['post_id'],
            'user_id' => $request->user()->id,
        ];
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
    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
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
        if ($request->hasFile('image')) {

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

    public function destroy(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        if ($comment->user_id !== $request->user()->id && !$request->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'No tienes permiso para eliminar este comentario'
            ], 403);
        }


        if ($comment->image) {
            Storage::disk('public')->delete($comment->image);
        }
        $comment->delete();
        return response()->json([
            'message' => 'Comentario eliminado exitosamente'
        ], 200);
    }

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
