<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RecordController extends Controller
{
    //개개인별 기록 저장
    public function store(Request $request)
    {
        //mmr상승 함수
        if ($request->kind == "랭크") {
            $this->mmr_point($request);
        }

        $input = array_merge(
            $request->all(),
            ["user_id" => Auth::user()->id],
        );

        //기록 등록
        if (Record::create($input)) {
            return response([
                'message' => ['기록이 저장됐습니다']
            ], 201);
        } else {
            return response([
                'message' => ['실패했습니다']
            ], 405);
        }
    }
    //내가 뛴 상대방의 gpsData 즉 post_id가 필요하다 그러기 위해서 어떻게 해야할까??
    //여기서 알 수 있는 정보는 내가 같이 달린 상대방 user_id밖에 없다
    //상대방 아이디로 그 사람이 올린 posts를 조회할 수 있다.


    //내 기록 불러오기
    public function myIndex()
    {
        $id = Auth::user()->getAttribute('id');
        return Record::orderby('created_at', 'desc')->where('user_id', '=', $id)->paginate(10);
    }

    //상대 기록 불러오기
    public function index($id)
    {
        return Record::orderby('created_at', 'desc')->where('user_id', '=', $id)->paginate(10);
    }



    //mmr상승 함수
    protected function mmr_point($request)
    {
        $win_user_id = $request->win_user_id;
        // $lose_user_id = $request->lose_user_id;
        $id = Auth::user()->id;

        //이기면 mmr +10
        if ($id == $win_user_id) {
            DB::table('users')->where('id', $id)->increment('mmr', 10);
        } else {
            //지면 mmr +3
            DB::table('users')->where('id', $id)->increment('mmr', 3);
        }
    }
}
