<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Livevideolist extends Model
{
    //
    protected $fillable = [
      'user_id', 'live_video_id', 'title', 'is_online'
    ];

    protected $hidden= [
        'user_id', 'updated_at',
    ];
}
