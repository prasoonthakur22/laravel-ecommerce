<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Validator;

class UserAPIController extends AppBaseController
{

    public function index()
    {
        $usersCount = User::all()->count();
        if (!$usersCount) return $this->sendError('Users not found');

        $users = User::all();

        return $this->sendResponse($users, 'Users retrieved successfully');
    }


    public function show($id)
    {
        /** @var User $user */
        $user = User::find($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }
        $user->makeHidden(['password']);

        $data = $user->toArray();

        return $this->sendResponse($data, 'User retrieved successfully');
    }


    public function update($id, Request $request)
    {
        $input = $request->all();

        unset($input['email']);
        unset($input['password']);

        $user = User::find($id);

        if (empty($user)) return $this->sendError('User not found');

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) return $this->sendError($validator->errors()->first(), 400);

        $user = $user->update($input);

        if (!$user) return $this->sendError('User does not updated');

        return $this->sendSuccess('User updated successfully');
    }



    public function destroy($id)
    {
        /** @var User $user */
        $user = User::find($id);

        if (empty($user)) return $this->sendError('User not found');

        $user->delete();

        return $this->sendSuccess('User deleted successfully');
    }
}
