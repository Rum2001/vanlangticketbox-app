<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Scales;
use App\Models\Event;
class ScaleController extends Controller
{
    public function index()
    {
        $Scales = Scales::all();
        return response()->json($Scales);
    }

    public function store(Request $request)
    {
        $Scales = new Scales;
        $Scales->name = $request->name;
        $Scales->save();
        return response()->json(['message' => 'Scales created successfully', 'Scales' => $Scales]);
    }

    public function update(Request $request, $id)
{
    $scales = Scales::find($id);
    if ($scales) {
        // Lưu trữ tên danh mục ban đầu
        $oldName = $scales->name;

        $scales->name = $request->input('name');
        $scales->save();

        // Cập nhật trường categories trong bảng Events
        Event::where('scales', $oldName)
            ->update(['scales' => $scales->name]);

        return response()->json($scales);
    } else {
        return response()->json(['error' => 'Quy mô không tìm thấy'], 404);
    }
}

    public function destroy($id)
    {
        $scales = Scales::find($id);
        if ($scales) {
            // Kiểm tra xem danh mục có sự kiện liên kết không
            if ($scales->events()->exists()) {
                return response()->json(['error' => 'Quy mô chứa sự kiện không thể xóa'], 422);
            }
            
            $scales->delete();
            return response()->json(['message' => 'Quy mô đã được xóa']);
        } else {
            return response()->json(['error' => 'Không tìm thấy quy mô'], 404);
        }
    }
}
