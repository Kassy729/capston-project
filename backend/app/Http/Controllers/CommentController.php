<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate(['comment' => ['required']]);

        $comment = Comment::create(
            [
                'comment' => $request->comment,
                'user_id' => Auth::user()->getAttribute('id'),
                'post_id' => $id
            ]
        );
        return $comment;
    }

    public function index($id)
    {
        $comments = Comment::where('post_id', $id)->with('user')->get();
        return $comments;
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        $comment->delete();
        return "댓글 삭제 성공";
    }
}
