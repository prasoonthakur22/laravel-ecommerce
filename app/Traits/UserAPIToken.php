<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\UserToken;
use Illuminate\Support\Str;

trait UserAPIToken
{
    public function generateUserToken($user_id)
    {
        // Unique Token
        $data['user_id'] = $user_id;
        $data['token'] = uniqid(base64_encode(Str::random(10)));
        $data['token_type'] = "Custom";
        $data['is_revoked'] = 0;
        $data['expires_at'] = Carbon::now()->add(7, 'day');

        $is_exist = UserToken::where([['user_id', '=', $user_id]])->count();
        if ($is_exist) {
            $old = UserToken::where([['user_id', '=', $user_id]]);
            $old->delete();
        }
        $status = UserToken::create($data);

        return $data['token'];
    }
}
