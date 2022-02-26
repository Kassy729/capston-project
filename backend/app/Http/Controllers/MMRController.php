<?php

namespace App\Http\Controllers;

use App\Models\MMR;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class MMRController extends Controller
{
    public function match(Request $request)
    {
        $track_id = $request->track_id;
        $user_mmr = Auth::user()->mmr;
        $user_id = Auth::user()->id;

        //트랙아이디를 받아와서 해당 트랙을 달린 유저중 mmr이 비슷한 사람(mmr +10 or -10)을 탐색
        $posts = Post::where('track_id', '=', $track_id)->where('user_id', '!=', $user_id)->where('mmr', '<=', $user_mmr + 10)->where('mmr', '>=', $user_mmr - 10)->get('user_id');

        //배열의 길이
        $array_length = count($posts);

        $matching_user = array();

        //array_push함수를 이용해서 해당하는 유저의 id를 배열에 넣음
        for ($i = 0; $i < $array_length; $i++) {
            array_push($matching_user, $posts[$i]->user_id);
        }

        //mmr이 비슷한 유저를 랜덤으로 한명 선정해서 뽑음
        $random = array_rand($matching_user);
        $random_matching_user = $matching_user[$random];

        return $random_matching_user;
    }
}
