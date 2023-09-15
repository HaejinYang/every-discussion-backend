<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Models\User;

class AuthController extends ApiController
{
    public function register(AuthRegisterRequest $request)
    {
        $data = $request->all();

        assert(!is_null($data['email']), 'email required');
        assert(!is_null($data['name']), 'name required');
        assert($data['password'] === $data['password_confirmation'], 'password should be confirmed');

        $user = User::create($data);

        return $this->showOne($user);
    }

    public function login()
    {

    }

    public function logout()
    {

    }

    public function delete()
    {

    }


}
