<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $input = array_merge(
            $request->all(),
            ["user_id" => Auth::user()->id],
        );

        Record::create($input);

        return "기록성공";
    }

    //전적 불러오기
    public function index($id)
    {
        return Record::where('user_id', '=', $id)->get();
    }
}
