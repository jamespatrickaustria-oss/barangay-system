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
        // Admin can always login - no email verification needed
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

        if ($user->role === 'resident' && $user->status === 'pending') {
            // Keep the user logged in so they can still access the pending status page.
            return redirect('/pending')->with('info', 'Your registration is still pending approval.');
        }

        if ($user->role === 'resident' && $user->status === 'rejected') {
            Auth::logout();
            return redirect('/login')->with('error', 'Your registration was rejected. Please contact the barangay office.');
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
                $user->role === 'admin' ||
                $user->role === 'official' ||
                ($user->role === 'resident' && $user->status === 'approved')
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

        // Check password
        if (!Hash::check($password, $user->password)) {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('error', 'The password you entered is incorrect.');
        }

        // Attempt authentication
        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('error', 'Authentication failed. Please try again.');
        }

        // Get authenticated user
        $user = Auth::user();

        return $this->redirectByRole($user);
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'unique:users,email',
                function ($attribute, $value, $fail) {
                    // Check if email exists in soft deleted records
                    if (User::onlyTrashed()->where('email', $value)->exists()) {
                        $fail('This email address has been previously used and cannot be registered again.');
                    }
                },
            ],
            'password' => 'required|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'marital_status' => 'nullable|in:single,married,divorced,widowed,separated',
        ]);

        // Create new user with pending status
        $user = User::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'surname' => $validated['surname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'birthdate' => $validated['birthdate'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'marital_status' => $validated['marital_status'] ?? null,
            'role' => 'resident',
            'status' => 'pending',
        ]);

        // Find all officials
        $officials = User::where('role', 'official')->get();

        // Notify each official
        foreach ($officials as $official) {
            // Create notification record
            Notification::create([
                'user_id' => $official->id,
                'sender_id' => null,
                'title' => 'New Resident Registration',
                'message' => $user->getFullName() . " has registered and is awaiting approval.",
            ]);

            // Send email notification
            MailService::send(
                $official->email,
                $official->getFullName(),
                'New Resident Registration Pending',
                MailService::pendingApprovalEmail($user->getFullName(), $user->email, url('/official/residents'))
            );
        }

        return redirect()
            ->route('pending')
            ->with('just_registered', true)
            ->with('pending_message', 'Wait for Barangay Official approval before you can log in.');
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
