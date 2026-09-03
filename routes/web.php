<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PharmacyInventoryController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\QRController;
use App\Http\Controllers\SalesController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// HOME PAGE - Public landing page
Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

// LOGIN ROUTES
Route::get('/login', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return app(AuthController::class)->showLoginForm();
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.store');

// REGISTER ROUTES
Route::get('/register', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return app(AuthController::class)->showRegistrationForm();
})->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('register.store');

// EMAIL VERIFICATION ROUTES
Route::get('/email/verify', function () {
    return Inertia::render('Auth/VerifyEmail');
})->middleware('auth')->name('verification.notice');

// Manual verification - ID দিয়ে database update
Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    $user = \App\Models\User::find($id);

    if (!$user) {
        return redirect()->route('login')->with('error', 'User not found.');
    }

    // Hash verify করুন
    if (hash_equals(sha1($user->getEmailForVerification()), $hash)) {
        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('login')->with('success', 'Email verified successfully! Please login.');
    }

    return redirect()->route('login')->with('error', 'Invalid verification link.');
})->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
// AUTHENTICATED ROUTES
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Main dashboard redirect based on role
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->hasRole('ADMIN')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('DOCTOR')) {
            return redirect()->route('doctor.dashboard');
        }

        if ($user->hasRole(['PHARMACIST', 'PHARMACY_MANAGER'])) {
            return redirect()->route('pharmacy.dashboard');
        }

        return Inertia::render('Dashboard');
    })->name('dashboard');

    // ==================== ADMIN ROUTES ====================
    Route::middleware(['role:ADMIN'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            $stats = [
                'totalDoctors' => \App\Models\User::role('DOCTOR')->count(),
                'totalPharmacies' => \App\Models\User::role(['PHARMACIST', 'PHARMACY_MANAGER'])->count(),
                'totalPatients' => \App\Models\Patient::count(),
                'totalMedicines' => \App\Models\Medicine::count(),
                'totalPrescriptions' => \App\Models\Prescription::count(),
                'todayPrescriptions' => \App\Models\Prescription::whereDate('created_at', today())->count(),
            ];

            return Inertia::render('Admin/Dashboard', [
                'stats' => $stats,
            ]);
        })->name('dashboard');
    });

    // ==================== DOCTOR ROUTES ====================
    Route::middleware(['role:DOCTOR'])->prefix('doctor')->name('doctor.')->group(function () {
        Route::get('/dashboard', function () {
            $stats = [
                'totalPatients' => \App\Models\Patient::count(),
                'todayPrescriptions' => \App\Models\Prescription::where('doctor_id', auth()->id())
                    ->whereDate('created_at', today())
                    ->count(),
                'totalPrescriptions' => \App\Models\Prescription::where('doctor_id', auth()->id())->count(),
            ];

            $recentPrescriptions = \App\Models\Prescription::with('patient')
                ->where('doctor_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return Inertia::render('Doctor/Dashboard', [
                'stats' => $stats,
                'recentPrescriptions' => $recentPrescriptions,
            ]);
        })->name('dashboard');
    });

    // ==================== PHARMACY ROUTES ====================
    Route::middleware(['role:PHARMACIST,PHARMACY_MANAGER'])->prefix('pharmacy')->name('pharmacy.')->group(function () {

        // Dashboard
        Route::get('/dashboard', function () {
            $user = auth()->user();
            $pharmacy = $user->pharmacy;

            if (!$pharmacy) {
                $pharmacy = \App\Models\Pharmacy::create([
                    'name' => $user->name . "'s Pharmacy",
                    'status' => 'approved',
                    'owner_id' => $user->id,
                ]);
            }

            $stats = [
                'totalMedicines' => \App\Models\PharmacyInventory::where('pharmacy_id', $pharmacy->id)->sum('stock_quantity'),
                'lowStock' => \App\Models\PharmacyInventory::where('pharmacy_id', $pharmacy->id)
                    ->whereColumn('stock_quantity', '<=', 'reorder_level')
                    ->count(),
                'prescriptionsProcessed' => \App\Models\Prescription::count(),
                'totalSales' => \App\Models\Sale::where('pharmacy_id', $pharmacy->id)->sum('grand_total'),
            ];

            return Inertia::render('Pharmacy/Dashboard', [
                'stats' => $stats,
            ]);
        })->name('dashboard');

        // Scanner
        Route::get('/scanner', function () {
            return Inertia::render('Pharmacy/Scanner');
        })->name('scanner');
    });

    // ==================== MEDICINE ROUTES ====================
    Route::prefix('medicines')->name('medicines.')->group(function () {
        Route::get('/', [MedicineController::class, 'index'])->name('index');
        Route::get('/create', [MedicineController::class, 'create'])->name('create');
        Route::post('/', [MedicineController::class, 'store'])->name('store');
        Route::get('/{medicine}', [MedicineController::class, 'show'])->name('show');
        Route::get('/{medicine}/edit', [MedicineController::class, 'edit'])->name('edit');
        Route::put('/{medicine}', [MedicineController::class, 'update'])->name('update');
        Route::delete('/{medicine}', [MedicineController::class, 'destroy'])->name('destroy');
    });

    // Medicine Search API
    Route::get('/api/medicines/search', [MedicineController::class, 'search'])->name('medicines.search');

    // ==================== PATIENT ROUTES ====================
    // Search API (BEFORE {patient} route)
    Route::get('/patients/search', [PatientController::class, 'search'])->name('patients.search');

    Route::prefix('patients')->name('patients.')->group(function () {
        Route::get('/', [PatientController::class, 'index'])->name('index');
        Route::get('/create', [PatientController::class, 'create'])->name('create');
        Route::post('/', [PatientController::class, 'store'])->name('store');
        Route::get('/{patient}', [PatientController::class, 'show'])->name('show');
        Route::get('/{patient}/edit', [PatientController::class, 'edit'])->name('edit');
        Route::put('/{patient}', [PatientController::class, 'update'])->name('update');
        Route::delete('/{patient}', [PatientController::class, 'destroy'])->name('destroy');
    });

    // ==================== PRESCRIPTION ROUTES ====================
    Route::prefix('prescriptions')->name('prescriptions.')->group(function () {
        Route::get('/', [PrescriptionController::class, 'index'])->name('index');
        Route::get('/create', [PrescriptionController::class, 'create'])->name('create');
        Route::post('/', [PrescriptionController::class, 'store'])->name('store');
        Route::get('/{prescription}', [PrescriptionController::class, 'show'])->name('show');
    });

    // QR Routes
    Route::get('/prescriptions/{prescription}/qr', [QRController::class, 'generate'])->name('prescriptions.qr');
    Route::post('/api/qr/process', [QRController::class, 'process'])->name('qr.process');

    // ==================== PHARMACY INVENTORY ROUTES ====================
    Route::middleware(['role:PHARMACIST,PHARMACY_MANAGER'])->prefix('pharmacy/inventory')->name('pharmacy.inventory.')->group(function () {
        Route::get('/', [PharmacyInventoryController::class, 'index'])->name('index');
        Route::post('/add-stock', [PharmacyInventoryController::class, 'addStock'])->name('add-stock');
        Route::post('/bulk-import', [PharmacyInventoryController::class, 'bulkImport'])->name('bulk-import');
        Route::post('/{id}/adjust', [PharmacyInventoryController::class, 'adjustStock'])->name('adjust');
    });

    // ==================== SALES ROUTES ====================
    Route::middleware(['role:PHARMACIST,PHARMACY_MANAGER'])->prefix('pharmacy/sales')->name('pharmacy.sales.')->group(function () {
        Route::get('/', [SalesController::class, 'index'])->name('index');
        Route::get('/create', [SalesController::class, 'create'])->name('create');
        Route::post('/', [SalesController::class, 'store'])->name('store');
        Route::get('/{sale}', [SalesController::class, 'show'])->name('show');
    });
});
