<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'event' => 'required',
                'time' => 'required',
                'calorie' => 'required',
                'average_speed' => 'required',
                'altitude' => 'required',
                'distance' => 'required',
                'range' => 'required',
                'track_id' => 'required',
                'gps_id' => 'required',
                'win_user_id' => 'required',
                'loss_user_id' => 'required'
            ]
        );

        $input = array_merge(
            $request->all(),
            ["user_id" => Auth::user()->id],
            ["mmr" => Auth::user()->mmr]
        );
        $post = Post::create($input);

        if ($request->hasFile("image")) {
            $files = $request->file("images");
            foreach ($files as $file) {
                $imageName = time() . '_' . $file->getClientOriginalName();
                $request['post_id'] = $post->id;
                $request['image'] = $imageName;
                $file->move(\public_path("/images"), $imageName);
                Image::create($request->all());
            }
        }

        return redirect()->route('record.store', [
            'win_user_id' => $request->win_user_id,
            'loss_user_id' => $request->loss_user_id,
            'kind' => $request->kind,
            'post_id' => $post->id,
            'track_id' => $post->track_id,
            'event' => $post->event
        ]);
    }

    //사람들 활동 내역 보기
    public function index()
    {
        return Post::orderby('created_at', 'desc')->where('range', '=', 'public')->with('user')->paginate(5);
    }

    //내 활동내역 보기
    public function myIndex(Request $request)
    {
        $range = $request->range;
        $user = Auth::user()->id;

        if ($range == 'private') {
            return Post::orderby('created_at', 'desc')->where('user_id', '=', $user)->where('range', '=', 'private')->pagination(6);
        } else if ($range == 'public') {
            return Post::orderby('created_at', 'desc')->where('user_id', '=', $user)->where('range', '=', 'public')->pagination(6);
        } else {
            return Post::orderby('created_at', 'desc')->where('user_id', '=', $user)->pagination(6);
        }
    }

    public function show($id)
    {
        $post = Post::with(['user', 'likes', ''])->find($id);
        return $post;
    }

    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'content' => 'required',
                'range' => 'required',
            ]
        );

        $post = Post::find($id);
        $post->content = $request->content;
        $post->range = $request->range;

        $post->save();
        return $post;
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return "삭제성공";
    }
}
