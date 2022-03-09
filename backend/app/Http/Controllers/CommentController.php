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
        $comments = Comment::orderby('created_at', 'desc')->where('post_id', $id)->with('user')->get();
        return $comments;
    }

    public function destroy($id)
    {

        $user = Auth::user()->id;
        $comment = Comment::find($id);

        $user_id = $comment->user_id;

        if ($user == $user_id) {
            $comment->delete();
            return "댓글 삭제 성공";
        } else {
            return abort(401);
        }
    }
}
