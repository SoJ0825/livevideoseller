<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Livevideolist;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function liveVideoList()
    {
        $result = DB::table('livevideolists')->select('live_video_id', 'title')->get();
        
        if(count($result) > 0){            
            return response()->json([
                'result' => 'True',
                'response' => $result
            ]);
        } else {
            return response()->json([
                'result' => 'False',
                'response' => 'There are no data in database'
            ]);
        }
    }

    public function productList($live_video_id)
    {
        $result = DB::table('products')->select('id', 'name', 'description', 'price', 'picture')->where('live_video_id', '=', $live_video_id)->get();

        if(count($result) > 0){
            return response()->json([
                'result' => 'True',
                'response' => [
                    'live_video_id' => $live_video_id,
                    'products' => $result
                ],
            ]);
        } else {
            return response()->json([
                'result' => 'False',
                'response' => 'No product info'
            ]);
        }
    }
}