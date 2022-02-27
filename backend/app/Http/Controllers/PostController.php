<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function store(Request $request)
    {
        // error_log('Some');
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
            ]
        );

        $input = array_merge(
            $request->all(),
            ["user_id" => Auth::user()->id],
            ["mmr" => Auth::user()->mmr]
        );

        // $fileName = null;
        // if ($request->hasFile('img')) {
        //     //파일명이 겹칠 수도 있기 때문에 앞에 time을 붙여준다
        //     $fileName = time() . '_' . $request->file('img')->getClientOriginalName();
        //     $request->file('img')->storeAs('public/images', $fileName);
        // };

        // if ($fileName) {
        //     $input = array_merge($input, ['img' => $fileName]);
        // }

        Post::create($input);

        return '등록성공';
    }

    public function index(Request $request)
    {
        $range = "private";
        $user = Auth::user()->id;

        $posts = Post::where('range', '=', $range)->where('user_id', '=', $user)->paginate(5);
        return $posts;

        if ($range == 'private') {
            $posts = Post::where('range', '=', $range)->where('user_id', '=', $user)->paginate(6);
            return $posts;
        } else {
            return Post::all();
        }
    }

    public function show($id)
    {
        $post = Post::with(['user'])->find($id);
        return $post;
    }

    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                // 'kind' => 'required',
                // 'time' => 'required',
                // 'calorie' => 'required',
                // 'average_speed' => 'required',
                // 'altitude' => 'required',
                // 'distance' => 'required',
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
