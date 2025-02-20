<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class PostController extends Controller
{
    public function store(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado!'], 401);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'content' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        $post = new Post();
        $post->title = $request->title;
        $post->content = $request->content;
        $post->user_id = $user->id;
        $post->save();

        return response()->json($post, 201);
    }

    public function index() {
        $posts = Post::with('user')->latest()->get();
        return response()->json($posts);
    }

    public function update(Request $request, $postId) {
        $user = JWTAuth::parseToken()->authenticate();
        $post = Post::find($postId);

        if (!$post) {
            return response()->json(['error' => 'Post não encontrado!'], 404);
        }

        if ($post->user_id !== $user->id) {
            return response()->json(['error' => 'Você não tem permissão para excluir este post!'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:5000'
        ]);

        $post->title = $request->title;
        $post->content = $request->content;
        $post->save();

        return response()->json($post);
    }

    public function destroy($postId) {
        $user = JWTAuth::parseToken()->authenticate();
        $post = Post::find($postId);

        if (!$post) {
            return response()->json(['error' => 'Post não encontrado!'], 404);
        }

        if ($post->user_id !== $user->id) {
            return response()->json(['error' => 'Você não tem permissão para excluir este post!'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post excluído com sucesso!']);
    }
}
