<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Product;
use App\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Livevideolist;


class OrderController extends Controller
{

    public function orderCreate(Request $request)
    {
        $token = $request->get('token');

        $fb = new \Facebook\Facebook([
            'app_id' => '587226395035824',
            'app_secret' => '2c5a8bcc0f3f448b5d21adfbf71d978d',
            'default_graph_version' => 'v3.2',
        ]);

        try {
        $response = $fb->get('/me?fields=id,name,email,picture', $token);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
        }

        $user = $response->getGraphUser();
        $graphNode = $response->getGraphNode();

        $livevideoid = $request->get('live_video_id');
        $result_user = User::where('fb_id', '=', $user['id'])->first();
        $result_user->update(['token' => $token]);

        foreach($request['products'] as $product){
                $result_products = DB::table('products')->select('name', 'description', 'price', 'picture')->where('id', '=', $product['id'])->get();

                foreach($result_products as $result_product){
                    $name = $result_product->name;
                    $description = $result_product->description;
                    $price = $result_product->price;
                    $picture = $result_product->picture;
                }

                $result_order = Order::create([
                    'user_id' => $result_user['id'],
                    'live_video_id' => $livevideoid,
                    'name' => $name,
                    'description' => $description,
                    'quantity' => $product['quantity'],
                    'price' => $price,
                    'picture' => $picture
            ]);
                return response()->json([
                    'result' => 'True',
                    'response' => 'Update OK!'
            ]);
        };
    }

    public function buyerOrder($live_video_id = null, Request $request)
    {   
        $token = $request->get('token');

        $fb = new \Facebook\Facebook([
            'app_id' => '587226395035824',
            'app_secret' => '2c5a8bcc0f3f448b5d21adfbf71d978d',
            'default_graph_version' => 'v3.2',
        ]);

        try {
        $response = $fb->get('/me?fields=id,name,email,picture', $token);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
        }

        $user = $response->getGraphUser();
        $graphNode = $response->getGraphNode();

        $result_user = User::where('fb_id', '=', $user['id'])->first();
        $result_user->update(['token' => $token]);

        $validator = Validator::make(
            ['live_video_id' => $live_video_id], //!!!!!!!!!!!!!!!!
            ['live_video_id' => 'nullable|Exists:livevideolists']
        );

        if($validator->fails()){
            return response()->json([
                'result' => 'False',
                'response' => $validator->errors()->first()
            ]);
        };

        if($live_video_id == null){
            $result_order_id = Order::where('user_id', '=', $result_user->id)->get();
            return response()->json([
                'result' => 'True',
                'response' => [
                    'order' => $result_order_id
                ]
            ]);
        }elseif($live_video_id != null){
            $result_order = Order::where('live_video_id', '=', $live_video_id)->get();
            return response()->json([
                'result' => 'True',
                'response' => $result_order
            ]);
        }else{
            return response()->json([
                'result' => 'False',
                'response' => 'There are no data in the database'
            ]);
        }   
    }

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
