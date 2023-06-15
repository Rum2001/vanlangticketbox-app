<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\QuestionEvent;

class QuestionEventController extends Controller
{
    // API để lấy toàn bộ dữ liệu của bảng QuestionEvent
    public function index()
    {
        $questions = QuestionEvent::all();
        return response()->json([
            'success' => true,
            'data' => $questions
        ]);
    }

    // API để tìm kiếm câu hỏi theo event_id
    public function showByEventId($event_id)
    {
        $questions = QuestionEvent::where('event_id', $event_id)->get();
        return response()->json([
            'success' => true,
            'data' => $questions
        ]);
    }

    // API để thêm một câu hỏi mới vào bảng QuestionEvent
    public function store(Request $request)
    {
        $question = new QuestionEvent();
        $question->event_id = $request->event_id;
        $question->question = $request->question;

        $question->save();

        return response()->json([
            'success' => true,
            'data' => $question
        ]);
    }

    // API để sửa một câu hỏi trong bảng QuestionEvent
    public function update(Request $request, $id)
    {
        $question = QuestionEvent::find($id);

        if (!$question) {
            return response()->json([
                'success' => false,
                'message' => 'Câu hỏi không tồn tại'
            ], 400);
        }

        $question->event_id = $request->event_id;
        $question->question = $request->question;

        $question->save();

        return response()->json([
            'success' => true,
            'data' => $question
        ]);
    }

    // API để xóa một câu hỏi trong bảng QuestionEvent
    public function destroy($id)
    {
        $question = QuestionEvent::find($id);

        if (!$question) {
            return response()->json([
                'success' => false,
                'message' => 'Câu hỏi không tồn tại'
            ], 400);
        }

        $question->delete();

        return response()->json([
            'success' => true,
            'message' => 'Câu hỏi đã được xóa'
        ]);
    }
}
