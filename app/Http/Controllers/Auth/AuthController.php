<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\AuthCheckDuplicateUserRequest;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Mail\AuthMail;
use App\Models\Participant;
use App\Models\User;
use App\Util\ArrayUtil;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends ApiController
{
    public function register(AuthRegisterRequest $request)
    {
        $input = $request->input();
        assert(ArrayUtil::existKeysStrictly(['email', 'name', 'password', 'password_confirmation'], $input), '필드 확인');

        $input['remember_token'] = Str::random(10);

        $user = User::create($input);
        if (env('APP_ENV') === 'local') {
            Mail::to('extension.master.91@gmail.com')->send(new AuthMail($user->email, $user->remember_token));
        } else {
            Mail::to($user->email)->send(new AuthMail($user->email, $user->remember_token));
        }

        return $this->showOne($user);
    }

    public function login(AuthLoginRequest $request)
    {
        $input = $request->input();
        assert(ArrayUtil::existKeysStrictly(['email', 'password'], $input), '필드 확인');
        // TODO: 이메일 인증이 안되면 반려하기 추가.
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return $this->showMessage('login failed', Response::HTTP_UNAUTHORIZED);
        }

        $user = User::where('email', $input['email'])->first();
        $user['token'] = $user->createToken('access_token')->plainTextToken;
        $user['topicsCount'] = Participant::where('id', $user->id)->first()->topics()->count();
        $user['opinionsCount'] = Participant::where('id', $user->id)->first()->opinions()->count();

        return $this->showOne($user);
    }

    public function logout(Request $request)
    {
        $input = $request->input();
        assert(ArrayUtil::existKeysStrictly(['user'], $input), '필드 확인');

        $user = $input['user'];
        $user->currentAccessToken()->delete();
        $user['token'] = null;

        return $this->showOne($user);
    }

    public function delete(Request $request)
    {
        $input = $request->input();
        assert(ArrayUtil::existKeysStrictly(['user'], $input), '필드 확인');

        $user = $input['user'];
        $user->delete();

        return $this->showMessage('계정을 삭제하였습니다.');
    }

    public function duplicated(AuthCheckDuplicateUserRequest $request)
    {
        return $this->showMessage("중복 체크 패스");
    }
}
