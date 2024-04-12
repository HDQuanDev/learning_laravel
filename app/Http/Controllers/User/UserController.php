<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class UserController extends Controller
{
    use HasApiTokens;
    public function login(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        try {
            $request->validate([
                'username' => 'required',
                'password' => 'required'
            ]);
            $credentials = $request->only('username', 'password');
            if (!Auth::attempt($credentials)) {
                throw ValidationException::withMessages([
                    'username' => 'Username không hợp lệ'
                ]);
            }

            $user = User::getByUsername($request->username);
            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Password không hợp lệ');
            }

            //kiểm tra xem user đã có token chưa
            $existingToken = $user->tokens->first();

            if ($existingToken && $user->remember_token != null) {
                $tokenResult = $user->remember_token;
            } else {
                $tokenResult = $user->createToken('authToken')->plainTextToken;
                $save_token = User::UpdateToken($request->username, $tokenResult);
                if (!$save_token) {
                    throw new \Exception('Lỗi lưu token');
                }
            }

            return response()->json([
                'status' => 'success',
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
            ]);
        } catch (ValidationException $validationException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Xác thực thất bại',
                'error' => $validationException->errors(),
            ], 401);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => 'Đã có lỗi xảy ra',
                'error' => $error->getMessage(),
                'error_line' => $error->getLine(),
                'error_file' => $error->getFile(),
            ], 401);
        }
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'username' => 'required',
                'password' => 'required',
                're_password' => 'required'
            ]);
            if (User::checkUsername($request->username)) {
                throw new \Exception('Username đã tồn tại');
            }
            if ($request->password != $request->re_password) {
                throw new \Exception('Password không trùng khớp');
            }

            $user = User::createUser($request->name, $request->username, Hash::make($request->password));

            if (!$user) {
                throw new \Exception('Lỗi tạo user');
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Tạo user thành công'
                ]);
            }
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => 'Đã có lỗi xảy ra',
                'error' => $error->getMessage(),
            ], 401);
        }
    }
}
