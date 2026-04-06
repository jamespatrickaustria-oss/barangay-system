<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use App\Models\OnlineId;
use App\Models\Announcement;
use App\Services\DashboardAnalyticsService;
use App\Services\ImageUploadService;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class OfficialController extends Controller
{
    public function __construct(private readonly ImageUploadService $imageUploadService)
    {
    }

    /**
     * Show the official dashboard.
     */
    public function dashboard(DashboardAnalyticsService $analytics)
    {
        $pending = User::where('role', 'resident')
            ->where('status', 'pending')
            ->count();

        $approved = User::where('role', 'resident')
            ->where('status', 'approved')
            ->count();

        $total = User::where('role', 'resident')->count();

        $notificationCount = Announcement::where('user_id', auth()->id())->count();

        $unread = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        $pendingResidents = User::where('role', 'resident')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentResidentIds = OnlineId::with('user')
            ->latest('issued_at')
            ->take(10)
            ->get();

        $chartData = $analytics->getOfficialChartData((int) auth()->id());

        return view('official.dashboard', compact('pending', 'approved', 'total', 'unread', 'notificationCount', 'pendingResidents', 'recentResidentIds', 'chartData'));
    }

    /**
     * Return live chart data for the official dashboard.
     */
    public function dashboardCharts(DashboardAnalyticsService $analytics)
    {
        return response()->json($analytics->getOfficialChartData((int) auth()->id()));
    }

    /**
     * Display a listing of residents.
     */
    public function residents(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = User::where('role', 'resident');

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%");
            });
        }

        $residents = $query->paginate(15)->appends($request->query());

        return view('official.residents.index', compact('residents', 'status', 'search'));
    }

    /**
     * Show the form for creating a new resident.
     */
    public function createResident()
    {
        return view('official.residents.create');
    }

    /**
     * Store a newly created resident in storage.
     */
    public function storeResident(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                \Illuminate\Validation\Rule::unique('users', 'email')->whereNull('deleted_at'),
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
            ],
            'phone' => 'required|string|max:20',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_relationship' => 'nullable|string|max:100',
            'emergency_contact_number' => 'nullable|string|max:20',
            'profile_photo' => $this->residentPhotoValidationRule(true),
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'house_no' => 'nullable|string|max:100',
            'barangay' => 'nullable|string|max:255',
            'municipality_city' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'marital_status' => 'nullable|in:single,married,divorced,widowed,separated',
        ]);

        $validated['phone'] = trim((string) ($validated['phone'] ?? ''));

        validator(
            ['phone' => $validated['phone']],
            [
                'phone' => [
                    'required',
                    'string',
                    'max:20',
                    \Illuminate\Validation\Rule::unique('users', 'phone')->whereNull('deleted_at'),
                ],
            ]
        )->validate();

        if (empty($validated['nationality'])) {
            $validated['nationality'] = 'Filipino';
        }

        $plainPassword = $request->password;

        $profilePhotoPath = null;

        if ($request->hasFile('profile_photo')) {
            $photoFile = $request->file('profile_photo');
            $profilePhotoPath = $this->imageUploadService->store($photoFile, 'uploads/profile_photos');
        }

        $accountNumber = User::generateAccountNumber(
            $validated['first_name'],
            $validated['middle_name'] ?? null,
            $validated['surname'],
            $validated['birthdate'] ?? null
        );

        $user = User::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'surname' => $validated['surname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
            'emergency_contact_relationship' => $validated['emergency_contact_relationship'] ?? null,
            'emergency_contact_number' => $validated['emergency_contact_number'] ?? null,
            'profile_photo' => $profilePhotoPath,
            'father_name' => $validated['father_name'] ?? null,
            'mother_name' => $validated['mother_name'] ?? null,
            'house_no' => $validated['house_no'] ?? null,
            'barangay' => $validated['barangay'] ?? null,
            'municipality_city' => $validated['municipality_city'] ?? null,
            'nationality' => $validated['nationality'],
            'address' => $validated['address'] ?? null,
            'birthdate' => $validated['birthdate'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'marital_status' => $validated['marital_status'] ?? null,
            'role' => 'resident',
            'status' => 'approved',
            'account_number' => $accountNumber,
        ]);

        $onlineId = OnlineId::create([
            'user_id' => $user->id,
            'id_number' => OnlineId::generateIdNumber(),
            'issued_at' => now(),
        ]);

        if (empty($user->resident_id_number)) {
            $user->resident_id_number = $onlineId->id_number;
            $user->save();
        }

        Notification::create([
            'user_id' => $user->id,
            'sender_id' => auth()->id(),
            'title' => 'Welcome to Barangay System',
            'message' => 'Your account has been created by a Barangay Official. You can now log in.',
        ]);

        MailService::send(
            $user->email,
            $user->getFullName(),
            'Your Barangay Account Has Been Created',
            MailService::accountCreatedByOfficialEmail($user->getFullName(), $user->email, $plainPassword, url('/login'))
        );

        return redirect('/official/residents')->with('success', 'Resident registered and approved successfully.');
    }

    /**
     * Show the form for editing the specified resident.
     */
    public function editResident($id)
    {
        $resident = User::where('id', $id)
            ->where('role', 'resident')
            ->firstOrFail();

        return view('official.residents.edit', compact('resident'));
    }

    /**
     * Update the specified resident in storage.
     */
    public function updateResident(Request $request, $id)
    {
        $resident = User::where('id', $id)
            ->where('role', 'resident')
            ->firstOrFail();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'surname' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_relationship' => 'nullable|string|max:100',
            'emergency_contact_number' => 'nullable|string|max:20',
            'profile_photo' => $this->residentPhotoValidationRule(false),
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'house_no' => 'nullable|string|max:100',
            'barangay' => 'nullable|string|max:255',
            'municipality_city' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'marital_status' => 'nullable|in:single,married,divorced,widowed,separated',
        ]);

        if (array_key_exists('phone', $validated) && $validated['phone'] !== null && $validated['phone'] !== '') {
            $validated['phone'] = trim((string) $validated['phone']);

            validator(
                ['phone' => $validated['phone']],
                [
                    'phone' => [
                        'nullable',
                        'string',
                        'max:20',
                        \Illuminate\Validation\Rule::unique('users', 'phone')
                            ->ignore($resident->id)
                            ->whereNull('deleted_at'),
                    ],
                ]
            )->validate();
        }

        if (empty($validated['nationality'])) {
            $validated['nationality'] = 'Filipino';
        }

        if ($request->hasFile('profile_photo')) {
            $existingPhotoPath = $resident->getProfilePhotoStoragePath();

            if ($existingPhotoPath) {
                $this->imageUploadService->delete($existingPhotoPath);
            }

            $validated['profile_photo'] = $this->imageUploadService->store($request->file('profile_photo'), 'uploads/profile_photos');
        }

        $resident->update($validated);

        return redirect()->back()->with('success', 'Resident profile updated.');
    }

    /**
     * Show the dedicated resident photo management interface.
     */
    public function editResidentPhoto($id)
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'official'], true), 403);

        $resident = User::where('id', $id)
            ->where('role', 'resident')
            ->firstOrFail();

        return view('official.residents.photo', compact('resident'));
    }

    /**
     * Upload or replace the resident photo.
     */
    public function updateResidentPhoto(Request $request, $id)
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'official'], true), 403);

        $resident = User::where('id', $id)
            ->where('role', 'resident')
            ->firstOrFail();

        $validated = $request->validate([
            'profile_photo' => $this->residentPhotoValidationRule(true),
        ]);

        try {
            DB::transaction(function () use ($resident, $validated): void {
                $existingPhotoPath = $resident->getProfilePhotoStoragePath();

                if ($existingPhotoPath) {
                    $this->imageUploadService->delete($existingPhotoPath);
                }

                $resident->profile_photo = $this->imageUploadService->store($validated['profile_photo'], 'uploads/profile_photos');
                $resident->save();
            });
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Unable to upload resident photo right now. Please try again.');
        }

        return redirect()->back()->with('success', 'Resident photo uploaded successfully.');
    }

    /**
     * Validation rule used across resident photo create/update flows.
     */
    private function residentPhotoValidationRule(bool $required): string
    {
        $baseRule = 'file|image|mimes:jpg,jpeg,png|max:5120';

        return $required ? 'required|' . $baseRule : 'nullable|' . $baseRule;
    }

    /**
     * Approve the specified resident.
     */
    public function approveResident($id)
    {
        $resident = User::where('id', $id)
            ->where('role', 'resident')
            ->firstOrFail();

        $resident->status = 'approved';
        $resident->save();

        if (!OnlineId::where('user_id', $resident->id)->exists()) {
            $onlineId = OnlineId::create([
                'user_id' => $resident->id,
                'id_number' => OnlineId::generateIdNumber(),
                'issued_at' => now(),
            ]);

            if (empty($resident->resident_id_number)) {
                $resident->resident_id_number = $onlineId->id_number;
                $resident->save();
            }
        } else {
            $onlineId = OnlineId::where('user_id', $resident->id)->first();

            if ($onlineId && empty($resident->resident_id_number)) {
                $resident->resident_id_number = $onlineId->id_number;
                $resident->save();
            }
        }

        $emailSent = MailService::send(
            $resident->email,
            $resident->getFullName(),
            'Your Account Has Been Approved',
            MailService::modernAccountApprovedEmail($resident->getFullName(), url('/login'))
        );

        if (!$emailSent) {
            logger()->error('Failed to send resident approval email.', [
                'resident_user_id' => $resident->id,
                'approved_by_user_id' => optional(auth()->user())->id,
            ]);
        }

        return redirect()->back()->with('success', 'Resident approved successfully.');
    }

    /**
     * Reject the specified resident.
     */
    public function rejectResident($id)
    {
        $resident = User::where('id', $id)
            ->where('role', 'resident')
            ->firstOrFail();

        $resident->status = 'rejected';
        $resident->save();

        MailService::send(
            $resident->email,
            $resident->getFullName(),
            'Registration Status Update',
            MailService::accountRejectedEmail($resident->getFullName())
        );

        return redirect()->back()->with('success', 'Resident registration rejected.');
    }

    /**
     * Delete the specified resident.
     */
    public function deleteResident($id)
    {
        $resident = User::where('id', $id)
            ->where('role', 'resident')
            ->firstOrFail();

        // Delete related records
        OnlineId::where('user_id', $resident->id)->delete();
        Notification::where('user_id', $resident->id)->delete();
        
        $resident->delete();

        return redirect()->back()->with('success', 'Resident deleted successfully.');
    }

    /**
     * View a resident's online ID card and auto-generate it if missing.
     */
    public function viewResidentId($id)
    {
        $resident = User::where('id', $id)
            ->where('role', 'resident')
            ->firstOrFail();

        $resident->ensureResidentAccountNumber();

        $onlineId = OnlineId::firstOrCreate(
            ['user_id' => $resident->id],
            [
                'id_number' => OnlineId::generateIdNumber(),
                'issued_at' => now(),
            ]
        );

        if (empty($resident->resident_id_number)) {
            $resident->resident_id_number = $onlineId->id_number;
            $resident->save();
        }

        if (is_null($onlineId->issued_at)) {
            $onlineId->issued_at = now();
            $onlineId->save();
        }

        return view('official.residents.view-id', [
            'resident' => $resident,
            'onlineId' => $onlineId,
        ]);
    }

    /**
     * Show the official's profile.
     */
    public function profile()
    {
        return view('official.profile', ['user' => auth()->user()]);
    }

    /**
     * Update the official's profile.
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'surname' => 'required|string|max:255',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'marital_status' => 'nullable|in:single,married,divorced,widowed,separated',
            'profile_photo' => 'nullable|file|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $user = auth()->user();

        if (array_key_exists('phone', $validated) && $validated['phone'] !== null && $validated['phone'] !== '') {
            $validated['phone'] = trim((string) $validated['phone']);

            validator(
                ['phone' => $validated['phone']],
                [
                    'phone' => [
                        'nullable',
                        'string',
                        'max:20',
                        \Illuminate\Validation\Rule::unique('users', 'phone')
                            ->ignore($user->id)
                            ->whereNull('deleted_at'),
                    ],
                ]
            )->validate();
        }

        $profilePhotoPath = $user->profile_photo;

        if ($request->hasFile('profile_photo')) {
            $existingPhotoPath = $user->getProfilePhotoStoragePath();

            if ($existingPhotoPath) {
                $this->imageUploadService->delete($existingPhotoPath);
            }

            $profilePhotoPath = $this->imageUploadService->store($request->file('profile_photo'), 'uploads/profile_photos');
        }

        $user->update([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'surname' => $validated['surname'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'marital_status' => $validated['marital_status'] ?? null,
            'profile_photo' => $profilePhotoPath,
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Show the form for creating a new notification.
     */
    public function createNotification()
    {
        $residents = User::where('role', 'resident')
            ->where('status', 'approved')
            ->get();

        return view('official.notifications.create', compact('residents'));
    }

    /**
     * Send a notification to residents.
     */
    public function sendNotification(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'recipient_type' => 'required|string|in:all,specific',
            'resident_ids' => 'array|required_if:recipient_type,specific',
        ]);

        $recipients = $validated['recipient_type'] === 'all'
            ? User::where('role', 'resident')->where('status', 'approved')->get()
            : User::whereIn('id', $validated['resident_ids'] ?? [])->get();

        foreach ($recipients as $recipient) {
            Notification::create([
                'user_id' => $recipient->id,
                'sender_id' => auth()->id(),
                'title' => $validated['title'],
                'message' => $validated['message'],
            ]);

            MailService::send(
                $recipient->email,
                $recipient->getFullName(),
                $validated['title'],
                MailService::newNotificationEmail($recipient->getFullName(), $validated['title'], $validated['message'])
            );
        }

        return redirect('/official/dashboard')->with('success', 'Notification sent to ' . $recipients->count() . ' resident(s).');
    }

    /**
     * Display a listing of announcements.
     */
    public function announcements(Request $request)
    {
        $announcements = Announcement::with('user')
            ->latest()
            ->paginate(15);

        return view('official.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function createAnnouncement()
    {
        return view('official.announcements.create');
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function storeAnnouncement(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'expires_at' => 'nullable|date|after_or_equal:today',
        ]);

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $this->imageUploadService->store($request->file('photo'), 'uploads/announcement_photos');
        }

        $announcement = Announcement::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'photo_path' => $photoPath,
            'published_at' => now(),
            'expires_at' => !empty($validated['expires_at'])
                ? Carbon::parse($validated['expires_at'])->endOfDay()
                : null,
            'is_active' => true,
        ]);

        return redirect('/official/announcements')->with('success', 'Announcement published successfully.');
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function editAnnouncement($id)
    {
        $announcement = Announcement::findOrFail($id);

        return view('official.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement in storage.
     */
    public function updateAnnouncement(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'remove_photo' => 'nullable|boolean',
            'expires_at' => 'nullable|date|after_or_equal:today',
        ]);

        $photoPath = $announcement->photo_path;

        if (!empty($validated['remove_photo']) && $photoPath) {
            $this->imageUploadService->delete($photoPath);
            $photoPath = null;
        }

        if ($request->hasFile('photo')) {
            if ($photoPath) {
                $this->imageUploadService->delete($photoPath);
            }

            $photoPath = $this->imageUploadService->store($request->file('photo'), 'uploads/announcement_photos');
        }

        $announcement->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'photo_path' => $photoPath,
            'expires_at' => !empty($validated['expires_at'])
                ? Carbon::parse($validated['expires_at'])->endOfDay()
                : null,
        ]);

        return redirect('/official/announcements')->with('success', 'Announcement updated successfully.');
    }

    /**
     * Toggle announcement active status.
     */
    public function toggleAnnouncement($id)
    {
        $announcement = Announcement::findOrFail($id);

        $announcement->is_active = !$announcement->is_active;
        $announcement->save();

        $status = $announcement->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Announcement {$status} successfully.");
    }

    /**
     * Delete the specified announcement.
     */
    public function deleteAnnouncement($id)
    {
        $announcement = Announcement::findOrFail($id);

        if (!empty($announcement->photo_path)) {
            $this->imageUploadService->delete($announcement->photo_path);
        }

        $announcement->delete();

        return redirect()->back()->with('success', 'Announcement deleted successfully.');
    }
}
