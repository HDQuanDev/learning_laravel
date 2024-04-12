<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserGetController extends Controller
{
    public function get_list()
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

        $get_user = User::getUser();
        return response()->json([
            'status' => 'success',
            'message' => 'Lấy danh sách user thành công',
            'data' => $get_user
        ]);
    }

    public function get_info_by_id(Request $request){
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

        $request->validate([
            'id' => 'required'
        ]);

        $id = $request->id;
        if (!User::checkValidById($id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Id không hợp lệ'
            ]);
        }

        $get_user = User::getUserById($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Lấy thông tin user thành công',
            'data' => $get_user
        ]);
    }
}
