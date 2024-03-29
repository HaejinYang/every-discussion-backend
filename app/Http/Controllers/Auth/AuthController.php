<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\AuthChangePasswordRequest;
use App\Http\Requests\Auth\AuthCheckDuplicateUserRequest;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Jobs\SendAuthEmail;
use App\Mail\SendTokenForChangingPassword;
use App\Models\Participant;
use App\Models\User;
use App\Util\ArrayUtil;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends ApiController
{
    public function register(AuthRegisterRequest $request)
    {
        $input = $request->input();
        assert(ArrayUtil::existKeysStrictly(['email', 'name', 'password', 'password_confirmation'], $input), '필드 확인');

        $input['remember_token'] = Str::random(10);

        $user = User::create($input);
        SendAuthEmail::dispatch($user->email, $user->remember_token);

        return $this->showOne($user);
    }

    public function login(AuthLoginRequest $request)
    {
        $input = $request->input();
        assert(ArrayUtil::existKeysStrictly(['email', 'password'], $input), '필드 확인');

        $email = $input['email'];
        $password = $input['password'];

        $user = User::where('email', $email)->firstOrFail();
        if (!$user->email_verified_at) {
            return $this->showMessage('login failed', Response::HTTP_FORBIDDEN);
        }

        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            return $this->showMessage('login failed', Response::HTTP_UNAUTHORIZED);
        }

        $user['token'] = $user->createToken('login_token')->plainTextToken;
        $user['topicsCount'] = Participant::where('id', $user->id)->first()->topics()->count();
        $user['opinionsCount'] = Participant::where('id', $user->id)->first()->opinions()->count();

        return $this->showOne($user);
    }

    public function loginByToken(Request $request)
    {
        $input = $request->input();
        assert(ArrayUtil::existKeysStrictly(['user'], $input), '필드 확인');

        $user = $input['user'];
        $user['token'] = $user->createToken('login_token')->plainTextToken;
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

    public function findEmail(Request $request)
    {
        $input = $request->input();
        assert(ArrayUtil::existKeysStrictly(['name'], $input), '필드 확인');

        $name = $input['name'];
        $user = User::where('name', $name)->firstOrFail();

        return $this->showOne((object)['email' => $user->email]);
    }

    public function sendTokenForChangingPassword(Request $request)
    {
        $input = $request->input();
        assert(ArrayUtil::existKeysStrictly(['email'], $input), '필드 확인');

        $token = Str::random(10);
        $user = User::where('email', $input['email'])->firstOrFail();
        $user->remember_token = $token;
        $user->saveOrFail();

        if (env('APP_ENV') === 'local') {
            Mail::to(config('app.master_email'))->send(new SendTokenForChangingPassword($token));
        } else {
            Mail::to($user->email)->send(new SendTokenForChangingPassword($token));
        }

        return $this->showOne((object)['token' => $token]);
    }

    public function verifyTokenForChangingPassword(Request $request)
    {
        $input = $request->input();
        assert(ArrayUtil::existKeysStrictly(['email', 'token'], $input), '필드 확인');

        $token = $input['token'];
        $email = $input['email'];

        User::where('email', $email)->where('remember_token', $token)->firstOrFail();

        return $this->showMessage('인증 성공');
    }

    public function changePassword(AuthChangePasswordRequest $request)
    {
        $input = $request->input();
        assert(ArrayUtil::existKeysStrictly(['email', 'token', 'password', 'password_confirmation'], $input), '필드 확인');

        $token = $input['token'];
        $email = $input['email'];
        $password = $input['password'];
        $passwordConfirm = $input['password_confirmation'];

        if ($password !== $passwordConfirm) {
            return $this->showMessage('패스워드가 일치하지 않습니다', Response::HTTP_BAD_REQUEST);
        }

        $user = User::where('email', $email)->where('remember_token', $token)->firstOrFail();
        $newPassword = Hash::make($password);
        $user->password = $newPassword;
        $user->saveOrFail();

        return $this->showMessage('변경 성공');
    }

    public function resendAuthEmail(Request $request)
    {
        $input = $request->input();
        assert(ArrayUtil::existKeysStrictly(['email'], $input), '필드 확인');

        $email = $input['email'];

        $user = User::where('email', $email)->where('email_verified_at', null)->firstOrFail();
        $user['remember_token'] = Str::random(10);
        $user->save();
        SendAuthEmail::dispatch($user->email, $user->remember_token);

        return $this->showMessage("재전송 성공");
    }

    /**
     * Redirect the user to the Provider authentication page.
     *
     * @param $provider
     * @return JsonResponse
     */
    public function redirectToProvider($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        return Socialite::driver($provider)->stateless()->redirect();
    }

    /**
     * Obtain the user information from Provider.
     *
     * @param $provider
     * @return JsonResponse
     */
    public function handleProviderCallback($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (ClientException $exception) {
            return $this->error("Invalid credentials provided", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            DB::beginTransaction();
            $userCreated = User::firstOrCreate(
                [
                    'name' => $user->getName()
                ],
                [
                    'email' => $user->getEmail(),
                    'email_verified_at' => now(),
                ]
            );

            $userCreated->providers()->updateOrCreate(
                [
                    'provider' => $provider,
                    'provider_id' => $user->getId(),
                ],
                [
                    'avatar' => $user->getAvatar()
                ]
            );
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }

        $userCreated['token'] = $userCreated->createToken('login_token')->plainTextToken;
        $userCreated['topicsCount'] = Participant::where('id', $userCreated->id)->first()->topics()->count();
        $userCreated['opinionsCount'] = Participant::where('id', $userCreated->id)->first()->opinions()->count();
        $cookie = cookie('login_token', $userCreated['token'], 3600, null, null, null, false);

        return redirect()->to(config('app.frontend_endpoint'), 302)->withCookie($cookie);
    }

    /**
     * @param $provider
     * @return JsonResponse
     */
    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['kakao'])) {
            return $this->error('Please login using facebook, github or google', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
