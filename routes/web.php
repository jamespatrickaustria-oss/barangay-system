<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilePhotoController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\OfficialController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CarouselSettingsController;
use App\Http\Controllers\UserActivityLogController;
use App\Http\Controllers\Auth\ForgotPasswordOtpController;
use App\Models\CarouselSlide;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        }

        if ($user->role === 'official') {
            return redirect('/official/dashboard');
        }

        if ($user->role === 'resident') {
            return redirect('/resident/dashboard');
        }
    }

    $fallbackSlides = collect(range(1, 7))->map(function (int $slot): array {
        return [
            'slot' => $slot,
            'image_url' => asset('images/carousel/slide' . $slot . '.svg'),
            'title' => null,
            'description' => null,
        ];
    });

    $slides = $fallbackSlides;

    if (Schema::hasTable('carousel_slides')) {
        $dbSlides = CarouselSlide::query()
            ->orderBy('slot')
            ->get()
            ->keyBy('slot');

        if ($dbSlides->isNotEmpty()) {
            $slides = collect(range(1, 7))->map(function (int $slot) use ($dbSlides, $fallbackSlides): array {
                $fallbackSlide = $fallbackSlides->firstWhere('slot', $slot);
                $slide = $dbSlides->get($slot);

                if (!$slide) {
                    return $fallbackSlide;
                }

                $imageUrl = $fallbackSlide['image_url'];
                $imagePath = trim(str_replace('\\', '/', (string) $slide->image_path), '/');

                if (empty($imagePath)) {
                    $firstImage = $slide->images()->orderBy('sort_order')->first();
                    $imagePath = trim(str_replace('\\', '/', (string) $firstImage?->image_path), '/');
                }

                if (!empty($imagePath)) {
                    $imagePath = preg_replace('#^storage/#', '', $imagePath);
                    $imagePath = trim($imagePath, '/');

                    if (Storage::disk('public')->exists($imagePath)) {
                        $imageUrl = asset('storage/' . $imagePath);
                    }
                }

                return [
                    'slot' => $slot,
                    'image_url' => $imageUrl,
                    'title' => $slide->title,
                    'description' => $slide->description,
                    'enabled' => (bool) ($slide->enabled ?? true),
                    'link_url' => $slide->link_url,
                    'open_in_new_tab' => (bool) ($slide->open_in_new_tab ?? false),
                ];
            });
        }
    }

    $carouselSettings = null;
    if (Schema::hasTable('carousel_settings')) {
        $carouselSettings = DB::table('carousel_settings')->orderBy('id')->limit(1)->first();
    }

    return view('homepage', [
        'slides' => $slides,
        'carouselSettings' => $carouselSettings,
    ]);
})->middleware('prevent-back-history')->name('homepage');

Route::middleware(['guest.redirect', 'prevent-back-history'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/verify-official-email', [AuthController::class, 'verifyOfficialEmail'])->name('verify-official-email');
});

Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [ForgotPasswordOtpController::class, 'showForgotForm'])->name('password.forgot');
    Route::post('/forgot-password', [ForgotPasswordOtpController::class, 'sendOtp'])->name('password.otp.send');
    Route::get('/verify-otp', [ForgotPasswordOtpController::class, 'showVerifyForm'])->name('password.otp.verify');
    Route::post('/verify-otp', [ForgotPasswordOtpController::class, 'verifyOtp'])->name('password.otp.check');
    Route::get('/reset-password', [ForgotPasswordOtpController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordOtpController::class, 'resetPassword'])->name('password.update');
});

Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile-photos/{user}', [ProfilePhotoController::class, 'show'])->name('profile-photos.show');
});

Route::get('/pending', function () {
    return view('pending');
})->name('pending');

Route::get('/unauthorized', function () {
    return view('unauthorized');
});



/*
|--------------------------------------------------------------------------
| Captive Portal
|--------------------------------------------------------------------------
*/
//From onegentri footer
Route::get('/portal', function () {
    return view('captive.homepage');
})->name('captive.homepage');

//From submit button in captive portal homepage
Route::post('/portal', function () {
    return view('captive.homepage');
})->name('captive.homepage');


/*
|--------------------------------------------------------------------------
| Verify the contact number
|--------------------------------------------------------------------------
*/



// Page (opened from footer link)
Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.page');

// Actions
Route::post('/contacts/add', [ContactController::class, 'add'])->name('contacts.add');
Route::post('/contacts/verify', [ContactController::class, 'verify'])->name('contacts.verify');

/*
|--------------------------------------------------------------------------
| Resident Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'resident', 'prevent-back-history'])->prefix('resident')->group(function () {
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
    Route::post('/chat/threads/{thread}/mark-read', [ChatController::class, 'markMessagesAsRead'])->name('resident.chat.mark-read');
    Route::get('/chat/unread-count', [ChatController::class, 'residentUnreadCount'])->name('resident.chat.unread-count');
});

/*
|--------------------------------------------------------------------------
| Official Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'official', 'prevent-back-history'])->prefix('official')->group(function () {
    Route::get('/dashboard', [OfficialController::class, 'dashboard'])->name('official.dashboard');
    Route::get('/dashboard/charts', [OfficialController::class, 'dashboardCharts'])->name('official.dashboard.charts');

    // Carousel Settings CRUD
    Route::prefix('/settings/carousel')->group(function () {
        Route::get('/', [CarouselSettingsController::class, 'index'])->name('official.carousel-settings.index');
        Route::get('/create', [CarouselSettingsController::class, 'create'])->name('official.carousel-settings.create');
        Route::post('/', [CarouselSettingsController::class, 'store'])->name('official.carousel-settings.store');
        Route::put('/settings', [CarouselSettingsController::class, 'updateSettings'])->name('official.carousel-settings.update-settings');
        Route::put('/batch', [CarouselSettingsController::class, 'updateCarousel'])->name('official.carousel-settings.batch-update');
        Route::post('/images/reorder', [CarouselSettingsController::class, 'reorderImages'])->name('official.carousel-settings.images.reorder');
        Route::delete('/images/{carouselImage}', [CarouselSettingsController::class, 'deleteImage'])->name('official.carousel-settings.images.delete');
        Route::get('/{carouselSlide}/edit', [CarouselSettingsController::class, 'edit'])->name('official.carousel-settings.edit');
        Route::put('/{carouselSlide}', [CarouselSettingsController::class, 'update'])->name('official.carousel-settings.update');
        Route::delete('/{carouselSlide}', [CarouselSettingsController::class, 'destroy'])->name('official.carousel-settings.destroy');
    });
    
    // Resident management
    Route::get('/residents', [OfficialController::class, 'residents'])->name('official.residents.index');
    Route::get('/residents/create', [OfficialController::class, 'createResident'])->name('official.residents.create');
    Route::post('/residents', [OfficialController::class, 'storeResident'])->name('official.residents.store');
    Route::get('/residents/{id}/edit', [OfficialController::class, 'editResident'])->name('official.residents.edit');
    Route::put('/residents/{id}', [OfficialController::class, 'updateResident'])->name('official.residents.update');
    Route::get('/residents/{id}/photo', [OfficialController::class, 'editResidentPhoto'])->name('official.residents.photo.edit');
    Route::put('/residents/{id}/photo', [OfficialController::class, 'updateResidentPhoto'])->name('official.residents.photo.update');
    Route::post('/residents/{id}/approve', [OfficialController::class, 'approveResident'])->name('official.residents.approve');
    Route::post('/residents/{id}/reject', [OfficialController::class, 'rejectResident'])->name('official.residents.reject');
    Route::get('/residents/{id}/view-id', [OfficialController::class, 'viewResidentId'])->name('official.residents.view-id');
    Route::delete('/residents/{id}', [OfficialController::class, 'deleteResident'])->name('official.residents.destroy');
    
    // Profile
    Route::get('/profile', [OfficialController::class, 'profile'])->name('official.profile');
    Route::put('/profile', [OfficialController::class, 'updateProfile'])->name('official.profile.update');

    // Chat
    Route::get('/chat', [ChatController::class, 'officialIndex'])->name('official.chat.index');
    Route::get('/chat/residents', [ChatController::class, 'officialResidents'])->name('official.chat.residents');
    Route::get('/chat/residents/{resident}/thread', [ChatController::class, 'officialResidentThread'])->name('official.chat.resident-thread');
    Route::get('/chat/threads', [ChatController::class, 'officialThreads'])->name('official.chat.threads');
    Route::get('/chat/threads/{thread}/messages', [ChatController::class, 'officialMessages'])->name('official.chat.messages');
    Route::post('/chat/threads/{thread}/messages', [ChatController::class, 'officialSend'])->name('official.chat.send');
    Route::post('/chat/threads/{thread}/mark-read', [ChatController::class, 'markMessagesAsRead'])->name('official.chat.mark-read');
    Route::get('/chat/unread-count', [ChatController::class, 'officialUnreadCount'])->name('official.chat.unread-count');
    
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

Route::middleware(['auth', 'admin', 'prevent-back-history'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/dashboard/charts', [AdminController::class, 'dashboardCharts'])->name('admin.dashboard.charts');
    Route::get('/activity-logs', [UserActivityLogController::class, 'index'])->name('admin.activity-logs');

    // Carousel Settings CRUD
    Route::prefix('/settings/carousel')->group(function () {
        Route::get('/', [CarouselSettingsController::class, 'index'])->name('admin.carousel-settings.index');
        Route::get('/create', [CarouselSettingsController::class, 'create'])->name('admin.carousel-settings.create');
        Route::post('/', [CarouselSettingsController::class, 'store'])->name('admin.carousel-settings.store');
        Route::put('/settings', [CarouselSettingsController::class, 'updateSettings'])->name('admin.carousel-settings.update-settings');
        Route::put('/batch', [CarouselSettingsController::class, 'updateCarousel'])->name('admin.carousel-settings.batch-update');
        Route::post('/images/reorder', [CarouselSettingsController::class, 'reorderImages'])->name('admin.carousel-settings.images.reorder');
        Route::delete('/images/{carouselImage}', [CarouselSettingsController::class, 'deleteImage'])->name('admin.carousel-settings.images.delete');
        Route::get('/{carouselSlide}/edit', [CarouselSettingsController::class, 'edit'])->name('admin.carousel-settings.edit');
        Route::put('/{carouselSlide}', [CarouselSettingsController::class, 'update'])->name('admin.carousel-settings.update');
        Route::delete('/{carouselSlide}', [CarouselSettingsController::class, 'destroy'])->name('admin.carousel-settings.destroy');
    });
    
    // User Requests
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
    Route::get('/residents/{id}/photo', [AdminController::class, 'editResidentPhoto'])->name('admin.residents.photo.edit');
    Route::put('/residents/{id}/photo', [AdminController::class, 'updateResidentPhoto'])->name('admin.residents.photo.update');
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

