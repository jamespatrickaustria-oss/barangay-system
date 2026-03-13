<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilePhotoController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\OfficialController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('homepage');
})->name('homepage');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/verify-official-email', [AuthController::class, 'verifyOfficialEmail'])->name('verify-official-email');
Route::middleware('auth')->get('/profile-photos/{user}', [ProfilePhotoController::class, 'show'])->name('profile-photos.show');

Route::get('/pending', function () {
    return view('pending');
})->name('pending');

Route::get('/unauthorized', function () {
    return view('unauthorized');
});

/*
|--------------------------------------------------------------------------
| Resident Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'resident'])->prefix('resident')->group(function () {
    Route::get('/dashboard', [ResidentController::class, 'dashboard'])->name('resident.dashboard');
    Route::get('/profile', [ResidentController::class, 'profile'])->name('resident.profile');
    Route::put('/profile', [ResidentController::class, 'updateProfile'])->name('resident.profile.update');
    Route::get('/online-id', [ResidentController::class, 'onlineId'])->name('resident.online-id');
    Route::get('/notifications', [ResidentController::class, 'notifications'])->name('resident.notifications');
    Route::post('/notifications/{id}/read', [ResidentController::class, 'markRead'])->name('resident.notifications.read');
    Route::post('/notifications/read-all', [ResidentController::class, 'markAllRead'])->name('resident.notifications.read-all');

    Route::get('/chat/thread', [ChatController::class, 'residentThread'])->name('resident.chat.thread');
    Route::get('/chat/messages', [ChatController::class, 'residentMessages'])->name('resident.chat.messages');
    Route::post('/chat/messages', [ChatController::class, 'residentSend'])->name('resident.chat.send');
});

/*
|--------------------------------------------------------------------------
| Official Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'official'])->prefix('official')->group(function () {
    Route::get('/dashboard', [OfficialController::class, 'dashboard'])->name('official.dashboard');
    
    // Resident management
    Route::get('/residents', [OfficialController::class, 'residents'])->name('official.residents.index');
    Route::get('/residents/create', [OfficialController::class, 'createResident'])->name('official.residents.create');
    Route::post('/residents', [OfficialController::class, 'storeResident'])->name('official.residents.store');
    Route::get('/residents/{id}/edit', [OfficialController::class, 'editResident'])->name('official.residents.edit');
    Route::put('/residents/{id}', [OfficialController::class, 'updateResident'])->name('official.residents.update');
    Route::post('/residents/{id}/approve', [OfficialController::class, 'approveResident'])->name('official.residents.approve');
    Route::post('/residents/{id}/reject', [OfficialController::class, 'rejectResident'])->name('official.residents.reject');
    Route::get('/residents/{id}/view-id', [OfficialController::class, 'viewResidentId'])->name('official.residents.view-id');
    Route::delete('/residents/{id}', [OfficialController::class, 'deleteResident'])->name('official.residents.destroy');
    
    // Profile
    Route::get('/profile', [OfficialController::class, 'profile'])->name('official.profile');
    Route::put('/profile', [OfficialController::class, 'updateProfile'])->name('official.profile.update');

    // Chat
    Route::get('/chat', [ChatController::class, 'officialIndex'])->name('official.chat.index');
    Route::get('/chat/threads', [ChatController::class, 'officialThreads'])->name('official.chat.threads');
    Route::get('/chat/threads/{thread}/messages', [ChatController::class, 'officialMessages'])->name('official.chat.messages');
    Route::post('/chat/threads/{thread}/messages', [ChatController::class, 'officialSend'])->name('official.chat.send');
    
    // Notifications
    Route::get('/notifications/create', [OfficialController::class, 'createNotification'])->name('official.notifications.create');
    Route::post('/notifications', [OfficialController::class, 'sendNotification'])->name('official.notifications.store');
    
    // Announcements
    Route::get('/announcements', [OfficialController::class, 'announcements'])->name('official.announcements.index');
    Route::get('/announcements/create', [OfficialController::class, 'createAnnouncement'])->name('official.announcements.create');
    Route::post('/announcements', [OfficialController::class, 'storeAnnouncement'])->name('official.announcements.store');
    Route::get('/announcements/{id}/edit', [OfficialController::class, 'editAnnouncement'])->name('official.announcements.edit');
    Route::put('/announcements/{id}', [OfficialController::class, 'updateAnnouncement'])->name('official.announcements.update');
    Route::delete('/announcements/{id}', [OfficialController::class, 'deleteAnnouncement'])->name('official.announcements.destroy');
    Route::post('/announcements/{id}/toggle', [OfficialController::class, 'toggleAnnouncement'])->name('official.announcements.toggle');

    // User status actions (officials can approve/reject)
    Route::post('/users/{user}/approve', [AdminController::class, 'approveUser'])->name('official.users.approve');
    Route::post('/users/{user}/reject', [AdminController::class, 'rejectUser'])->name('official.users.reject');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // User approvals
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users.index');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('admin.users.show');
    Route::post('/users/{user}/approve', [AdminController::class, 'approveUser'])->name('admin.users.approve');
    Route::post('/users/{user}/reject', [AdminController::class, 'rejectUser'])->name('admin.users.reject');
    
    // Residents management
    Route::get('/residents', [AdminController::class, 'residents'])->name('admin.residents.index');
    Route::get('/residents/create', [AdminController::class, 'createResident'])->name('admin.residents.create');
    Route::post('/residents', [AdminController::class, 'storeResident'])->name('admin.residents.store');
    Route::get('/residents/{id}', [AdminController::class, 'showResident'])->name('admin.residents.show');
    Route::get('/residents/{id}/edit', [AdminController::class, 'editResident'])->name('admin.residents.edit');
    Route::put('/residents/{id}', [AdminController::class, 'updateResident'])->name('admin.residents.update');
    Route::post('/residents/{id}/approve', [AdminController::class, 'approveResident'])->name('admin.residents.approve');
    Route::post('/residents/{id}/reject', [AdminController::class, 'rejectResident'])->name('admin.residents.reject');
    Route::get('/residents/{id}/view-id', [AdminController::class, 'viewResidentId'])->name('admin.residents.view-id');
    Route::delete('/residents/{id}', [AdminController::class, 'deleteResident'])->name('admin.residents.destroy');
    
    // Announcements management
    Route::get('/announcements', [AdminController::class, 'announcements'])->name('admin.announcements.index');
    Route::get('/announcements/create', [AdminController::class, 'createAnnouncement'])->name('admin.announcements.create');
    Route::post('/announcements', [AdminController::class, 'storeAnnouncement'])->name('admin.announcements.store');
    Route::get('/announcements/{id}/edit', [AdminController::class, 'editAnnouncement'])->name('admin.announcements.edit');
    Route::put('/announcements/{id}', [AdminController::class, 'updateAnnouncement'])->name('admin.announcements.update');
    Route::delete('/announcements/{id}', [AdminController::class, 'deleteAnnouncement'])->name('admin.announcements.destroy');
    Route::post('/announcements/{id}/toggle', [AdminController::class, 'toggleAnnouncement'])->name('admin.announcements.toggle');
    
    // Officials management
    Route::get('/officials', [AdminController::class, 'officials'])->name('admin.officials.index');
    Route::get('/officials/create', [AdminController::class, 'createOfficial'])->name('admin.officials.create');
    Route::post('/officials', [AdminController::class, 'storeOfficial'])->name('admin.officials.store');
    Route::delete('/officials/{id}', [AdminController::class, 'deleteOfficial'])->name('admin.officials.destroy');
});

