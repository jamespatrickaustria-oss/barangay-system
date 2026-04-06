<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\PasswordResetOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordOtpController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $email = $validated['email'];

        PasswordResetOtp::where('email', $email)->delete();

        $otp = (string) random_int(100000, 999999);

        PasswordResetOtp::create([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($email)->send(new OtpMail($otp));

        session(['password_reset_email' => $email]);
        session()->forget('password_reset_verified_email');
        session()->flash('success', 'An OTP has been sent to your email address.');

        return redirect()->route('password.otp.verify');
    }

    public function showVerifyForm()
    {
        if (!session('password_reset_email')) {
            session()->flash('error', 'Please request an OTP first.');

            return redirect()->route('password.forgot');
        }

        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $email = session('password_reset_email');

        if (!$email) {
            session()->flash('error', 'Please request an OTP first.');

            return redirect()->route('password.forgot');
        }

        $otpRecord = PasswordResetOtp::where('email', $email)
            ->where('otp', $request->otp)
            ->latest('id')
            ->first();

        if (!$otpRecord || !$otpRecord->isValid()) {
            session()->flash('error', 'Invalid or expired OTP. Please try again.');

            return back()->withInput();
        }

        $otpRecord->update(['is_used' => true]);

        session(['password_reset_verified_email' => $email]);
        session()->flash('success', 'OTP verified successfully. You may now reset your password.');

        return redirect()->route('password.reset');
    }

    public function showResetForm()
    {
        if (!session('password_reset_verified_email')) {
            session()->flash('error', 'Please verify your OTP first.');

            return redirect()->route('password.forgot');
        }

        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $verifiedEmail = session('password_reset_verified_email');

        if (!$verifiedEmail || $validated['email'] !== $verifiedEmail) {
            session()->flash('error', 'Invalid reset session. Please restart the password reset process.');

            return redirect()->route('password.forgot');
        }

        $user = User::where('email', $validated['email'])->first();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        PasswordResetOtp::where('email', $validated['email'])->delete();

        session()->forget(['password_reset_email', 'password_reset_verified_email']);
        session()->flash('success', 'Your password has been reset successfully. You can now log in.');

        return redirect()->route('login');
    }
}
