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
        $this->validate(
            $request,
            [
                'win_user_id' => 'required',
                'loss_user_id' => 'required',
                'win_user_time' => 'required',
                'loss_user_time' => 'required',
            ]
        );

        //mmr상승 함수
        $this->mmr_point($request);

        $input = array_merge(
            $request->all(),
            ["user_id" => Auth::user()->id],
        );

        //기록 등록
        Record::create($input);
        return "기록성공";
    }

    //전적 불러오기
    public function index($id)
    {
        return Record::where('user_id', '=', $id)->get();
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
