<?php

namespace App\Http\Controllers\Api\Auth;

use App\Constants\Application;
use App\Helpers\System\LogHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\WebAuthRequest;
use App\Models\Auth\User;
use App\Traits\ResponseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthenticateController extends Controller
{
    /**
     * Get the guard to be used during authentication.
     *
     */
    use ResponseType;
    public function guard()
    {
        return Auth::guard();
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $other = [])
    {
        return response()->json(array_merge($other, [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => config('sanctum.expiration'),
        ]));
    }

    public function mobileAuthenticate(WebAuthRequest $request)
    {
        $key = $request->get('key');
        $user = $this->checkUser($request, $key);
        if (empty($user)) {
            abort(400, 'Tên đăng nhập hoặc mật khẩu không đúng');
        }
        $token = $user->createToken('login_token');
        Cookie::make('token', $token->plainTextToken);
        $user->save();
        LogHelper::logLogin($user, $token->accessToken->getKey(), 'login.mobile', $request->get('device_id'));
        return $this->respondWithToken($token->plainTextToken);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }

    private function checkUser(Request $request, $key)
    {
        $credentials = $request->only('login', 'password');
        if ($this->checkEmail($credentials["login"])) {
            $credentials["email"] = $credentials["login"];
            unset($credentials["login"]);
        }
        $query = User::where('login', $credentials['email'] ?? $credentials['login']);
        if (isset($credentials['email'])) {
            $query->orWhere('email', $credentials['email']);
        }
        $user = $query->with(['groups.groupApplications:code'])->first();
        if (empty($user)) {
            abort(400, 'Tên đăng nhập hoặc mật khẩu không đúng');
        }
        if (!Hash::check($credentials['password'], $user->password)) {
            abort(400, 'Tên đăng nhập hoặc mật khẩu không đúng');
        }
        if (!$user->isActive()) {
            abort(401, 'Người dùng bị chặn, liên hệ quản trị hệ thống để biết thêm thông tin');
        }
        $keys = Application::KEYS;
        foreach ($user->groups->toArray() as $group) {
            $app = array_filter($group['group_applications'], function ($e) use ($keys, $key, $user) {
                return $keys[$e['code']] == $key;
            });
            if ($app !== []) {
                return $user;
            }
        }
        abort(401, 'Bạn không có quyền truy cập');
    }

    public function editPasswordFirstLogin(Request $request)
    {
        $request->validate(
            [
                'oldPassword' => ['required', 'string', 'max:255', 'min:1'],
                'newPassword' => ['required', Password::min(8)->letters()->mixedCase()->numbers()],
                'confirmPassword' => 'required|same:newPassword|min:1',
            ], [], [
                'oldPassword' => __('user-manager-modal.field.oldPassword'),
                'newPassword' => __('user-manager-modal.field.newPassword'),
                'confirmPassword' => __('user-manager-modal.field.confirmPassword'),
            ]
        );
        $info = $request->all();
        $user = User::where('id', $request->user()->getKey())->first();
        if ($user && $user->first_login) {
            if (Hash::check($info['oldPassword'], $user['password'])) {
                $newPasswordHash = Hash::make($info['newPassword']);
                User::where('id', $request->user()->getKey())->update([
                    'password' => $newPasswordHash,
                    'first_login' => false,
                ]);
                return response()->json([
                    'message' => "Cập nhật mật khẩu thành công",
                ], 200);
            } else {
                return response()->json(['error' => 'Mật khẩu cũ không trùng khớp'], 404);
            }
        };
        return response()->json([
            'message' => 'Thay đổi mật khẩu thất bại',
        ], 400);
    }

    public function checkEmail($str)
    {
        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
        if (preg_match($regex, $str)) {
            return true;
        }
        return false;
    }
}
