<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EmailAuthController extends Controller
{
    public function __invoke(Request $request)
    {
        $input = $request->input();

        if (!(isset($input['user']) && isset($input['token']))) {
            return view('mail.verify', ['msg' => "양식에 맞지 않습니다."]);
        }

        $email = $input['user'];
        $token = $input['token'];

        $user = User::where('email', $email)->firstOrFail();
        if ($user->email_verified_at) {
            return view('mail.verify', ['msg' => "이미 인증된 계정입니다."]);
        }

        if ($user->remember_token !== $token) {
            return view('mail.verify', ['msg' => "인증을 실패했습니다."]);
        }

        $user->email_verified_at = Carbon::now();
        $user->save();

        return view('mail.verify', ['msg' => "인증을 완료했습니다."]);
    }
}
