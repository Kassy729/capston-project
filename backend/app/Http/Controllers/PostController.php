<?php

namespace App\Http\Controllers;

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
                'kind' => 'required',
                'time' => 'required',
                'calorie' => 'required',
                'average_speed' => 'required',
                'altitude' => 'required',
                'distance' => 'required',
                'range' => 'required'
            ]
        );

        $input = array_merge(
            $request->all(),
            ["user_id" => Auth::user()->id]
        );

        // $fileName = null;
        // if ($request->hasFile('image')) {
        //     // dd($request->file('image'));
        //     $fileName = time() . '_' . $request->file('image')->getClientOriginalName();
        //     $request->file('image')->storeAs('public/images', $fileName);
        //     // dd($path);
        // };

        // if ($fileName) {
        //     $input = array_merge($input, ['image' => $fileName]);
        //     // dd($input);
        // }

        Post::create($input);

        return '등록성공';
    }

    public function index(Request $request)
    {
        $range = "private";
        $user = Auth::user()->id;

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
