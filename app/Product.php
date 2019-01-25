<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $hidden = [
      'user_id', 'live_video_id', 'quantity', 'expired_time', 'buyable', 'created_at', 'updated_at'
    ];
}
