<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EventManager;

class EventManagerController extends Controller
{
    public function index()
    {
        $eventManagers = EventManager::all();
        return response()->json($eventManagers, 200);
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $eventManagers = EventManager::where('name', 'LIKE', '%'.$search.'%')
                                        ->orWhere('email', 'LIKE', '%'.$search.'%')
                                        ->get();
        return response()->json($eventManagers, 200);
    }

    public function store(Request $request)
    {
        $eventManager = new EventManager;
        $eventManager->name = $request->input('name');
        $eventManager->email = $request->input('email');
        $eventManager->phone = $request->input('phone');
        $eventManager->save();
        return response()->json($eventManager, 201);
    }

    public function update(Request $request, $id)
    {
        $eventManager = EventManager::find($id);
        $eventManager->name = $request->input('name');
        $eventManager->email = $request->input('email');
        $eventManager->phone = $request->input('phone');
        $eventManager->save();
        return response()->json($eventManager, 200);
    }

    public function destroy($id)
    {
        $eventManager = EventManager::find($id);
        $eventManager->delete();
        return response()->json(null, 204);
    }
}
