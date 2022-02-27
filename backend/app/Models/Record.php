<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "track_id",
        "win_user_id",
        "loss_user_id",
        "win_user_time",
        "loss_user_time"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
