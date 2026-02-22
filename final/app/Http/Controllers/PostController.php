<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index(Request $request)
    {
        $query = Post::with('user', 'comments', 'categories')->orderBy('created_at', 'desc');
        
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }
        
        $posts = $query->get();
        return response()->json($posts);
    }


    public function show($id)
    {
        $post = Post::with('user', 'comments', 'categories')->findOrFail($id);
        return view('post', ['post' => $post, 'currentUser' => auth()->user()]);
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
        return view('crearpost', ['currentUser' => auth()->user()]);
    }
    
    /**
     * Store post from web form
     */
    public function storeFromWeb(Request $request)
    {
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
            'user_id' => auth()->id(),
        ];
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }
        
        $post = Post::create($data);
        
        if ($request->has('categories')) {
            $post->categories()->sync($request->categories);
        }
        
        return redirect('/')->with('success', '¡Post creado exitosamente!');
    }
    
    /**
     * Delete post from web
     */
    public function destroyFromWeb($id)
    {
        $post = Post::findOrFail($id);
        
        // Verificar que el usuario sea el propietario o admin
        if ($post->user_id != auth()->id() && !auth()->user()->hasRole('admin')) {
            return response()->json(['error' => 'No tienes permiso para eliminar este post'], 403);
        }
        
        $post->delete();
        
        return response()->json(['success' => 'Post eliminado'], 200);
    }

}
