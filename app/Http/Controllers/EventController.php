<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\StatusUpdateMail;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('created_at', 'desc')->get();
        return response()->json($events);
    }
    
    public function getApprovedEvents()
    {
        $events = Event::where('status', 'Công Khai')->get();
        return response()->json($events);
    }


    public function show($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        return response()->json($event);
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('q');
        $events = Event::where('title', 'LIKE', "%$searchTerm%")->get();
        return response()->json($events);
    }

    public function store(Request $request)
    {
        $event = new Event();
        $event->title = $request->input('title');
        $event->email = $request->input('email');
        $event->categories = $request->input('categories');
        $event->categories_id = $request->input('categories_id');
        $event->description = $request->input('description');
        $event->locations = $request->input('locations');
        $event->locations_id = $request->input('locations_id');
        $event->faculties = $request->input('faculties');
        $event->faculties_id = $request->input('faculties_id');
        $event->scales = $request->input('scales');
        $event->scales_id = $request->input('scales_id');
        if ($request->file('path')) {
            $path = $request->file('path');
            $fileName = time() . '.' . $path->getClientOriginalExtension();
            $filePath = $path->storeAs('public/uploads/path', $fileName);
            $imgPath = $fileName;
            $event->path = $imgPath;
        }
        else{
            $event->path = 'English.jpeg';
        }
        $event->quantity_ticket = $request->input('quantity_ticket');
        $event->start_time = $request->input('start_time');
        $event->end_time = $request->input('end_time');
        $event->save();
        return response()->json($event);
    }

    public function update(Request $request, $id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        $event->title = $request->input('title');
        $event->email = $request->input('email');
        $event->categories = $request->input('categories');
        $event->description = $request->input('description');
        $event->locations = $request->input('locations');
        $event->faculties = $request->input('faculties');
        $event->scales = $request->input('scales');
        if ($request->file('path')) {
            $path = $request->file('path');
            $fileName = time() . '.' . $path->getClientOriginalExtension();
            $filePath = $path->storeAs($fileName);
            $imgPath = 'uploads/path/' . $fileName;
            $event->path = $imgPath;
        }

        $event->quantity_ticket = $request->input('quantity_ticket');
        $event->start_time = $request->input('start_time');
        $event->end_time = $request->input('end_time');
        $event->status = $request->input('status');
        $event->save();
        return response()->json($event);
    }

    public function destroy($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        $event->delete();
        return response()->json(['message' => 'Event deleted successfully']);
    }
    public function searchByEmail(Request $request)
    {
        $email = $request->input('email');

        $events = Event::where(`events` . 'email', $email)->get();

        return response()->json($events);
    }
    

    public function updateStatus(Request $request, $id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        $event->status = $request->input('status');
        $event->save();
    
        // Gửi email thông báo
        $recipientEmail = $event->email;
        $status = $event->status;
        Mail::to($recipientEmail)->send(new StatusUpdateMail($event, $status));
    
        return response()->json($event);
    }
    public function updateTicket(Request $request, $id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        $event->quantity_ticket = $request->input('quantity_ticket');
        $event->save();

        return response()->json($event);
    }
}
