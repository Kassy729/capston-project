<?php

namespace App\Http\Controllers;

use App\Models\MMR;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class MMRController extends Controller
{
    public function rank(Request $request)
    {
        //랜덤매칭 함수를 호출
        return $this->random_match($request);
    }

    public function friendly(Request $request)
    {
        $track_id = $request->track_id;
        $user_id = $request->user_id;

        $gps_id = Post::where('track_id', '=', $track_id)->where('user_id', '=', $user_id)->get('gps_id');
        return $gps_id; //이걸 node서버에 보내서 gps_data요청
    }


    //mmr이 비슷한 사람과 매칭 시키는 함수
    protected function random_match($request)
    {
        $track_id = $request->track_id;
        $user_mmr = Auth::user()->mmr;
        $user_id = Auth::user()->id;
        $event = $request->event;

        //트랙아이디를 받아와서 해당 트랙을 달린 유저중 mmr이 비슷한 사람(mmr +10 or -10)을 탐색
        $posts = Post::where('event', '=', $event)->where('track_id', '=', $track_id)->where('user_id', '!=', $user_id)->where('mmr', '<=', $user_mmr + 10)->where('mmr', '>=', $user_mmr - 10)->get();

        //배열의 길이
        $array_length = count($posts);

        $matching = array();

        //array_push함수를 이용해서 해당하는 유저의 id를 배열에 넣음
        for ($i = 0; $i < $array_length; $i++) {
            array_push($matching, $posts[$i]);
        }

        //mmr이 비슷한 유저를 랜덤으로 한명 선정해서 뽑음
        $random = array_rand($matching);
        $random_matching = $matching[$random];

        //이거를 이제 mongoDB에 보내서 요청
        return $random_matching->gps_id;
    }
}
