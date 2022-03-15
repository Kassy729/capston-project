<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Image;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
                'gps_id' => 'required',
                'kind' => 'required'
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

        if ($request->kind == "혼자하기") {
            return response([
                'message' => ['혼자하기 기록을 저장했습니다']
            ], 201);
        } else {
            return redirect()->route('record.store', [
                'post_id' => $post->id,
                'win_user_id' => $request->win_user_id,
                'loss_user_id' => $request->loss_user_id,
                'kind' => $request->kind
            ]);
        }
    }

    //팔로우한 사람들 활동 내역 시간별로 보기
    public function index()
    {
        //팔로워들 id가져오기
        //where절 걸어서 팔로워들의 id의 post만 가져오기
        $id = Auth::user()->id;
        $followings = Follow::where('follower_id', '=', $id)->get('following_id');
        $array_length = count($followings);
        $array = array();
        array_push($array, $id);

        //배열에 팔로잉한 아이디 push
        for ($i = 0; $i < $array_length; $i++) {
            array_push($array, $followings[$i]->following_id);
        }

        //팔로잉한 아이디의 포스트만 시간별로 출력
        return Post::with(['user', 'likes'])->whereIn('user_id', $array)->where('range', 'public')->orderby('created_at', 'desc')->paginate(5);
    }

    //내 활동내역 보기
    public function myIndex(Request $request)
    {
        $range = $request->range;
        $user = Auth::user()->id;
        if ($range == 'private') {
            return Post::orderby('created_at', 'desc')->where('user_id', '=', $user)->where('range', '=', 'private')->paginate(6);
        } else if ($range == 'public') {
            return Post::orderby('created_at', 'desc')->where('user_id', '=', $user)->where('range', '=', 'public')->paginate(6);
        } else {
            return Post::orderby('created_at', 'desc')->where('user_id', '=', $user)->paginate(6);
        }
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
        $user = Auth::user()->id;
        $user_id = $post->user_id;

        if ($user == $user_id) {
            $post->content = $request->content;
            $post->range = $request->range;
            $post->save();
            return response([
                'message' => ['수정 완료']
            ], 201);
        } else {
            return abort(401);
        }
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $user = Auth::user()->id;
        $user_id = $post->user_id;

        if ($user == $user_id) {
            $post->delete();
            return response([
                'message' => ['삭제 성공']
            ], 201);
        } else {
            return abort(401);
        }
    }
}
