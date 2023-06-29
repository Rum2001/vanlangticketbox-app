<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/users', [\App\Http\Controllers\UserController::class, 'index']); // Lấy danh sách Users
Route::get('/users/{id}', [\App\Http\Controllers\UserController::class, 'show']); // Lấy thông tin một User theo id
Route::post('/users', [\App\Http\Controllers\UserController::class, 'store']); // Thêm một User mới
Route::put('/users/{id}', [\App\Http\Controllers\UserController::class, 'update']); // Cập nhật thông tin một User
Route::put('/role/{id}', [\App\Http\Controllers\UserController::class, 'role']); // Cập nhật thông tin role một User
Route::put('/status/{id}', [\App\Http\Controllers\UserController::class, 'status']); // Cập nhật thông tin status một User
Route::delete('/users/{id}', [\App\Http\Controllers\UserController::class, 'destroy']); // Xóa một User
Route::post('/users/login', '\App\Http\Controllers\UserController@login');
Route::middleware('auth:api')->group(function () {
    Route::post('/users/logout', '\App\Http\Controllers\UserController@logout');
});
Route::get('/tickets', [\App\Http\Controllers\TicketController::class, 'index']); // Lấy danh sách Ticket
Route::get('/tickets/{id}', [App\Http\Controllers\TicketController::class, 'show']); // Lấy thông tin một Ticket theo id
Route::post('/tickets', [\App\Http\Controllers\TicketController::class, 'store']); // Thêm một Ticket mới
Route::put('/tickets/{id}', [\App\Http\Controllers\TicketController::class, 'update']); // Cập nhật thông tin một Ticket
Route::delete('/tickets/{id}', [\App\Http\Controllers\TicketController::class, 'destroy']);//Xóa một Ticket mới
Route::get('/events', [\App\Http\Controllers\EventController::class, 'index']);
Route::get('/events/approved', [\App\Http\Controllers\EventController::class, 'getApprovedEvents']);
Route::get('/events/{id}', [\App\Http\Controllers\EventController::class, 'show']);
Route::get('/events/search', [\App\Http\Controllers\EventController::class, 'search']);
Route::post('/events', [\App\Http\Controllers\EventController::class, 'store']);
Route::put('/events/{id}', [\App\Http\Controllers\EventController::class, 'update']);
Route::put('/events/{id}/status', [\App\Http\Controllers\EventController::class, 'updateStatus']);
Route::put('/events/{id}/ticket', [\App\Http\Controllers\EventController::class, 'updateTicket']);
Route::delete('/events/{id}', [\App\Http\Controllers\EventController::class, 'destroy']);
Route::get('/email', [\App\Http\Controllers\EventController::class, 'searchByEmail']);
Route::prefix('roles')->group(function  () {
    Route::get('/', [\App\Http\Controllers\RoleController::class, 'index']);
    Route::post('/', [\App\Http\Controllers\RoleController::class, 'store']);
    Route::get('/{id}', [\App\Http\Controllers\RoleController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\RoleController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\RoleController::class, 'destroy']);
});
Route::get('/question-events', [\App\Http\Controllers\QuestionEventController::class, 'index']);
Route::get('/question-events/event/{event_id}', [\App\Http\Controllers\QuestionEventController::class, 'showByEventId']);
Route::post('/question-events', [\App\Http\Controllers\QuestionEventController::class, 'store']);
Route::put('/question-events/{id}', [\App\Http\Controllers\QuestionEventController::class, 'update']);
Route::delete('/question-events/{id}', [\App\Http\Controllers\QuestionEventController::class, 'destroy']);
Route::resource('image-events', '\App\Http\Controllers\ImageEventController');
Route::get('/feedback-events', '\App\Http\Controllers\FeedbackEventController@index');
Route::get('/feedback-events/{id}', '\App\Http\Controllers\FeedbackEventController@show');
Route::post('/feedback-events', '\App\Http\Controllers\FeedbackEventController@store');
Route::put('/feedback-events/{id}', '\App\Http\Controllers\FeedbackEventController@update');
Route::delete('/feedback-events/{id}', '\App\Http\Controllers\FeedbackEventController@destroy');
Route::get('/feedback-events/by-event/{event_id}', '\App\Http\Controllers\FeedbackEventController@getFeedbackByEventId');
Route::get('/event-managers', [\App\Http\Controllers\EventManagerController::class, 'index']);
Route::get('/event-managers/search', [\App\Http\Controllers\EventManagerController::class, 'search']);
Route::post('/event-managers', [\App\Http\Controllers\EventManagerController::class, 'store']);
Route::put('/event-managers/{id}', [\App\Http\Controllers\EventManagerController::class, 'update']);
Route::delete('/event-managers/{id}', [\App\Http\Controllers\EventManagerController::class, 'destroy']);
Route::get('/categories', [\App\Http\Controllers\CategoriesController::class, 'index']);
Route::get('/categories/{id}', [\App\Http\Controllers\CategoriesController::class, 'show']);
Route::post('/categories', [\App\Http\Controllers\CategoriesController::class, 'store']);
Route::put('/categories/{id}', [\App\Http\Controllers\CategoriesController::class, 'update']);
Route::delete('/categories/{id}', [\App\Http\Controllers\CategoriesController::class, 'destroy']);
Route::get('/categories/search', [\App\Http\Controllers\CategoriesController::class, 'search']);
Route::apiResource('attendees', '\App\Http\Controllers\AttendeeController');
Route::get('attendees/search', '\App\Http\Controllers\AttendeeController@search');
Route::get('attendees/event/{eventName}', '\App\Http\Controllers\AttendeeController@getByEventName');
Route::put('attendees/code/{verifyCode}', '\App\Http\Controllers\AttendeeController@updateStatusByVerifyCode');
Route::get('/attendees/{id}', [\App\Http\Controllers\AttendeeController::class, 'show']);
Route::get('/ticket', [\App\Http\Controllers\AttendeeController::class, 'searchAttendeeByEmail']);
Route::get('/attendeescount', [\App\Http\Controllers\AttendeeController::class, 'countAttendeesByMonth']);
// Router cho bảng location
Route::get('/locations', [\App\Http\Controllers\Api\LocationController::class, 'index']);
Route::post('/locations', [\App\Http\Controllers\Api\LocationController::class, 'store']);
Route::put('/locations/{id}', [\App\Http\Controllers\Api\LocationController::class, 'update']);
Route::delete('/locations/{id}', [\App\Http\Controllers\Api\LocationController::class, 'destroy']);

// Router cho bảng faculty
Route::get('/faculties', [\App\Http\Controllers\Api\FacultyController::class, 'index']);
Route::post('/faculties', [\App\Http\Controllers\Api\FacultyController::class, 'store']);
Route::put('/faculties/{id}', [\App\Http\Controllers\Api\FacultyController::class, 'update']);
Route::delete('/faculties/{id}', [\App\Http\Controllers\Api\FacultyController::class, 'destroy']);

// Router cho bảng scale
Route::get('/scales', [\App\Http\Controllers\Api\ScaleController::class, 'index']);
Route::post('/scales', [\App\Http\Controllers\Api\ScaleController::class, 'store']);
Route::put('/scales/{id}', [\App\Http\Controllers\Api\ScaleController::class, 'update']);
Route::delete('/scales/{id}', [\App\Http\Controllers\Api\ScaleController::class, 'destroy']);
//Lay hinh anh events

Route::get('/images/{filename}', function ($filename) {
    $path = public_path('uploads/path/' . $filename);
    if (!File::exists($path)) {
    return response()->json(['message' => 'Image not found.'], 404);
}

$file = File::get($path);
$type = File::mimeType($path);

$response = Response::make($file, 200);
$response->header("Content-Type", $type);

return $response;
});

//Mail