<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    // Hàm lấy danh sách Users
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Hàm lấy thông tin một User theo id
    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['error' => 'User not found']);
        }
    }

    // Hàm thêm một User mới
    public function store(Request $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->phone = $request->phone;
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarPath = 'uploads/avatars/' . $avatar->getClientOriginalName();
            $avatar->move(public_path('uploads/avatars'), $avatarPath);
            $user->avatar = $avatarPath;
        }
        $user->masv = $request->masv;
        $user->save();
        return response()->json(['success' => "Thêm Thành Công"]);
    }

    // Hàm cập nhật thông tin một User
    public function update(Request $request, $id)
    {
        // return $request->all();
        $user = User::find($id);
        if ($user) {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->phone = $request->phone;
            $user->masv = $request->masv;
            $user->save();
            return response()->json(['success' => 'User updated successfully']);
        } else {
            return response()->json(['error' => 'User not found']);
        }
    }
    public function role(Request $request, $id)
    {
        // return $request->all();
        $user = User::find($id);
        if ($user) {
            $user->role = $request->role;
            $user->save();
            return response()->json(['success' => 'Roles updated successfully']);
        } else {
            return response()->json(['error' => 'User not found']);
        }
    }
    public function status(Request $request, $id)
    {
        // return $request->all();
        $user = User::find($id);
        if ($user) {
            $user->status = $request->status;
            $user->save();
            return response()->json(['success' => 'Status updated successfully']);
        } else {
            return response()->json(['error' => 'User not found']);
        }
    }
    
    // Hàm xóa một User
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['success' => 'User deleted successfully']);
        } else {
            return response()->json(['error' => 'User not found']);
        }
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'Sai Email hoặc Password'
            ], 401);
        }
    
        if ($user->status === 'Vô hiệu hóa') {
            return response([
                'message' => 'Tài khoản của bạn đã bị vô hiệu'
            ], 401);
        }
        if ($user->role === 'User') {
            return response([
                'message' => 'Tài khoản của bạn không đủ quyền truy cập'
            ], 401);
        }
    
        $token = $user->createToken('API Token')->accessToken;
    
        $response = [
            'user' => [
                'email' => $user->email,
                'name' => $user->name,
                'role' => $user->role,
                'status' => $user->status,
            ],
            'token' => $token
        ];
    
        return response($response, 201);
    }
    
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Logged out successfully']);
    }

}
