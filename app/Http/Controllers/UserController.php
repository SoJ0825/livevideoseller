<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255'
        ]);

        if($validator->fails()){ //fails() is a bool
            return response()->json([
                'result' => 'false',
                'response' => $validator->errors()->first()
            ]);
        };
        
        $tokens = $request->all('token');
        foreach($tokens as $token){};
        $names = $request->all('name');
        foreach($names as $name){};
        $phones = $request->all('phone');
        foreach($phones as $phone){};
        $addresss = $request->all('address');
        foreach($addresss as $address){};

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
        $result = User::where('fb_id', '=', $user['id'])->first();

        $result->update([
                'name'=>$name,
                'phone'=>$phone,
                'address'=>$address
            ]);
        return response()->json([
            'result' => 'True',
            'response' => 'Update OK'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function check(Request $request)
    {   
        $tokens = $request->all('token');
        foreach($tokens as $token){};

        $expireds = $request->all('expired_time');
        foreach($expireds as $expired){};

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
        $result = User::where('fb_id', '=', $user['id'])->first();

        if($result == true){
            $result->update(['token' => $token, 'expired_time' => $expired]);
            if($result['name'] != null){
                return response()->json([
                    'result' => 'True',
                    'response' => $result
                ]);
            } elseif ($result['name'] == null){
                return response()->json([
                    'result' => 'True',
                    'response' => $result
                ]);
            }
        } else {
            User::create([
                'fb_id'=>$user['id'],
                'fb_name'=>$user['name'],
                'fb_email'=>$graphNode['email'],
                'fb_picture'=>$graphNode['picture']['url'],
                'expired_time'=>$expired,
                'token'=>$token
            ]);
            $result_check = User::where('fb_id', '=', $user['id'])->first();
            return response()->json([
                    'result' => 'True',
                    'response' => $result_check
            ]);
        }
    }
}
