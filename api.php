<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AssistanceController;
use App\Http\Controllers\AiAssistantController;
use App\Http\Controllers\YatraPlannerController;
use App\Models\Facility;
use App\Models\MedicalCentre;
use App\Models\Accommodation;
use App\Models\Announcement;

/*
|--------------------------------------------------------------------------
| API Routes - YATRI SAHAYATA REST Services
|--------------------------------------------------------------------------
*/

Route::get('/v1/circuits', function () {
    return response()->json([
        'success' => true,
        'circuits' => ['Vaishno Devi', 'Ujjain Mahakal', 'Char Dham', 'Omkareshwar', 'Varanasi Kashi', 'Amarnath', 'Somnath']
    ]);
});

Route::get('/v1/facilities', function () {
    return response()->json([
        'success' => true,
        'data' => Facility::where('is_verified', true)->get()
    ]);
});

Route::get('/v1/medical', function () {
    return response()->json([
        'success' => true,
        'data' => MedicalCentre::all()
    ]);
});

Route::get('/v1/accommodations', function () {
    return response()->json([
        'success' => true,
        'data' => Accommodation::where('is_verified', true)->get()
    ]);
});

Route::get('/v1/announcements', function () {
    return response()->json([
        'success' => true,
        'data' => Announcement::where('is_active', true)->orderBy('published_at', 'desc')->get()
    ]);
});

Route::post('/v1/sos/submit', [AssistanceController::class, 'submitRequest']);
Route::get('/v1/requests/track/{code}', [AssistanceController::class, 'track']);
Route::post('/v1/ai/chat', [AiAssistantController::class, 'chat']);
Route::post('/v1/planner/generate', [YatraPlannerController::class, 'plan']);
Route::get('/v1/autocomplete', [SearchController::class, 'autocomplete']);
