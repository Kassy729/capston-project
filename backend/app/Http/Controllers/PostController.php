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
            'track_id' => $post->track_id
        ]);
    }

    public function index(Request $request)
    {
        $range = "public";
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
