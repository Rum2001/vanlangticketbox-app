<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImageEvent;

class ImageEventController extends Controller
{
    public function index(Request $request)
    {
        $event_id = $request->input('event_id');
        if ($event_id) {
            $image_events = ImageEvent::where('event_id', $event_id)->get();
        } else {
            $image_events = ImageEvent::all();
        }
        return response()->json($image_events);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'event_id' => 'required',
            'path' => 'required'
        ]);

        $image_event = new ImageEvent;
        $image_event->event_id = $request->input('event_id');
        $image_event->path = $request->input('path');
        $image_event->save();

        return response()->json($image_event);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'event_id' => 'required',
            'path' => 'required'
        ]);

        $image_event = ImageEvent::find($id);
        if (!$image_event) {
            return response()->json(['error' => 'Image event not found'], 404);
        }

        $image_event->event_id = $request->input('event_id');
        $image_event->path = $request->input('path');
        $image_event->save();

        return response()->json($image_event);
    }

    public function destroy($id)
    {
        $image_event = ImageEvent::find($id);
        if (!$image_event) {
            return response()->json(['error' => 'Image event not found'], 404);
        }
        $image_event->delete();

        return response()->json(['message' => 'Image event deleted']);
    }
}
