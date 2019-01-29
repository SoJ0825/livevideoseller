<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\User;

class ValidateToken {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'token' => 'required|string|max:255'
            ]
        );

        if ($validator->fails())
        {
            return response(['result' => 'false', 'response' => $validator->errors()->first()]);
        }

        $fb = new \Facebook\Facebook([
            'app_id'                => '587226395035824',
            'app_secret'            => '2c5a8bcc0f3f448b5d21adfbf71d978d',
            'default_graph_version' => 'v3.2',
        ]);

        try
        {
            $response = $fb->get('/me?fields=id,name,email,picture', $request->token);
        } catch (Facebook\Exceptions\FacebookResponseException $e)
        {
            Log::error($e->getMessage());

            return response(['result' => 'false', 'response' => 'Graph returned an error']);
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e)
        {
            Log::error($e->getMessage());

            return response(['result' => 'false', 'response' => 'Facebook SDK returned an error']);
            exit;
        }

        if ($user = User::where('fb_id', $response->getGraphUser()->getId())->first())
        {
            session()->put('user_id', $user->id);

            return $next($request);

        } else
        {
            return response(['result' => 'false', 'response' => 'Please login first']);
        }


    }
}
