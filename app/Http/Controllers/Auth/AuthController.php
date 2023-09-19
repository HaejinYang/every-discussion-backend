<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\AuthCheckDuplicateUserRequest;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Http\Request;
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
        $email = $request->input('email');
        $password = $request->input('password');

        assert(!is_null($email), 'email required');
        assert(!is_null($password), 'password required');

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return $this->showMessage('login failed', Response::HTTP_UNAUTHORIZED);
        }

        $user = User::where('email', $email)->first();
        $user['token'] = $user->createToken('access_token')->plainTextToken;
        $user['topicsCount'] = Participant::where('id', $user->id)->first()->topics()->count();
        $user['opinionsCount'] = Participant::where('id', $user->id)->first()->opinions()->count();

        return $this->showOne($user);
    }

    public function logout(Request $request)
    {
        $user = $request->input('user');

        assert(!is_null($user), 'user required');

        $user->currentAccessToken()->delete();
        $user['token'] = null;

        return $this->showOne($user);
    }

    public function delete(Request $request)
    {
        $user = $request->input('user');

        assert(!is_null($user), 'user required');

        $user->delete();

        return $this->showMessage('계정을 삭제하였습니다.');
    }

    public function duplicated(AuthCheckDuplicateUserRequest $request)
    {
        return $this->showMessage("중복 체크 패스");
    }
}
