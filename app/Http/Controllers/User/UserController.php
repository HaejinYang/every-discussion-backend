<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Requests\User\UserUpdateRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends ApiController
{
    public function update(UserUpdateRequest $request)
    {
        $password = $request->input('password');
        $passwordConfirmation = $request->input('password_confirmation');
        $user = $request->input('user');

        assert(!is_null($user), "잘못된 유저입니다");
        assert(!is_null($password) && !is_null($passwordConfirmation), "패스워드가 필요합니다.");
        assert($password === $passwordConfirmation, "패스워드가 다릅니다.");

        $user->update(['password' => Hash::make($password)]);

        return $this->showMessage("비밀번호 변경 성공");
    }
}
