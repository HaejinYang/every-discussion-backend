<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends ApiController
{
    public function register(AuthRegisterRequest $request)
    {
        $data = $request->input();

        assert(!is_null($data['email']), 'email required');
        assert(!is_null($data['name']), 'name required');
        assert($data['password'] === $data['password_confirmation'], 'password should be confirmed');

        $user = User::create($data);

        return $this->showOne($user);
    }

    public function login(AuthLoginRequest $request)
    {
        $data = $request->input();

        assert(!is_null($data['email']), 'email required');
        assert($data['password'] === $data['password_confirmation'], 'password should be confirmed');

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return $this->showMessage('login failed', Response::HTTP_UNAUTHORIZED);
        }

        $user = User::where('email', $data['email'])->first();
        $user['token'] = $user->createToken('access_token')->plainTextToken;
        
        return $this->showOne($user);
    }

    public function logout()
    {

    }

    public function delete()
    {
        // 탈퇴를 진행하려면 로그인 한지 확인을 해야하네
    }


}
