<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use App\Models\PharmacyInventory;
use App\Models\Medicine;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class PharmacyInventoryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $pharmacy = $user->pharmacy;

        // যদি pharmacy না থাকে, auto-create করুন
        if (!$pharmacy) {
            $pharmacy = Pharmacy::create([
                'name' => $user->name . "'s Pharmacy",
                'phone' => $user->phone,
                'email' => $user->email,
                'status' => 'approved',
                'owner_id' => $user->id,
            ]);
        }

        $inventory = PharmacyInventory::with(['medicine.category', 'medicine.generic'])
            ->where('pharmacy_id', $pharmacy->id)
            ->when($request->search, function ($query, $search) {
                $query->whereHas('medicine', function ($q) use ($search) {
                    $q->where('name', 'ILIKE', "%{$search}%")
                        ->orWhere('sku', 'ILIKE', "%{$search}%");
                });
            })
            ->orderBy('stock_quantity', 'asc')
            ->paginate(15);

        $allMedicines = Medicine::active()->orderBy('name')->get();

        return Inertia::render('Pharmacy/Inventory/Index', [
            'inventory' => $inventory,
            'pharmacy' => $pharmacy,
            'allMedicines' => $allMedicines,
        ]);
    }

    public function addStock(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'selling_price' => 'required|numeric|min:0',
        ]);

        $user = auth()->user();
        $pharmacy = $user->pharmacy;

        if (!$pharmacy) {
            $pharmacy = Pharmacy::create([
                'name' => $user->name . "'s Pharmacy",
                'status' => 'approved',
                'owner_id' => $user->id,
            ]);
        }

        DB::transaction(function () use ($request, $pharmacy) {
            $inventory = PharmacyInventory::firstOrCreate(
                [
                    'pharmacy_id' => $pharmacy->id,
                    'medicine_id' => $request->medicine_id,
                ],
                [
                    'stock_quantity' => 0,
                    'selling_price' => $request->selling_price,
                    'reorder_level' => 10,
                ]
            );

            $inventory->stock_quantity += $request->quantity;
            $inventory->selling_price = $request->selling_price;
            $inventory->save();

            InventoryTransaction::create([
                'pharmacy_id' => $pharmacy->id,
                'medicine_id' => $request->medicine_id,
                'type' => 'PURCHASE',
                'quantity' => $request->quantity,
                'created_by' => auth()->id(),
            ]);
        });

        return back()->with('success', 'Stock added successfully.');
    }

    public function bulkImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
        ]);

        $pharmacy = auth()->user()->pharmacy;
        $file = $request->file('file');

        // CSV পড়ুন
        $rows = array_map('str_getcsv', file($file->getRealPath()));

        // Header row skip করুন
        $header = array_shift($rows);

        $imported = 0;
        $skipped = 0;
        $errors = [];

        DB::transaction(function () use ($rows, $pharmacy, &$imported, &$skipped, &$errors) {
            foreach ($rows as $row) {
                if (count($row) < 3) continue;

                // CSV format: Medicine Name/SKU, Stock Quantity, Selling Price
                $medicineIdentifier = trim($row[0]);
                $quantity = (int) trim($row[1]);
                $price = (float) trim($row[2]);

                if ($quantity <= 0 || $price < 0) {
                    $errors[] = "Invalid data: $medicineIdentifier";
                    $skipped++;
                    continue;
                }

                // Medicine খুঁজুন (name বা SKU দিয়ে)
                $medicine = Medicine::where('name', $medicineIdentifier)
                    ->orWhere('sku', $medicineIdentifier)
                    ->first();

                if (!$medicine) {
                    $errors[] = "Medicine not found: $medicineIdentifier";
                    $skipped++;
                    continue;
                }

                // Inventory update or create
                $inventory = PharmacyInventory::firstOrCreate(
                    [
                        'pharmacy_id' => $pharmacy->id,
                        'medicine_id' => $medicine->id,
                    ],
                    [
                        'stock_quantity' => 0,
                        'selling_price' => $price,
                        'reorder_level' => 10,
                    ]
                );

                $inventory->stock_quantity += $quantity;
                $inventory->selling_price = $price;
                $inventory->save();

                InventoryTransaction::create([
                    'pharmacy_id' => $pharmacy->id,
                    'medicine_id' => $medicine->id,
                    'type' => 'PURCHASE',
                    'quantity' => $quantity,
                    'created_by' => auth()->id(),
                    'notes' => 'Bulk import',
                ]);

                $imported++;
            }
        });

        return back()->with('success', "Imported: $imported items. Skipped: $skipped items.");
    }

    public function adjustStock(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer',
            'type' => 'required|in:ADJUSTMENT,DAMAGE,EXPIRED,RETURN',
        ]);

        $user = auth()->user();
        $pharmacy = $user->pharmacy;

        DB::transaction(function () use ($request, $id, $pharmacy) {
            $inventory = PharmacyInventory::where('pharmacy_id', $pharmacy->id)
                ->where('id', $id)
                ->firstOrFail();

            $inventory->stock_quantity += $request->quantity;
            $inventory->save();

            InventoryTransaction::create([
                'pharmacy_id' => $pharmacy->id,
                'medicine_id' => $inventory->medicine_id,
                'type' => $request->type,
                'quantity' => $request->quantity,
                'created_by' => auth()->id(),
            ]);
        });

        return back()->with('success', 'Stock adjusted successfully.');
    }
}
