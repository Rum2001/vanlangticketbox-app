<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faculties;
use App\Models\Event;
class FacultyController extends Controller
{
    public function index()
    {
        $faculties = Faculties::all();
        return response()->json($faculties);
    }

    public function store(Request $request)
    {
        $faculties = new Faculties;
        $faculties->name = $request->name;
        $faculties->save();
        return response()->json(['message' => 'Faculties created successfully', 'faculties' => $faculties]);
    }
    public function update(Request $request, $id)
{
    $faculties = Faculties::find($id);
    if ($faculties) {
        // Lưu trữ tên danh mục ban đầu
        $oldName = $faculties->name;

        $faculties->name = $request->input('name');
        $faculties->save();

        // Cập nhật trường categories trong bảng Events
        Event::where('faculties', $oldName)
            ->update(['faculties' => $faculties->name]);

        return response()->json($faculties);
    } else {
        return response()->json(['error' => 'Quy mô không tìm thấy'], 404);
    }
}

    public function destroy($id)
    {
        $faculties = Faculties::find($id);
        if ($faculties) {
            // Kiểm tra xem danh mục có sự kiện liên kết không
            if ($faculties->events()->exists()) {
                return response()->json(['error' => 'Danh mục khoa ban đã chứa sự kiện không thể xóa'], 422);
            }
            
            $faculties->delete();
            return response()->json(['message' => 'Danh mục khoa ban đã được xóa']);
        } else {
            return response()->json(['error' => 'Không tìm thấy danh mục khoa ban'], 404);
        }
    }
}
