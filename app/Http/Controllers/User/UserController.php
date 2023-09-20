<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Requests\User\UserDestroyRequest;
use App\Http\Requests\User\UserUpdateRequest;
use Illuminate\Http\Response;
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

    public function destroy(UserDestroyRequest $request)
    {
        $password = $request->input('password');
        $user = $request->input('user');

        assert(!is_null($user), "잘못된 유저입니다");
        assert(!is_null($password), "패스워드가 필요합니다.");

        if (!Hash::check($password, $user->password)) {
            return $this->error("비밀번호가 다릅니다", Response::HTTP_BAD_REQUEST);
        }

        $user->delete();

        return $this->showMessage("탈퇴 성공");
    }
}
