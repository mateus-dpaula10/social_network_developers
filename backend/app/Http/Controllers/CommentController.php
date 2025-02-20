<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();

        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->post_id = $request->post_id;
        $comment->content = $request->content;
        $comment->save();

        return response()->json([
            'id' => $comment->id,
            'post_id' => $comment->post_id,
            'content' => $comment->content,
            'created_at' => $comment->created_at,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
        ], 201);
    }

    public function destroy($commentId) {
        $user = JWTAuth::parseToken()->authenticate();
        $comment = Comment::find($commentId);

        if (!$comment) {
            return response()->json(['error' => 'Comentário não encontrado!'], 404);
        }

        if ($comment->user_id !== $user->id) {
            return response()->json(['error' => 'Você não tem permissão para excluir este comentário!'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comentário excluído com sucesso!']);
    }

    public function getComments($postId) {
        $comments = Comment::where('post_id', $postId)->with('user')->latest()->get();
        return response()->json($comments);
    }
}
