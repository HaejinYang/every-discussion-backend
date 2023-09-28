<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Requests\User\UserDestroyRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Util\ArrayUtil;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends ApiController
{
    public function update(UserUpdateRequest $request)
    {
        $name = $request->input('name');
        $password = $request->input('password');
        $passwordConfirmation = $request->input('password_confirmation');
        $user = $request->input('user');

        assert(!is_null($user), "잘못된 유저입니다");

        $updates = [];
        if (!is_null($name)) {
            $updates['name'] = $name;
        }

        if (!is_null($password)) {
            assert(!is_null($password) && !is_null($passwordConfirmation), "패스워드가 필요합니다.");
            assert($password === $passwordConfirmation, "패스워드가 다릅니다.");
            $updates['password'] = Hash::make($password);
        }

        $user->update($updates);

        return $this->showMessage("비밀번호 변경 성공");
    }


    public function destroy(UserDestroyRequest $request)
    {
        $input = $request->input();
        assert(ArrayUtil::existKeysStrictly(['user', 'password'], $input), '필드 확인');

        if (!Hash::check($input['password'], $input['user']->password)) {
            return $this->error("비밀번호가 다릅니다", Response::HTTP_BAD_REQUEST);
        }

        $input['user']->delete();

        return $this->showMessage("탈퇴 성공");
    }
}
