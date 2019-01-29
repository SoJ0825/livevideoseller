<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Livevideolist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\User;

class ProductController extends Controller {

    public function liveVideoList()
    {
        $result = DB::table('livevideolists')->select('live_video_id', 'title')->where('is_online', '=', 1)->get();

        if(count($result) > 0){
            return response()->json([
                'result'   => 'True',
                'response' => $result
            ]);
        } else {
            return response()->json([
                'result' => 'False',
                'response' => $result
            ]);
        }
    }

    public function productList($live_video_id)
    {
        $result = DB::table('products')->select('id', 'name', 'description', 'price', 'picture')->where('live_video_id', '=', $live_video_id)->get();

        if (count($result) > 0)
        {
            return response()->json([
                'result'   => 'True',
                'response' => [
                    'live_video_id' => $live_video_id,
                    'products'      => $result
                ],
            ]);
        } else
        {
            return response()->json([
                'result'   => 'False',
                'response' => 'No product info'
            ]);
        }
    }

    public function prepare()
    {
        return response(['result'   => 'true',
                         'response' => User::find(session('user_id'))->products()->get()]);

    }

    public function setNewProduct(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'product'             => 'required|array',
                'product.name'        => 'required|string',
                'product.description' => 'required|string',
                'product.price'       => 'required|integer',
                'product.picture'     => 'required|string'
            ]
        );

        if ($validator->fails())
        {
            return response(['result' => 'false', 'response' => $validator->errors()->first()]);
        }
        $product = Product::forceCreate([
            'user_id'     => session('user_id'),
            'name'        => $request->product['name'],
            'description' => $request->product['description'],
            'price'       => $request->product['price'],
            'picture'     => $request->product['picture']
        ]);

        return response(['result'   => 'true',
                         'response' => [
                             'message' => "Create product " . $request->product['name'] . " OK",
                             'id'      => $product->id]]);
    }

    public function updateProduct(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'product'             => 'required|array',
                'product.id'          => 'required|integer',
                'product.name'        => 'required|string',
                'product.description' => 'required|string',
                'product.price'       => 'required|integer',
                'product.picture'     => 'required|string'
            ]
        );

        if ($validator->fails())
        {
            return response(['result' => 'false', 'response' => $validator->errors()->first()]);
        }

        $product = Product::where('id', $request->product['id'])->first();
        if ($product->user_id != session('user_id'))
        {
            return response(['result' => 'false', 'response' => 'Please confirm your product id']);
        }
        Product::where('id', $request->product['id'])
            ->update([
                'name'        => $request->product['name'],
                'description' => $request->product['description'],
                'price'       => $request->product['price'],
                'picture'     => $request->product['picture']
            ]);

        return response(['result'   => 'true',
                         'response' => "Update product " . $request->product['name'] . " OK"]);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'product'    => 'required|array',
                'product.id' => 'required|integer',
            ]
        );

        if ($validator->fails())
        {
            return response(['result' => 'false', 'response' => $validator->errors()->first()]);
        }

        $product = Product::where('id', $request->product['id'])->first();
        if ($product == null || $product->user_id != session('user_id'))
        {
            return response(['result' => 'false', 'response' => 'Please confirm your product id']);
        }
        $product->forceDelete();

        return response(['result'   => 'true',
                         'response' => "Delete product " . $product->name . ", OK"]);
    }

    public function sell(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'live_video_id'     => 'required|string|size:16',
                'product'           => 'required|array',
                'product.id'        => 'required|integer',
                'product.life_time' => 'required|integer',
            ]
        );

        if ($validator->fails())
        {
            return response(['result' => 'false', 'response' => $validator->errors()->first()]);
        }

        $product = Product::where('id', $request->product['id'])->first();
        if ($product == null || $product->user_id != session('user_id'))
        {
            return response(['result' => 'false', 'response' => 'Please confirm your product id']);
        }

        Product::where('id', $request->product['id'])
            ->update([
                'live_video_id' => $request->live_video_id,
                'expired_time'  =>
                    $request->product['life_time'] == 0 ? 0 : $request->product['life_time'] + time(),
                'buyable'       => true
            ]);


        return response(['result'   => 'true',
                         'response' => "Product " . $product->name . " is buyable now"]);
    }

    public function stopSell(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'live_video_id' => 'required|string|size:16',
                'product'       => 'required|array',
                'product.id'    => 'required|integer',
            ]
        );

        if ($validator->fails())
        {
            return response(['result' => 'false', 'response' => $validator->errors()->first()]);
        }

        $product = Product::where('id', $request->product['id'])->first();
        if ($product == null || $product->user_id != session('user_id'))
        {
            return response(['result' => 'false', 'response' => 'Please confirm your product id']);
        }

        Product::where('id', $request->product['id'])
            ->update([
                'live_video_id' => null,
                'expired_time'  => 0,
                'buyable'       => false
            ]);


        return response(['result'   => 'true',
                         'response' => "Product " . $product->name . " is stop selling"]);
    }
}
