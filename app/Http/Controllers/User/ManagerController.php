<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Laravel\Sanctum\HasApiTokens;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ManagerController extends Controller
{
    function edit_user(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Xác thực thất bại',
                'error' => 'Chưa đăng nhập'
            ]);
        }
        $user = Auth::user();
        if ($user->admin != 'true') {
            return response()->json([
                'status' => 'error',
                'message' => 'Xác thực thất bại',
                'error' => 'Không có quyền truy cập'
            ]);
        }

        try {
            $request->validate([
                'id' => 'required',
                'username' => 'required',
                'name' => 'required'
            ]);
            $id = $request->id;
            if (!User::checkValidById($id)) {
                throw new \Exception('Id không hợp lệ');
            }
            $username = $request->username;
            if (User::checkUsername($username)) {
                throw new \Exception('Username đã tồn tại');
            }
            $password = $request->password;
            $name = $request->name;
            $update = User::updateUser($id, $username, $name, Hash::make($password));
            if (!$update) {
                throw new \Exception('Lỗi cập nhật user');
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật user thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cập nhật user thất bại',
                'error' => $e->getMessage()
            ]);
        }
    }

    function delete_user(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Xác thực thất bại',
                'error' => 'Chưa đăng nhập'
            ]);
        }
        $user = Auth::user();
        if ($user->admin != 'true') {
            return response()->json([
                'status' => 'error',
                'message' => 'Xác thực thất bại',
                'error' => 'Không có quyền truy cập'
            ]);
        }

        try {
            $request->validate([
                'id' => 'required'
            ]);
            $id = $request->id;
            if (!User::checkValidById($id)) {
                throw new \Exception('Id không hợp lệ');
            }
            $delete = User::deleteUser($id);
            if (!$delete) {
                throw new \Exception('Lỗi xóa user');
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Xóa user thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Xóa user thất bại',
                'error' => $e->getMessage()
            ]);
        }
    }
}
