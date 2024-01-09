<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class KakaoLoginController extends ApiController
{
    public function __invoke(Request $request)
    {
        // redirect_uri로 설정된 카카오 로그인 인가 코드를 기대한다.
        // 관련 문서: https://developers.kakao.com/docs/latest/ko/kakaologin/rest-api#request-code
        $code = $request->input('code');
        if (empty($code)) {
            return $this->error('잘못된 요청입니다.', Response::HTTP_BAD_REQUEST);
        }

        // token 및 인증 정보 가져오기
        // 관련 문서: https://developers.kakao.com/docs/latest/ko/kakaologin/rest-api#request-token
        $resOfTokens = Http::withHeaders([
            'Content-type' => 'application/x-www-form-urlencoded;charset=utf-8',
        ])->asForm()->post('https://kauth.kakao.com/oauth/token', [
            'grant_type' => "authorization_code",
            'client_id' => config('app.kakao_api_key'),
            'redirect_uri' => config('app.kakao_api_redirect_uri'),
            'code' => $code,
        ]);

        if ($resOfTokens->failed()) {
            return $this->error('잘못된 요청입니다.', Response::HTTP_UNAUTHORIZED);
        }

        $tokens = $resOfTokens->json();
        $validator = Validator::make($tokens, [
            'token_type' => 'required|string',
            'access_token' => 'required|string',
            'expires_in' => 'required|int',
            'refresh_token' => 'required|string',
            'refresh_token_expires_in' => 'required|int'
        ]);

        if ($validator->fails()) {
            return $this->error('서버 내부 오류', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // 사용자 정보 가져오기
        // 관련 문서: https://developers.kakao.com/docs/latest/ko/kakaologin/rest-api#req-user-info
        $resOfUser = Http::withHeaders([
            'Authorization' => "Bearer {$tokens['access_token']}",
            'Content-type' => 'application/x-www-form-urlencoded;charset=utf-8'
        ])->asForm()->post('https://kapi.kakao.com/v2/user/me');

        if ($resOfUser->failed()) {
            return $this->error('서버 내부 오류', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        Log::info('login info ' . $resOfUser->json('properties')['nickname']);

        return redirect()->to(config('app.frontend_endpoint'));
    }
}
