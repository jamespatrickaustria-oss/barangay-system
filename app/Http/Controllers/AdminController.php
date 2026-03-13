<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OnlineId;
use App\Services\MailService;
use Illuminate\Http\Request;
use App\Http\Controllers\OfficialController;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function dashboard()
    {
        $totalOfficials = User::where('role', 'official')->count();

        $totalResidents = User::where('role', 'resident')->count();

        $pendingResidents = User::where('role', 'resident')
            ->where('status', 'pending')
            ->count();

        $recentOfficials = User::where('role', 'official')
            ->latest()
            ->take(5)
            ->get();

        $recentResidentIds = OnlineId::with('user')
            ->latest('issued_at')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('totalOfficials', 'totalResidents', 'pendingResidents', 'recentOfficials', 'recentResidentIds'));
    }

    /**
     * Display a listing of officials.
     */
    public function officials(Request $request)
    {
        $officials = User::where('role', 'official')
            ->latest()
            ->paginate(15);

        return view('admin.officials.index', compact('officials'));
    }

    /**
     * Display all registered users.
     */
    public function users(Request $request)
    {
        $filter = $request->get('filter', 'all');
        
        $query = User::query();
        
        // Apply status filter
        if ($filter !== 'all') {
            $query->where('status', $filter);
        }
        
        $users = $query->latest()->paginate(15);
        
        // Get stats for the dashboard cards
        $pendingCount = User::where('status', 'pending')->count();
        $approvedCount = User::where('status', 'approved')->count();
        $rejectedCount = User::where('status', 'rejected')->count();

        return view('admin.users.index', compact('users', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }

    /**
     * Display detailed information for one user.
     */
    public function showUser(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Approve a user account.
     */
    public function approveUser(Request $request, User $user)
    {
        $authUser = auth()->user();

        if (!in_array($authUser->role, ['admin', 'official'], true)) {
            abort(403, 'You are not allowed to approve users.');
        }

        if ($authUser->role === 'official' && $user->role !== 'resident') {
            abort(403, 'Barangay Officials can only approve Resident accounts.');
        }

        if ($authUser->role === 'admin' && !in_array($user->role, ['resident', 'official'], true)) {
            abort(403, 'Admin can only approve Resident or Barangay Official accounts.');
        }

        $finalRole = $user->role;

        if ($authUser->role === 'admin') {
            $validated = $request->validate([
                'assigned_role' => ['required', 'string', 'in:resident,official'],
            ]);

            $finalRole = $validated['assigned_role'];
        }

        $user->update([
            'status' => 'approved',
            'role' => $finalRole,
            'approved_by' => $authUser->id,
            'approved_at' => now(),
            'approver_role' => $authUser->role,
        ]);

        // When approving a resident, ensure account number, OnlineId, and approval email are set.
        if ($finalRole === 'resident') {
            $user->ensureResidentAccountNumber();

            if (!OnlineId::where('user_id', $user->id)->exists()) {
                OnlineId::create([
                    'user_id'   => $user->id,
                    'id_number' => OnlineId::generateIdNumber(),
                    'issued_at' => now(),
                ]);
            }

            MailService::send(
                $user->email,
                $user->getFullName(),
                'Your Account Has Been Approved',
                MailService::modernAccountApprovedEmail($user->getFullName(), url('/login'))
            );
        }

        return redirect()->back()->with('success', 'User approved successfully.');
    }

    /**
     * Reject a user account.
     */
    public function rejectUser(User $user)
    {
        $authUser = auth()->user();

        if (!in_array($authUser->role, ['admin', 'official'], true)) {
            abort(403, 'You are not allowed to reject users.');
        }

        if ($authUser->role === 'official' && $user->role !== 'resident') {
            abort(403, 'Barangay Officials can only reject Resident accounts.');
        }

        if ($authUser->role === 'admin' && !in_array($user->role, ['resident', 'official'], true)) {
            abort(403, 'Admin can only reject Resident or Barangay Official accounts.');
        }

        $user->update([
            'status' => 'rejected',
            'approved_by' => $authUser->id,
            'approved_at' => now(),
            'approver_role' => $authUser->role,
        ]);

        return redirect()->back()->with('success', 'User rejected successfully.');
    }

    /**
     * Show the form for creating a new official.
     */
    public function createOfficial()
    {
        return view('admin.officials.create');
    }

    /**
     * Store a newly created official in storage.
     */
    public function storeOfficial(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $plain = $request->password;

        $user = User::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'surname' => $validated['surname'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'role' => 'official',
            'status' => 'approved',
        ]);

        $html = '<div style="font-family:Segoe UI,sans-serif;padding:32px;background:#f0f9ff"><div style="background:linear-gradient(135deg,#1a6fcc,#3a8a3f);padding:32px;text-align:center;border-radius:12px 12px 0 0"><p style="color:white;font-size:22px;font-weight:700;margin:0">🏛️ Barangay Management System</p><p style="color:rgba(255,255,255,0.8);margin:8px 0 0">Official Account Created</p></div><div style="background:white;padding:40px;border-radius:0 0 12px 12px"><p>Dear <strong>' . $user->getFullName() . '</strong>,</p><p>A Barangay Official account has been created for you.</p><div style="background:#daf0fa;border-radius:8px;padding:20px;margin:20px 0"><p style="margin:0"><strong>Email:</strong> ' . $user->email . '</p><p style="margin:8px 0 0"><strong>Password:</strong> <code>' . $plain . '</code></p></div><p style="color:#e65100;font-size:13px">⚠️ Please change your password after first login.</p><a href="' . url('/login') . '" style="background:#3a8a3f;color:white;padding:14px 32px;border-radius:8px;text-decoration:none;font-weight:700;display:inline-block;margin-top:16px">Log In Now</a></div></div>';

        MailService::send(
            $user->email,
            $user->getFullName(),
            'Your Barangay Official Account Has Been Created',
            $html
        );

        return redirect('/admin/officials')->with('success', 'Official account created and credentials emailed.');
    }

    /**
     * Delete the specified official.
     */
    public function deleteOfficial($id)
    {
        $official = User::where('id', $id)
            ->where('role', 'official')
            ->firstOrFail();

        $official->delete();

        return redirect()->back()->with('success', 'Official removed successfully.');
    }

    /**
     * Display a listing of residents.
     */
    public function residents(Request $request)
    {
        return app(OfficialController::class)->residents($request);
    }

    /**
     * Show the form for creating a new resident.
     */
    public function createResident()
    {
        return app(OfficialController::class)->createResident();
    }

    /**
     * Store a newly created resident.
     */
    public function storeResident(Request $request)
    {
        return app(OfficialController::class)->storeResident($request);
    }

    /**
     * Display the specified resident.
     */
    public function showResident($id)
    {
        return app(OfficialController::class)->showResident($id);
    }

    /**
     * Show the form for editing a resident.
     */
    public function editResident($id)
    {
        return app(OfficialController::class)->editResident($id);
    }

    /**
     * Update the specified resident.
     */
    public function updateResident(Request $request, $id)
    {
        return app(OfficialController::class)->updateResident($request, $id);
    }

    /**
     * Delete the specified resident.
     */
    public function approveResident($id)
    {
        return app(OfficialController::class)->approveResident($id);
    }

    public function rejectResident($id)
    {
        return app(OfficialController::class)->rejectResident($id);
    }

    public function deleteResident($id)
    {
        return app(OfficialController::class)->deleteResident($id);
    }

    /**
     * View the resident online ID card.
     */
    public function viewResidentId($id)
    {
        return app(OfficialController::class)->viewResidentId($id);
    }

    /**
     * Display a listing of announcements.
     */
    public function announcements(Request $request)
    {
        return app(OfficialController::class)->announcements($request);
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function createAnnouncement()
    {
        return app(OfficialController::class)->createAnnouncement();
    }

    /**
     * Store a newly created announcement.
     */
    public function storeAnnouncement(Request $request)
    {
        return app(OfficialController::class)->storeAnnouncement($request);
    }

    /**
     * Show the form for editing an announcement.
     */
    public function editAnnouncement($id)
    {
        return app(OfficialController::class)->editAnnouncement($id);
    }

    /**
     * Update the specified announcement.
     */
    public function updateAnnouncement(Request $request, $id)
    {
        return app(OfficialController::class)->updateAnnouncement($request, $id);
    }

    /**
     * Delete the specified announcement.
     */
    public function deleteAnnouncement($id)
    {
        return app(OfficialController::class)->deleteAnnouncement($id);
    }

    /**
     * Toggle announcement status.
     */
    public function toggleAnnouncement($id)
    {
        return app(OfficialController::class)->toggleAnnouncement($id);
    }
}
