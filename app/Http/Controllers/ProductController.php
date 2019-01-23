<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Product;

class ProductController extends Controller {

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
        Product::forceCreate([
            'user_id'     => session('user_id'),
            'name'        => $request->product['name'],
            'description' => $request->product['description'],
            'price'       => $request->product['price'],
            'picture'     => $request->product['picture']
        ]);

        return response(['result'   => 'true',
                         'response' => "Create product " . $request->product['name'] . " OK"]);
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
                'live_video_id'     => 'required|string|size:16',
                'product'           => 'required|array',
                'product.id'        => 'required|integer',
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
