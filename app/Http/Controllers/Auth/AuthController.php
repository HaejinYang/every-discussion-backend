<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\AuthCheckDuplicateUserRequest;
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
        $user = auth('sanctum')->user();
        if (is_null($user)) {
            return $this->showMessage('로그아웃할 수 없습니다.', Response::HTTP_UNAUTHORIZED);
        }

        $user->currentAccessToken()->delete();
        $user['token'] = null;

        return $this->showOne($user);
    }

    public function delete()
    {
        if (!auth('sanctum')->check()) {
            return $this->error('계정을 삭제할 수 없습니다.', Response::HTTP_UNAUTHORIZED);
        }

        $user = auth('sanctum')->user();
        if (is_null($user)) {
            return $this->error('계정을 삭제할 수 없습니다.', Response::HTTP_UNAUTHORIZED);
        }

        $user->delete();

        return $this->showMessage('계정을 삭제하였습니다.');
    }

    public function duplicated(AuthCheckDuplicateUserRequest $request)
    {
        return $this->showMessage("중복 체크 패스");
    }
}
