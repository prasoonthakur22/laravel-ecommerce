<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

use Carbon\Carbon;
use App\Models\UserToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;


class APIToken
{

    public function handle(Request $request, Closure $next)
    {
        if ($request->header('Token')) {

            $isValid = UserToken::where([['token', '=', $request->header('Token')], ['expires_at', '>', Carbon::now()]])->count();
            if ($isValid) {
                $user_id = UserToken::where([['token', '=', $request->header('Token')], ['expires_at', '>', Carbon::now()]])->pluck('user_id')->first();

                $user = User::where('id', '=', $user_id)->first();

                Auth::loginUsingId($user->id, TRUE);
                return $next($request);
            } else {
                return $this->sendError('Token is expired or not valid');
            }
        } else {
            return $this->sendError('Token is not present in Header');
        }
    }


    public function sendResponse($data, $message)
    {
        return Response::json([
            'data' => $data,
            'success' => true,
            'message' => $message,
        ], 202);
    }

    public function sendError($error, $code = 404)
    {
        return Response::json([
            'success' => false,
            'message' => $error,
        ], $code);
    }

    public function sendSuccess($message)
    {
        return Response::json([
            'success' => true,
            'message' => $message,
        ], 200);
    }
}
