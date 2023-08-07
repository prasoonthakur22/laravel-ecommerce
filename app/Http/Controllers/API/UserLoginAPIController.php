<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class UserLoginAPIController extends AppBaseController
{
    use SendsPasswordResetEmails;

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) return $this->sendError($validator->errors()->first(), 400);

        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();

            $user['token'] = $user->generateUserToken($user->id);

            return $this->sendResponse($user, 'You have been successfully logged in');
        } else {
            return $this->sendError('The email address or password you entered is invalid. Please, try again', 401);
        }
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'unique:users,email|required|email',
            'password' => 'required',
            'conf_password' => 'required'
        ]);

        if ($validator->fails()) return $this->sendError($validator->errors()->first(), 400);

        $validated = $validator->validated();
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        // $user->sendEmailVerificationNotification();
        $success['token'] = $user->generateUserToken($user->id);

        $success['id'] =  $user->id;
        $success['name'] =  $user->name;
        $success['email'] =  $user->email;

        return $this->sendResponse($success, 'Users registered successfully');
    }


    public function logout($id)
    {
        $user = User::find($id);
        if (empty($user)) return $this->sendError('User not found');

        Auth::setUser($user);
        Auth::logout();

        return $this->sendSuccess('You have been successfully logged out');
    }


    public function userForgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) return $this->sendError($validator->errors()->first(), 400);

        $validated = $validator->validated();

        $user = User::where('email', $validated['email'])->first();

        if (empty($user)) return $this->sendError('User not found');

        // $user->sendPasswordResetNotification('tokenToPass');

        $this->sendResetLinkEmail($request);

        return $this->sendSuccess('Forgot Password mail sent');
    }


    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'old_password' => 'required',
            'new_password' => 'required',
            'conf_new_password' => 'required'
        ]);

        if ($validator->fails()) return $this->sendError($validator->errors()->first(), 400);

        $validated = $validator->validated();

        $email = $validated['email'];
        $current_pass = $validated['old_password'];
        $new_pass = $validated['new_password'];
        $confirm_pass = $validated['conf_new_password'];

        if ($current_pass == $new_pass) return $this->sendError('New Password cannot be the same as your old password', 400);

        if (!$new_pass == $confirm_pass) return $this->sendError('Confirm Password is not matching to new password', 400);

        if (!Auth::attempt(['email' => $email, 'password' => $current_pass])) {
            return $this->sendError('The email or password you supplied does not match your current credentials', 401);
        }

        User::where('email', '=', $email)->update(['password' => Hash::make($new_pass)]);
        // $user = User::where('email', '=', $email)->first();
        // $user->sendPasswordResetNotification('tokenToPass');
        return $this->sendSuccess('Password Changed Successfully');
    }


    ########## END OF
}
