<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AttendeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attendees = Attendee::all();

        return response()->json($attendees);
    }
    public function getByEventName($eventName)
    {
        $attendees = Attendee::where('event_name', $eventName)->get();

        return response()->json($attendees);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attendee = Attendee::find($id);
        if (!$attendee) {
            return response()->json(['message' => 'Attendee not found'], 404);
        }
        return response()->json($attendee);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'event_name' => 'required',
            'location' => 'required',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => "Email của bạn sai định dạng",
            ]);
        }
    
        // Kiểm tra định dạng email
        $emailValidator = Validator::make(['email' => $request->email], [
            'email' => 'email',
        ]);
    
        if ($emailValidator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email format.',
            ]);
        }
    
        // Kiểm tra sự tồn tại của email và event_name trong cơ sở dữ liệu
        $existingAttendee = Attendee::where('email', $request->email)
            ->where('event_name', $request->event_name)
            ->first();
    
        if ($existingAttendee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn đã đăng ký sự kiện này trước đó',
            ]);
        }
    
        // Tìm event_id tương ứng với event_name
        $event = Event::where('title', $request->event_name)->first();
    
        if (!$event) {
            return response()->json([
                'status' => 'error',
                'message' => 'Event not found.',
            ]);
        }
    
        // Kiểm tra và cập nhật quantity_ticket trong bảng events
        if ($event->quantity_ticket > 0) {
            $event->quantity_ticket -= 1;
            $event->save();
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Sự kiện đã đủ số lượng tham gia',
            ]);
        }
    
        $attendee = new Attendee;
        $attendee->email = $request->email;
        $attendee->event_name = $request->event_name;
        $attendee->location = $request->location; // Thêm trường location
        $attendee->start_time = $request->start_time; // Thêm trường start_time
        $attendee->end_time = $request->end_time; // Thêm trường end_time
        $attendee->event_id = $event->id; // Lưu event_id vào bảng attendees
        $attendee->verify_code = $this->generateVerifyCode($request->email, $request->event_name);
        $attendee->save();
    
        $this->sendEmail($attendee->email, $attendee->event_name, $attendee->verify_code);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Đăng ký thành công',
            'data' => $attendee,
        ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email,' . $id,
            'event_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ]);
        }

        $attendee = Attendee::find($id);

        if (!$attendee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Attendee not found.',
            ]);
        }
        $attendee->email = $request->email;
        $attendee->event_name = $request->event_name;
        $attendee->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Đăng ký thành công',
            'data' => $attendee,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attendee = Attendee::find($id);

        if (!$attendee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Attendee not found.',
            ]);
        }

        $attendee->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Attendee deleted successfully.',
        ]);
    }
    public function searchAttendeeByEmail(Request $request)
    {
        $email = $request->input('email');

        $attendee = Attendee::where(`attendes` . 'email', $email)->get();

        return response()->json($attendee);
    }
    /**
     * Search for attendees based on given criteria.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $query = Attendee::query();

        if ($request->has('email')) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
        }

        if ($request->has('event_name')) {
            $query->where('event_name', 'like', '%' . $request->input('event_name') . '%');
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        $attendees = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $attendees,
        ]);
    }

    /**
     * Generate the verify code based on email and event name.
     *
     * @param  string  $email
     * @param  string  $event_name
     * @return string
     */
    private function generateVerifyCode($email, $event_name)
    {
        $data = $email . $event_name;
        $verify_code = md5($data); // You can use other hashing algorithms if desired

        return $verify_code;
    }

    /**
     * Send registration verification email.
     *
     * @param  string  $email
     * @param  string  $event_name
     * @param  string  $verify_code
     * @return void
     */
    private function sendEmail($email, $event_name, $verify_code)
    {
        // Generate the QR code as SVG
        $qrCode = QrCode::format('svg')->size(150)->generate($verify_code);

        // Convert the SVG QR code to a string
        $svgQRCode = $qrCode->__toString();
        // dd($svgQRCode);

        // Send email with verify code and SVG QR code
        Mail::send('emails.attendee_verification', [
            'email' => $email,
            'event_name' => $event_name,
            'qrCode' => $svgQRCode,
        ], function ($message) use ($email, $event_name) {
            $message->to($email)
                ->subject('Event Registration Verification');
        });
    }
    // Điểm danh
    public function updateStatusByVerifyCode(Request $request, $verifyCode)
    {
        $attendee = Attendee::where('verify_code', $verifyCode)->first();

        if (!$attendee) {
            return response()->json([
                'message' => 'Attendee not found.',
            ], 404);
        }

        $attendee->status = 'Đã điểm danh';
        $attendee->save();

        return response()->json([
            'message' => 'Attendee status updated successfully.',
            'data' => $attendee,
        ]);
    }
    public function storeMultiple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attendees' => 'required|array',
            'attendees.*.email' => 'required|email',
            'attendees.*.event_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ]);
        }

        $attendeesData = $request->input('attendees');

        $attendees = [];
        $emails = []; // Lưu trữ danh sách email

        foreach ($attendeesData as $attendeeData) {
            $attendee = new Attendee;
            $attendee->email = $attendeeData['email'];
            $attendee->event_name = $attendeeData['event_name'];
            $attendee->verify_code = $this->generateVerifyCode($attendeeData['email'], $attendeeData['event_name']);
            $attendee->save();

            $attendees[] = $attendee;
            $emails[] = $attendee->email; // Thêm email vào danh sách

            // Không gửi email ngay tại đây

            // $this->sendEmail($attendee->email, $attendee->event_name, $attendee->verify_code);
        }

        // Gửi email cho toàn bộ email trong danh sách
        foreach ($emails as $email) {
            $this->sendEmail($email, $attendeesData[0]['event_name'], $attendeesData[0]['verify_code']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Attendees created successfully.',
            'data' => $attendees,
        ]);
    }
}
