<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Enums\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return Inertia::render('Auth/Login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || $user->status !== UserStatus::ACTIVE->value) {
            throw ValidationException::withMessages([
                'email' => 'Your account is inactive or suspended.',
            ]);
        }

        if (!Auth::attempt($credentials, $request->boolean('remember', false))) {
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials.',
            ]);
        }

        // ✅ Email Verification Check
        if (!$user->hasVerifiedEmail()) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Please verify your email address first.',
            ]);
        }

        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        $request->session()->regenerate();

        if ($user->hasRole('ADMIN')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('DOCTOR')) {
            return redirect()->route('doctor.dashboard');
        }

        if ($user->hasRole('PHARMACIST') || $user->hasRole('PHARMACY_MANAGER')) {
            return redirect()->route('pharmacy.dashboard');
        }

        return redirect()->route('dashboard');
    }

    public function showRegistrationForm()
    {
        return Inertia::render('Auth/Register');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => UserStatus::ACTIVE->value,
            // email_verified_at NOT set — verification required
        ]);

        $user->assignRole($request->role);

        // ✅ Send verification email
        $user->sendEmailVerificationNotification();

        Auth::login($user);

        // ✅ Redirect to verification notice
        return redirect()->route('verification.notice');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}