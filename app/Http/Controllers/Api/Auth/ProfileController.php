<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Res\Device;
use App\Models\Res\Partner;
use App\Traits\ResponseType;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    use ResponseType;
    protected $exts = ['jpeg', 'jpg', 'png', 'raw', 'svg', 'gif', 'webp', 'psd'];
    public function me(Request $request)
    {
        $user = $request->user();
        // $user->load(['employee', 'partner']);
        // $user->partner->makeHidden(['image', 'image_medium', 'image_small']);
        $query = User::query();
        if (config('app.check_user_device')) {
            $query
                ->whereHas('devices', function ($q) {
                    $q->where('logout', false);
                })
                ->with(['devices']);
        }
        $info = $query->find($user->id)->load(['employee', 'partner', 'devices']);
        if ($info->partner !== null) {
            $info->partner->makeHidden(['image', 'image_medium', 'image_small']);
        }
        if (isset($info)) {
            $info = $info->toArray();
            $info['devices'] = $info['devices'] ?? [];
        }
        return response()->json(['user' => $info]);
    }

    public function checkToken()
    {
        // Middleware auth:sanctum will handle logic for this method

        return response()->json([
            'isValid' => true,
        ]);
    }
    public function logoutDevice(Request $request)
    {
        $user = $request->user();
        $token = $request->user()->currentAccessToken();
        $user->tokens()->where('tokenable_id', $user->getKey())->where('id', '!=', $token->getKey())->delete();
        $devices = Device::where('user_id', $user->getKey())->where('token_id', '!=', $token->getKey())->get();
        foreach ($devices as $device) {
            $device->update([
                'logout' => true,
            ]);
        }
        return response()->json(['message' => 'Successfully'], 200);

    }
    public function editProfile(Request $request)
    {
        $request->validate(
            [
                'id' => ['required'],
                'email' => ['required', 'email'],
            ]
        );
        $id = $request->input('id');
        $user = User::findOrFail($id);
        if ($user) {
            User::where('id', $id)->update(['email' => $request->email]);
            return response()->json(
                [
                    'message' => 'Thay đổi email thành công',
                    'data' => $user,
                ]
            );
        }
        return response()->json([
            'message' => "Thay đổi email thất bại",
        ]);
    }
    public function editPassword(Request $request)
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
        $user = User::findOrFail($request->user()->id);
        if ($user) {
            if (Hash::check($info['oldPassword'], $user['password'])) {
                $newPasswordHash = Hash::make($info['newPassword']);
                User::where('id', $request->user()->id)->update(['password' => $newPasswordHash]);
                return response()->json([
                    'message' => "Cập nhật mật khẩu thành công",
                ]);
            } else {
                return response()->json(['error' => 'Mật khẩu cũ không trùng khớp'], 404);
            }
        };
        return response()->json([
            'message' => 'Thay đổi mật khẩu thất bại',
        ]);
    }

    public function getAvatar(Request $request)
    {
        $user = $request->user();
        $avatar = Partner::where('id', $user->partner_id)->first()->image;
        if ($avatar === null) {
            return response()->json(['message' => 'Image not found'], 404);
        }
        $avatar = stream_get_contents($avatar);
        $avatar = pg_unescape_bytea($avatar);
        $avatar = htmlspecialchars($avatar);
        $dataUri = 'data:image/jpeg;base64,' . $avatar;
        return $dataUri;
    }
    public function editAvatar(Request $request)
    {

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $ext = $image->extension();
            if (!in_array($ext, $this->exts)) {
                return response()->json([
                    'message' => 'Chỉ chấp nhận định dạng jpg, jpeg, png',
                ], 400);
            }
            $user = $request->user();
            $imageData = base64_encode(file_get_contents($image));
            Partner::where('id', $user->partner_id)->update([
                "has_image" => true,
                "image" => $imageData,
            ]);
            return response()->json([
                'message' => 'Cập nhật ảnh đại diện thành công',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Vui lòng chọn ảnh đại diện',
            ], 400);
        }
    }
}
