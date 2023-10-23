<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Util\ArrayUtil;
use Illuminate\Http\Request;

class EmailAuthController extends Controller
{
    public function __invoke(Request $request)
    {
        $input = $request->input();
        assert(ArrayUtil::existKeysStrictly(['user', 'token'], $input), 'field required');

        $user = $input['user'];
        $token = $input['token'];

        // TODO: 인증진행해야함.
        
        return view('mail.verify', ['user' => $user, 'token' => $token]);
    }
}
