<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $guarded = [];

    protected $hidden = ['user_id', 'created_at','updated_at'];   
}
