<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Notification;
use App\Services\MailService;

class AuthController extends Controller
{
    /**
     * Resolve redirect destination based on user role and status.
     */
    private function redirectByRole(User $user)
    {
        if ($user->status === 'pending') {
            Auth::logout();
            return redirect('/login')->with('error', 'Your account is still pending approval.');
        }

        if ($user->status === 'rejected') {
            Auth::logout();
            return redirect('/login')->with('error', 'Your account was rejected. Please contact the barangay office.');
        }

        // Admin can proceed as long as status is approved.
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        }

        // Check official verification status (only if account has email_verification_token)
        if ($user->role === 'official' && $user->status !== 'approved') {
            Auth::logout();
            return redirect('/login')->with('error', 'Your account is not yet verified. Please check your email for the verification link.');
        }

        // Only check email verification for officials who have a verification token (newly created ones)
        if ($user->role === 'official' && $user->email_verification_token && !$user->email_verified_at) {
            Auth::logout();
            return redirect('/login')->with('error', 'Your email is not verified. Please check your email for the verification link.');
        }

        if ($user->role === 'official') {
            return redirect('/official/dashboard');
        }

        if ($user->role === 'resident' && $user->status === 'approved') {
            return redirect('/resident/dashboard');
        }

        Auth::logout();
        return redirect('/login')->with('error', 'Unable to determine your access level.');
    }

    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $redirect = $this->redirectByRole($user);

            if (
                $user->status === 'approved' &&
                in_array($user->role, ['admin', 'official', 'resident'], true)
            ) {
                return $redirect->with('success', 'You are already logged in.');
            }

            return $redirect;
        }

        return view('auth.login');
    }

    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->email;
        $password = $request->password;

        // Check if user exists
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('error', 'User account not found with this email.');
        }

        // Check if user account is pending approval
        if ($user->status === 'pending') {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('error', 'Your account is still pending approval. Please wait for authorization from an administrator.');
        }

        // Check if user account is rejected
        if ($user->status === 'rejected') {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('error', 'Your account has been rejected. Please contact the barangay office for more information.');
        }

        // Check password
        if (!Hash::check($password, $user->password)) {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('error', 'The password you entered is incorrect.');
        }

        // Attempt authentication and respect the remember-me checkbox.
        if (!Auth::attempt(['email' => $email, 'password' => $password], $request->boolean('remember'))) {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('error', 'Authentication failed. Please try again.');
        }

        // Regenerate session ID after login to persist auth state securely.
        $request->session()->regenerate();

        // Get authenticated user
        $user = Auth::user();

        return $this->redirectByRole($user);
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        // Enforce fixed location defaults for resident self-registration.
        $request->merge([
            'province' => 'Cavite',
            'municipality_city' => 'General Trias',
        ]);

        // Validate input
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                \Illuminate\Validation\Rule::unique('users', 'email')->whereNull('deleted_at'),
            ],
            'password' => 'required|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'house_no' => 'nullable|string|max:100',
            'barangay' => 'nullable|string|max:255',
            'province' => 'required|string|in:Cavite',
            'municipality_city' => 'required|string|in:General Trias',
            'nationality' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'marital_status' => 'nullable|in:single,married,divorced,widowed,separated',
        ]);

        // Generate unique account number from name initials + birthdate
        $accountNumber = User::generateAccountNumber(
            $validated['first_name'],
            $validated['middle_name'] ?? null,
            $validated['surname'],
            $validated['birthdate'] ?? null
        );

        // Create new user with pending status - all new registrations are residents
        $user = User::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'surname' => $validated['surname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'father_name' => $validated['father_name'] ?? null,
            'mother_name' => $validated['mother_name'] ?? null,
            'house_no' => $validated['house_no'] ?? null,
            'barangay' => $validated['barangay'] ?? null,
            'municipality_city' => $validated['municipality_city'] ?? null,
            'nationality' => $validated['nationality'] ?? null,
            'address' => $validated['address'] ?? null,
            'birthdate' => $validated['birthdate'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'marital_status' => $validated['marital_status'] ?? null,
            'role' => 'resident', // All new registrations default to resident
            'status' => 'pending',
            'account_number' => $accountNumber,
        ]);

        // Notify all admins and officials about the new resident registration
        $approvers = User::whereIn('role', ['admin', 'official'])
            ->where('status', 'approved')
            ->get();
        
        $notificationTitle = 'New Resident Registration';
        $notificationMessage = $user->getFullName() . " has registered and is awaiting approval.";
        $emailSubject = 'New Resident Registration Pending';
        $approvalUrl = url('/official/user-approvals');

        // Notify approvers
        foreach ($approvers as $approver) {
            Notification::create([
                'user_id' => $approver->id,
                'sender_id' => null,
                'title' => $notificationTitle,
                'message' => $notificationMessage,
            ]);

            MailService::send(
                $approver->email,
                $approver->getFullName(),
                $emailSubject,
                MailService::pendingApprovalEmail($user->getFullName(), $user->email, $approvalUrl)
            );
        }

        return redirect()
            ->route('pending')
            ->with('just_registered', true)
            ->with('pending_message', 'Your account is pending approval. Please wait for approval from an authorized administrator.');
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Verify official email address.
     */
    public function verifyOfficialEmail(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        // Find user by email and token
        $user = User::where('email', $email)
            ->where('email_verification_token', $token)
            ->where('role', 'official')
            ->first();

        if (!$user) {
            return redirect('/login')->with('error', 'Invalid or expired verification link.');
        }

        // Mark email as verified
        $user->email_verified_at = now();
        $user->email_verification_token = null;
        $user->status = 'approved';
        $user->save();

        // Create notification for the official
        Notification::create([
            'user_id' => $user->id,
            'sender_id' => null,
            'title' => 'Email Verified',
            'message' => 'Your email has been verified. Your account is now active and you can log in.',
        ]);

        return redirect('/login')->with('success', 'Your email has been verified! You can now log in with your credentials.');
    }
}
