<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Locations;
use App\Models\Event;
class LocationController extends Controller
{
    public function index()
    {
        $locations = Locations::all();
        return response()->json($locations);
    }

    public function store(Request $request)
    {
        $locations = new Locations;
        $locations->name = $request->name;
        $locations->save();
        return response()->json(['message' => 'Locations created successfully', 'locations' => $locations]);
    }

    public function update(Request $request, $id)
{
    $locations = Locations::find($id);
    if ($locations) {
        // Lưu trữ tên danh mục ban đầu
        $oldName = $locations->name;

        $locations->name = $request->input('name');
        $locations->save();

        // Cập nhật trường categories trong bảng Events
        Event::where('locations', $oldName)
            ->update(['locations' => $locations->name]);

        return response()->json($locations);
    } else {
        return response()->json(['error' => 'Quy mô không tìm thấy'], 404);
    }
}

    public function destroy($id)
    {
        $locations = Locations::find($id);
        if ($locations) {
            // Kiểm tra xem danh mục có sự kiện liên kết không
            if ($locations->events()->exists()) {
                return response()->json(['error' => 'Địa điểm có sự kiện đặt chỗ không thể xóa'], 422);
            }
            
            $locations->delete();
            return response()->json(['message' => 'Địa điểm đã được xóa']);
        } else {
            return response()->json(['error' => 'Không tìm thấy địa điểm'], 404);
        }
    }
}
