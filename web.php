<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicPagesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\YatraPlannerController;
use App\Http\Controllers\AssistanceController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AiAssistantController;
use App\Http\Controllers\LostFoundController;
use App\Http\Middleware\EnsureAdmin;

/*
|--------------------------------------------------------------------------
| Web Routes - YATRI SAHAYATA Smart Pilgrim Platform
| Developed by: Nidhi Chaudhary (0108CS241082), SATI Vidisha
|--------------------------------------------------------------------------
*/

// Public Informational & Directory Pages
Route::get('/', [PublicPagesController::class, 'home'])->name('home');
Route::get('/about', [PublicPagesController::class, 'about'])->name('about');
Route::get('/yatra-guide', [PublicPagesController::class, 'yatraGuide'])->name('yatra-guide');
Route::get('/routes', [PublicPagesController::class, 'routes'])->name('routes');
Route::get('/accommodation', [PublicPagesController::class, 'accommodation'])->name('accommodation');
Route::get('/medical', [PublicPagesController::class, 'medical'])->name('medical');
Route::get('/weather', [PublicPagesController::class, 'weather'])->name('weather');
Route::get('/announcements', [PublicPagesController::class, 'announcements'])->name('announcements');
Route::get('/faqs', [PublicPagesController::class, 'faqs'])->name('faqs');
Route::get('/contact', [PublicPagesController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicPagesController::class, 'submitContact'])->name('contact.submit');
Route::get('/privacy', [PublicPagesController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PublicPagesController::class, 'terms'])->name('terms');
Route::get('/accessibility', [PublicPagesController::class, 'accessibility'])->name('accessibility');
Route::get('/checklist', [PublicPagesController::class, 'checklist'])->name('checklist');
Route::get('/lost-found', [PublicPagesController::class, 'lostFound'])->name('lost-found');
Route::post('/lost-found', [LostFoundController::class, 'submitReport'])->name('lost-found.submit');

// Smart Search & Autocomplete
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/api/autocomplete', [SearchController::class, 'autocomplete'])->name('api.autocomplete');

// Emergency Assistance & Status Tracking
Route::post('/assistance/submit', [AssistanceController::class, 'submitRequest'])->name('assistance.submit');
Route::get('/track/{code?}', [AssistanceController::class, 'track'])->name('assistance.track');

// AI Yatri Assistant Endpoint
Route::post('/api/ai/chat', [AiAssistantController::class, 'chat'])->name('api.ai.chat');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
});

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Public or Authenticated Smart Planner
Route::get('/planner', [YatraPlannerController::class, 'index'])->name('planner.index');
Route::post('/planner/generate', [YatraPlannerController::class, 'plan'])->name('planner.generate');
Route::get('/planner/{id}', [YatraPlannerController::class, 'show'])->name('planner.show');

// User Protected Dashboard Routes
Route::middleware('auth')->prefix('dashboard')->name('user.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::post('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/saved-places', [DashboardController::class, 'savedPlaces'])->name('saved-places');
    Route::post('/saved-places/toggle', [DashboardController::class, 'toggleSavePlace'])->name('saved-places.toggle');
    Route::post('/planner/save', [YatraPlannerController::class, 'save'])->name('planner.save');
    Route::get('/requests', [DashboardController::class, 'requests'])->name('requests');
    Route::get('/notifications', [DashboardController::class, 'notifications'])->name('notifications');
    Route::post('/feedback', [DashboardController::class, 'submitFeedback'])->name('feedback.submit');
});

// Admin Protected Dashboard Routes
Route::middleware(['auth', EnsureAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{id}/toggle', [AdminController::class, 'toggleUserStatus'])->name('users.toggle');
    Route::post('/users/{id}/role', [AdminController::class, 'updateUserRole'])->name('users.role');
    Route::get('/requests', [AdminController::class, 'requests'])->name('requests');
    Route::post('/requests/{id}/update', [AdminController::class, 'updateRequestStatus'])->name('requests.update');
    Route::get('/medical', [AdminController::class, 'medical'])->name('medical');
    Route::post('/medical/store', [AdminController::class, 'storeMedical'])->name('medical.store');
    Route::get('/accommodations', [AdminController::class, 'accommodations'])->name('accommodations');
    Route::get('/announcements', [AdminController::class, 'announcements'])->name('announcements');
    Route::post('/announcements/store', [AdminController::class, 'storeAnnouncement'])->name('announcements.store');
    Route::get('/faqs', [AdminController::class, 'faqs'])->name('faqs');
    Route::get('/feedback', [AdminController::class, 'feedback'])->name('feedback');
    Route::get('/audit-logs', [AdminController::class, 'auditLogs'])->name('audit-logs');
    Route::get('/export/{type}', [AdminController::class, 'exportCsv'])->name('export');
});
