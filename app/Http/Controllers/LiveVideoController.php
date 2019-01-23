<?php

namespace App\Http\Controllers;

use App\Livevideolist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LiveVideoController extends Controller {

    public function start(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'live_video_id' => 'required|unique:livevideolists|string|size:16',
                'title'         => 'required|string|max:255',
            ],
            [
                'live_video_id.unique' => 'Please confirm your live_video_id'
            ]
        );

        if ($validator->fails())
        {
            return response(['result' => 'false', 'response' => $validator->errors()->first()]);
        }

        $live_video = Livevideolist::create([
            'user_id'       => session('user_id'),
            'live_video_id' => $request->live_video_id,
            'title'         => $request->title,
        ]);

        return response(['result' => 'true', 'response' => $live_video->title . ' is online now.']);
    }

    public function stop(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'live_video_id' => 'required|string|size:16',
            ]
        );

        if ($validator->fails())
        {
            return response(['result' => 'false', 'response' => $validator->errors()->first()]);
        }

        $live_video = Livevideolist::where('live_video_id', $request->live_video_id)->first();

        if ($live_video == null || $live_video->user_id != session('user_id'))
        {
            return response(['result' => 'false', 'response' => 'Please confirm your live_video_id']);
        }

        Livevideolist::where('live_video_id', $request->live_video_id)
            ->update([
                'is_online' => false,
            ]);

        return response(['result' => 'true', 'response' => $live_video->title . ' is offline now.']);
    }

    public function show()
    {
       $video_list = Livevideolist::where('user_id', session('user_id'))->get();

        return response(['result' => 'true', 'response' => $video_list]);
    }
}
