<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\FeedbackEvent;

class FeedbackEventController extends Controller
{
    public function index()
    {
        $feedbacks = FeedbackEvent::all();
        return response()->json($feedbacks);
    }

    public function show($id)
    {
        $feedback = FeedbackEvent::findOrFail($id);
        return response()->json($feedback);
    }

    public function store(Request $request)
    {
        $feedback = new FeedbackEvent;
        $feedback->event_id = $request->event_id;
        $feedback->rating = $request->rating;
        $feedback->comment = $request->comment;
        $feedback->save();
        return response()->json(['success' => true, 'message' => 'Feedback added successfully!']);
    }

    public function update(Request $request, $id)
    {
        $feedback = FeedbackEvent::findOrFail($id);
        $feedback->event_id = $request->event_id;
        $feedback->rating = $request->rating;
        $feedback->comment = $request->comment;
        $feedback->save();
        return response()->json(['success' => true, 'message' => 'Feedback updated successfully!']);
    }

    public function destroy($id)
    {
        $feedback = FeedbackEvent::findOrFail($id);
        $feedback->delete();
        return response()->json(['success' => true, 'message' => 'Feedback deleted successfully!']);
    }

    public function getFeedbackByEventId($event_id)
    {
        $feedbacks = FeedbackEvent::where('event_id', $event_id)->get();
        return response()->json($feedbacks);
    }
}
