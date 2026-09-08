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
        $email = strtolower(trim($request->string('email')->toString()));
        $credentials = [
            'email' => $email,
            'password' => $request->input('password'),
        ];
        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

        $status = $user ? strtolower(trim((string) $user->status)) : null;

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'No account was found for this email address. Please register on this site first.',
            ]);
        }

        if ($status !== UserStatus::ACTIVE->value) {
            throw ValidationException::withMessages([
                'email' => in_array($status, [UserStatus::INACTIVE->value, UserStatus::SUSPENDED->value], true)
                    ? 'Your account is inactive or suspended.'
                    : 'Your account could not be activated. Please contact support.',
            ]);
        }

        if (!Auth::attempt($credentials, $request->boolean('remember', false))) {
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials.',
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
        ]);

        $user->assignRole($request->role);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}