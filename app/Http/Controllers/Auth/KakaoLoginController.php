<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KakaoLoginController extends ApiController
{
    // redirect_uri로 설정된 카카오 로그인 인가 코드를 받는다.
    public function __invoke(Request $request)
    {
        $code = $request->input('code');

        if (empty($code)) {
            return $this->error('잘못된 요청입니다.', Response::HTTP_BAD_REQUEST);
        }

        // code는 문자열
        //Http::get('https://kauth.kakao.com/oauth/authorize');
        $response = Http::withHeaders([
            'Content-type' => 'application/x-www-form-urlencoded;charset=utf-8',
        ])->asForm()->post('https://kauth.kakao.com/oauth/token', [
            'grant_type' => "authorization_code",
            'client_id' => config('app.kakao_api_key'),
            'redirect_uri' => config('app.kakao_api_redirect_uri'),
            'code' => $code,
        ]);

        Log::info('token ' . $response->body());

        return redirect()->to(config('app.frontend_endpoint'));
    }
}
