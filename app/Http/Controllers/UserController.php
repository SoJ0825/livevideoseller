<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;

class UserController extends Controller
{
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
            'token' => 'required|string',
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string|max:255'
        ]);

        if($validator->fails()){ //fails() is a bool
            return response()->json([
                'result' => 'false',
                'response' => $validator->errors()->first()
            ]);
        };
        
        $token = $request->get('token');
        $name = $request->get('name');
        $phone = $request->get('phone');
        $address = $request->get('address');

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

    public function check(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'expired_time' => 'required'
        ]);

        if($validator->fails()){ //fails() is a bool
            return response()->json([
                'result' => 'false',
                'response' => $validator->errors()->first()
            ]);
        };

        $token = $request->get('token');
        $expired = $request->get('expired_time');

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
