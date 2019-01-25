<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FakeController extends Controller {

    //
    public function login()
    {
        $user = [
            'id'      => 1,
            'name'    => 'goodidea',
            'phone'   => '0912111222',
            'address' => '台南市東區北門路二段16號 L2A'
        ];

        return response(['result'   => 'true',
                         'response' => $user]);
    }

    public function firstlogin()
    {
        $user = [
            'id'      => 2,
            'name'    => '',
            'phone'   => '',
            'address' => ''
        ];

        return response(['result'   => 'true',
                         'response' => $user]);
    }

    public function userupdate()
    {
        return response(['result' => 'true', 'response' => 'update OK!']);
    }

    public function livevideolists()
    {
        return response(['result'   => 'true',
                         'response' => [
                             [
                                 'live_video_id' => '2487357224613465',
                                 'title'         => 'have a nice day'
                             ],
                             [
                                 'live_video_id' => '1234567890123456',
                                 'title'         => 'enjoy your life'
                             ],
                         ]
        ]);
    }

    public function productpreparelist()
    {
        return response(['result'   => 'true',
                         'response' => [
                             [
                                 'id'          => 1,
                                 'name'        => 'NIKE 球鞋',
                                 'description' => '紅色 尺寸：26號',
                                 'price'       => 2999,
                                 'picture'     => 'some URL'
                             ],
                             [
                                 'id'          => 2,
                                 'name'        => 'NIKE 球鞋',
                                 'description' => '紅色 尺寸：25號',
                                 'price'       => 2999,
                                 'picture'     => 'some URL'
                             ],
                         ]
        ]);
    }

    public function productset()
    {
        return response(['result' => 'true', 'response' => 'update OK!']);
    }

    public function productupdate()
    {
        return response(['result' => 'true', 'response' => 'update OK!']);
    }

    public function productdelete()
    {
        return response(['result' => 'true', 'response' => 'delete id!']);
    }

    public function productsell()
    {
        return response(['result' => 'true', 'response' => 'update OK!']);
    }

    public function productstopsell()
    {
        return response(['result' => 'true', 'response' => 'update OK!']);
    }

    public function livevideostart()
    {
        return response(['result' => 'true', 'response' => 'update OK!']);
    }

    public function livevideostop()
    {
        return response(['result' => 'true', 'response' => 'update OK!']);
    }

    public function sellervideolist()
    {
        return response(['result'   => 'true',
                         'response' => [
                             [
                                 'live_video_id' => '2487357224613465',
                                 'title'         => 'have a nice day'
                             ],
                             [
                                 'live_video_id' => '1234567890123456',
                                 'title'         => 'enjoy your life'
                             ],
                         ]
        ]);
    }

    public function sellerorders()
    {
        return response(['result'   => 'true',
                         'response' => [
                             [
                                 'id'      => 1,
                                 'product' => [
                                     [
                                         'id'          => 1,
                                         'name'        => 'NIKE 球鞋',
                                         'description' => '紅色 尺寸：26號',
                                         'price'       => 2999,
                                         'quantity'    => 1,
                                         'picture'     => 'some URL'
                                     ],
                                     [
                                         'id'          => 2,
                                         'name'        => 'NIKE 球鞋',
                                         'description' => '紅色 尺寸：25號',
                                         'price'       => 2999,
                                         'quantity'    => 1,
                                         'picture'     => 'some URL'
                                     ]
                                 ]
                             ],
                             [
                                 'id'      => 2,
                                 'product' => [
                                     [
                                         'id'          => 1,
                                         'name'        => 'NIKE 球鞋',
                                         'description' => '紅色 尺寸：26號',
                                         'price'       => 2999,
                                         'quantity'    => 1,
                                         'picture'     => 'some URL'
                                     ],
                                 ]
                             ],
                         ]
        ]);
    }

    public function errormessage()
    {
        \App\Events\ProductUpdated::dispatch();
        return response(['result' => 'false', 'response' => 'error message']);
    }
}
