<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class LikeController extends Controller
{
    public function like($postId) {
        $user = JWTAuth::parseToken()->authenticate();
        $post = Post::find($postId);

        if (!$post) {
            return response()->json(['error' => 'Post não encontrado!'], 404);
        }

        $existingLike = Like::where('user_id', $user->id)->where('post_id', $postId)->first();

        if ($existingLike) {
            return response()->json(['message' => 'Post já curtido!'], 200);
        }

        Like::create([
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);

        return response()->json(['message' => 'Curtida adicionada ao post!']);
    }

    public function unlike($postId) {
        $user = JWTAuth::parseToken()->authenticate();
        $like = Like::where('post_id', $postId)->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            return response()->json(['message' => 'Curtida removida!'], 200);
        }

        return response()->json(['error' => 'Curtida não encontrada!'], 404);
    }

    public function getLikesCount($postId) {
        $user = JWTAuth::parseToken()->authenticate();
        $post = Post::find($postId);

        if (!$post) {
            return response()->json(['error' => 'Post não encontrado!'], 404);
        }

        $likesCount = $post->likes()->count();
        $likedByUser = $post->likes()->where('user_id', $user->id)->exists();

        return response()->json([
            'likes' => $likesCount, 
            'likedByUser' => $likedByUser
        ]);
    }
}
