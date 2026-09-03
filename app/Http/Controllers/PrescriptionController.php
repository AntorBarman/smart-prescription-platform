<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Patient;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class PrescriptionController extends Controller
{
    public function index(Request $request)
    {
        $prescriptions = Prescription::with(['patient', 'doctor'])
            ->when(auth()->user()->hasRole('DOCTOR'), function ($query) {
                $query->where('doctor_id', auth()->id());
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Prescriptions/Index', [
            'prescriptions' => $prescriptions,
        ]);
    }

    public function create()
    {
        $patients = Patient::orderBy('name')->get();
        $medicines = Medicine::with(['category', 'generic'])
            ->active()
            ->orderBy('name')
            ->get();

        return Inertia::render('Prescriptions/Create', [
            'patients' => $patients,
            'medicines' => $medicines,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'diagnosis' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.breakfast' => 'required|integer|min:0|max:5',
            'items.*.lunch' => 'required|integer|min:0|max:5',
            'items.*.dinner' => 'required|integer|min:0|max:5',
            'items.*.duration_days' => 'required|integer|min:1|max:365',
            'items.*.instructions' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $prescription = Prescription::create([
                'prescription_number' => Prescription::generateNumber(),
                'doctor_id' => auth()->id(),
                'patient_id' => $request->patient_id,
                'diagnosis' => $request->diagnosis,
                'notes' => $request->notes,
                'status' => 'issued',
                'issued_at' => now(),
                'expires_at' => now()->addDays(30),
            ]);

            foreach ($request->items as $item) {
                $dailyQty = ($item['breakfast'] ?? 0) + ($item['lunch'] ?? 0) + ($item['dinner'] ?? 0);
                $totalQty = $dailyQty * ($item['duration_days'] ?? 1);
                $dosageText = ($item['breakfast'] ?? 0) . '+' . ($item['lunch'] ?? 0) . '+' . ($item['dinner'] ?? 0);

                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'medicine_id' => $item['medicine_id'],
                    'dosage' => $dosageText,
                    'frequency' => $dosageText . ' (B+L+D)',
                    'duration_days' => $item['duration_days'],
                    'quantity' => $totalQty,
                    'instructions' => $item['instructions'] ?? null,
                ]);
            }
        });

        return redirect()->route('prescriptions.index')
            ->with('success', 'Prescription created successfully.');
    }

    public function show(Prescription $prescription)
    {
        $prescription->load(['patient', 'doctor', 'items.medicine.category', 'items.medicine.generic']);

        return Inertia::render('Prescriptions/Show', [
            'prescription' => $prescription,
        ]);
    }
}
