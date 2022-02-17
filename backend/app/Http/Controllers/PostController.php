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
                'title' => 'required',
                'content' => 'required|min:3'
            ]
        );

        // dd($request->all());
        $input = array_merge(
            $request->all(),
            ["user_id" => Auth::user()->id]
        );
        //이미지가 있으면.. $input에 image 항목 추가

        $fileName = null;
        if ($request->hasFile('image')) {
            // dd($request->file('image'));
            $fileName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public/images', $fileName);

            // dd($path);
        };

        if ($fileName) {
            $input = array_merge($input, ['image' => $fileName]);
            // dd($input);
        }


        // user_id 의 내용을 $request 와 함께 합친다
        // mass assignment
        // Eloquent model의 white list 인 $fillable에 기술해야 한다.
        Post::create($input);

        return redirect()->route('posts.index')->with('success', 1);
    }
}
