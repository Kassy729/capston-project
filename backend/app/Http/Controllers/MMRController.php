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


        $posts = Post::where('track_id', '=', $track_id)->where('user_id', '!=', $user_id)->where('mmr', '=', $user_mmr)->get('user_id');
        return $posts;
    }
}
