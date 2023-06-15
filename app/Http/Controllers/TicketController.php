<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    /**
     * Lấy danh sách tất cả các ticket
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tickets = Ticket::all();

        return response()->json($tickets);
    }

    /**
     * Lấy thông tin của một ticket theo id
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);

        return response()->json($ticket);
    }

    /**
     * Thêm một ticket mới
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $ticket = Ticket::create($request->all());

        return response()->json($ticket, 201);
    }

    /**
     * Cập nhật thông tin của một ticket theo id
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update($request->all());

        return response()->json($ticket, 200);
    }

    /**
     * Xóa một ticket theo id
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Ticket::findOrFail($id)->delete();

        return response()->json(null, 204);
    }

    /**
     * Lấy danh sách các ticket của một event theo event_id
     *
     * @param  int  $event_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByEventId($event_id)
    {
        $tickets = Ticket::where('event_id', $event_id)->get();

        return response()->json($tickets);
    }
}