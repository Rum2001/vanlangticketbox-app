<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Event;
class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Categories::all();
        return response()->json($categories);
    }

    public function show($id)
    {
        $categories = Categories::find($id);
        if ($categories) {
            return response()->json($categories);
        } else {
            return response()->json(['error' => 'Categories not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $categories = new Categories();
        $categories->name = $request->input('name');
        $categories->save();
        return response()->json($categories, 201);
    }
    public function update(Request $request, $id)
{
    $categories = Categories::find($id);
    if ($categories) {
        // Lưu trữ tên danh mục ban đầu
        $oldName = $categories->name;

        $categories->name = $request->input('name');
        $categories->save();

        // Cập nhật trường categories trong bảng Events
        Event::where('categories', $oldName)
            ->update(['categories' => $categories->name]);

        return response()->json($categories);
    } else {
        return response()->json(['error' => 'Danh mục không tìm thấy'], 404);
    }
}

    public function destroy($id)
    {
        $categories = Categories::find($id);
        if ($categories) {
            // Kiểm tra xem danh mục có sự kiện liên kết không
            if ($categories->events()->exists()) {
                return response()->json(['error' => 'Danh mục đã chứa sự kiện không thể xóa'], 422);
            }
            
            $categories->delete();
            return response()->json(['message' => 'Danh mục đã được xóa']);
        } else {
            return response()->json(['error' => 'Không tìm thấy danh mục'], 404);
        }
    }
    

    public function search(Request $request)
    {
        $query = $request->input('query');
        $categories = Categories::where('name', 'LIKE', "%$query%")->get();
        return response()->json($categories);
    }
}
