<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::with('user', 'comments', 'categories')->get();
        return response()->json($posts);
    }


    public function show($id)
    {
        $post = Post::with('user', 'comments', 'categories')->findOrFail($id);
        $currentUser = auth()->user() ?? \App\Models\User::find(session('user_id', 1));
        return view('post', ['post' => $post, 'currentUser' => $currentUser]);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        $post = Post::create($validatedData);

        return response()->json($post, 201);
    }
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'user_id' => 'sometimes|required|exists:users,id',
        ]);

        $post->update($validatedData);

        return response()->json($post);
    }
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json(null, 204);
    }
    public function create($id)
    {
        $post = Post::findOrFail($id);
        return response()->json($post);
    }
    
    /**
     * Show create post form
     */
    public function createForm(Request $request)
    {
        $currentUser = auth()->user() ?? \App\Models\User::find(session('user_id', 1));
        return view('crearpost', ['currentUser' => $currentUser]);
    }
    
    /**
     * Store post from web form
     */
    public function storeFromWeb(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        
        $userId = session('user_id', 1);
        
        Post::create([
            'title' => $validatedData['title'],
            'content' => $validatedData['content'],
            'user_id' => $userId,
        ]);
        
        return redirect('/')->with('success', '¡Post creado exitosamente!');
    }
    
    /**
     * Delete post from web
     */
    public function destroyFromWeb($id)
    {
        $post = Post::findOrFail($id);
        $userId = session('user_id', 1);
        
        if ($post->user_id != $userId) {
            return response()->json(['error' => 'No tienes permiso para eliminar este post'], 403);
        }
        
        $post->delete();
        
        return response()->json(['success' => 'Post eliminado'], 200);
    }

}
