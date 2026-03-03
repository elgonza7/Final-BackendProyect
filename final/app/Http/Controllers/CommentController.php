<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with('user', 'post')->get();
        return response()->json($comments);
    }
    public function create()
    {
        $comments = Comment::with('user', 'post')->get();
        return response()->json($comments);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'post_id' => 'required|exists:posts,id', // el post debe existir
            'user_id' => 'required|exists:users,id', // el user debe existir
        ]);

        $comment = Comment::create($validatedData);
        return response()->json($comment, 201);
    }
    public function storeFromWeb(Request $request, $postId)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $data = [
            'name' => $validatedData['name'],
            'content' => $validatedData['content'],
            'post_id' => $postId, // viene de la URL
            'user_id' => auth()->id(), // usuario logueado
        ];
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('comments', 'public');
        }
        Comment::create($data);
        return redirect("/post/{$postId}")->with('success', '¡Comentario publicado exitosamente!');
    }
    public function show(Comment $comment)
    {
        $comment->load('user', 'post');
        return response()->json($comment);
    }


    
    public function edit(Comment $comment)
    {
        $comment->load('user', 'post');
        return response()->json($comment);
    }


    
    public function update(Request $request, Comment $comment)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'post_id' => 'sometimes|required|exists:posts,id',
            'user_id' => 'sometimes|required|exists:users,id',
        ]);

        $comment->update($validatedData);
        return response()->json($comment);
    }

    // DELETE /comment/{comment} - Eliminar comentario
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json(null, 204);
    }
    

    public function destroyFromWeb($id)
    {
        $comment = Comment::findOrFail($id);
        // Solo el autor del comentario o un admin pueden eliminarlo
        if ($comment->user_id != auth()->id() && !auth()->user()->hasRole('admin')) {
            return response()->json(['error' => 'No tienes permiso para eliminar este comentario'], 403);
        }
        $comment->delete();

        
        return response()->json(['success' => 'Comentario eliminado'], 200);
    }
}
