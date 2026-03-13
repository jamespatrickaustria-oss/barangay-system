<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\OnlineId;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResidentController extends Controller
{
    /**
     * Show the resident dashboard.
     */
    public function dashboard()
    {
        $unread = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->where('title', '!=', 'Account Approved')
            ->count();

        $user = auth()->user();

        // Get active announcements (not expired and is_active = true)
        $announcements = Announcement::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    // Expiration is selected via date input, so keep it visible for the full selected day.
                    ->orWhereDate('expires_at', '>=', today());
            })
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('resident.portal-dashboard', compact('user', 'unread', 'announcements'));
    }

    /**
     * Show the resident's profile.
     */
    public function profile()
    {
        return view('resident.profile', ['user' => auth()->user()]);
    }

    public function index(Request $request)
{
    $sortBy = $request->get('sort', 'id');
    $sortOrder = $request->get('order', 'asc');
    
    $users = User::orderBy($sortBy, $sortOrder)->paginate(15);
    return view('admin.users.index', compact('users'));
}


    /**
     * Update the resident's profile.
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'surname' => 'required|string|max:255',
            'phone' => 'nullable|string',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'house_no' => 'nullable|string|max:100',
            'barangay' => 'nullable|string|max:255',
            'municipality_city' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'marital_status' => 'nullable|in:single,married,divorced,widowed,separated',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = auth()->user();

        if ($request->hasFile('profile_photo')) {
            $existingPhotoPath = $user->getProfilePhotoStoragePath();

            if ($existingPhotoPath) {
                Storage::disk('public')->delete($existingPhotoPath);
            }

            $user->profile_photo = $request->file('profile_photo')->store('uploads/profile_photos', 'public');
        }

        $user->first_name = $validated['first_name'];
        $user->middle_name = $validated['middle_name'] ?? null;
        $user->surname = $validated['surname'];
        $user->phone = $request->phone;
        $user->father_name = $validated['father_name'] ?? null;
        $user->mother_name = $validated['mother_name'] ?? null;
        $user->house_no = $validated['house_no'] ?? null;
        $user->barangay = $validated['barangay'] ?? null;
        $user->municipality_city = $validated['municipality_city'] ?? null;
        $user->nationality = $validated['nationality'] ?? null;
        $user->address = $request->address;
        $user->birthdate = $request->birthdate;
        $user->gender = $request->gender;
        $user->marital_status = $request->marital_status;
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Show the resident's online ID.
     */
    public function onlineId()
    {
        $onlineId = OnlineId::where('user_id', auth()->id())
            ->with('user')
            ->first();

        if (!$onlineId) {
            return redirect()->back()->with('error', 'No Online ID found for your account.');
        }

        $onlineId->user->ensureResidentAccountNumber();

        return view('resident.online-id', compact('onlineId'));
    }

    /**
     * Display resident's notifications.
     */
    public function notifications(Request $request)
    {
        $announcements = Announcement::with('user')
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhereDate('expires_at', '>=', today());
            })
            ->latest('published_at')
            ->paginate(10);

        $notifications = Notification::where('user_id', auth()->id())
            ->where('title', '!=', 'Account Approved')
            ->latest()
            ->paginate(10);

        $unread = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->where('title', '!=', 'Account Approved')
            ->count();

        return view('resident.notifications', compact('notifications', 'unread', 'announcements'));
    }

    /**
     * Mark a notification as read.
     */
    public function markRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $notification->is_read = true;
        $notification->save();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->where('title', '!=', 'Account Approved')
            ->update(['is_read' => true]);

        return back()->with('success', 'All alerts marked as read.');
    }
}
