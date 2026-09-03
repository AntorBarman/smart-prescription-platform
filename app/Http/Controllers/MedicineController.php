<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\MedicineGeneric;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $category = $request->input('category', '');
        $perPage = $request->input('per_page', 10);

        $medicines = Medicine::with(['category', 'generic'])
            ->when($search, function ($query, $search) {
                $query->search($search);
            })
            ->when($category, function ($query, $category) {
                $query->where('category_id', $category);
            })
            ->active()
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        $categories = MedicineCategory::active()->orderBy('name')->get();

        return Inertia::render('Medicines/Index', [
            'medicines' => $medicines,
            'categories' => $categories,
            'filters' => [
                'search' => $search,
                'category' => $category,
            ],
        ]);
    }

    public function create()
    {
        $categories = MedicineCategory::active()->orderBy('name')->get();
        $generics = MedicineGeneric::active()->orderBy('name')->get();

        return Inertia::render('Medicines/Create', [
            'categories' => $categories,
            'generics' => $generics,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:medicine_categories,id',
            'generic_id' => 'required|exists:medicine_generics,id',
            'name' => 'required|string|max:200',
            'strength' => 'required|string|max:50',
            'dosage_form' => 'required|string|max:50',
            'sku' => 'required|string|max:100|unique:medicines,sku',
            'barcode' => 'nullable|string|max:100|unique:medicines,barcode',
            'description' => 'nullable|string',
            'side_effects' => 'nullable|string',
            'contraindications' => 'nullable|string',
            'requires_prescription' => 'boolean',
            'is_active' => 'boolean',
        ]);

        Medicine::create($validated);

        return redirect()->route('medicines.index')
            ->with('success', 'Medicine created successfully.');
    }

    public function show(Medicine $medicine)
    {
        $medicine->load(['category', 'generic']);

        return Inertia::render('Medicines/Show', [
            'medicine' => $medicine,
        ]);
    }

    public function edit(Medicine $medicine)
    {
        $medicine->load(['category', 'generic']);
        $categories = MedicineCategory::active()->orderBy('name')->get();
        $generics = MedicineGeneric::active()->orderBy('name')->get();

        return Inertia::render('Medicines/Edit', [
            'medicine' => $medicine,
            'categories' => $categories,
            'generics' => $generics,
        ]);
    }

    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:medicine_categories,id',
            'generic_id' => 'required|exists:medicine_generics,id',
            'name' => 'required|string|max:200',
            'strength' => 'required|string|max:50',
            'dosage_form' => 'required|string|max:50',
            'sku' => 'required|string|max:100|unique:medicines,sku,' . $medicine->id,
            'barcode' => 'nullable|string|max:100|unique:medicines,barcode,' . $medicine->id,
            'description' => 'nullable|string',
            'side_effects' => 'nullable|string',
            'contraindications' => 'nullable|string',
            'requires_prescription' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $medicine->update($validated);

        return redirect()->route('medicines.index')
            ->with('success', 'Medicine updated successfully.');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();

        return redirect()->route('medicines.index')
            ->with('success', 'Medicine deleted successfully.');
    }

    // API for search
    public function search(Request $request)
    {
        $search = $request->input('q', '');
        
        $medicines = Medicine::with(['category', 'generic'])
            ->active()
            ->search($search)
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $medicines,
        ]);
    }
}
