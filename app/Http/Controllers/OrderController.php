<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Livevideolist;
use App\Order;

class OrderController extends Controller {

    public function showWithLiveVideoID($live_video_id = null)
    {
        $validator = Validator::make(
            ['live_video_id' => $live_video_id],
            [
                'live_video_id' => 'nullable|size:16|Exists:livevideolists'
            ]);

        if ($validator->fails())
        {
            return response(['result' => 'false', 'response' => $validator->errors()->first()]);
        }

        if ($live_video_id == null)
        {
            $video_list = Livevideolist::where('user_id', session('user_id'))->pluck('live_video_id');
            $orders = Order::all()->whereIn('live_video_id', $video_list);

            return response(['result' => 'true', 'response' => $orders]);
        }

        $video_owner = Livevideolist::where('live_video_id', $live_video_id)->first()->user;
        if ($video_owner->id != session('user_id'))
        {

            return response(['result' => 'false', 'response' => 'Please confirm your live_video_id']);

        }
        $orders = Order::where('live_video_id', $live_video_id)->get();

        return response(['result' => 'true', 'response' => $orders]);

    }
}
