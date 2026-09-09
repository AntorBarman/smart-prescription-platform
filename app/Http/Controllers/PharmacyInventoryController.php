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
            'file' => 'required|file|extensions:csv,txt|max:10240',
        ]);

        $pharmacy = auth()->user()->pharmacy;
        if (!$pharmacy) {
            $pharmacy = Pharmacy::create([
                'name' => auth()->user()->name . "'s Pharmacy",
                'phone' => auth()->user()->phone,
                'email' => auth()->user()->email,
                'status' => 'approved',
                'owner_id' => auth()->id(),
            ]);
        }
        $file = $request->file('file');

        $imported = 0;
        $skipped = 0;
        $errors = [];

        DB::transaction(function () use ($file, $pharmacy, &$imported, &$skipped, &$errors) {
            $contents = file_get_contents($file->getRealPath());
            if ($contents === false || trim($contents) === '') {
                throw new \RuntimeException('The uploaded CSV file is empty.');
            }

            // Excel may save CSV files as UTF-16; normalize them before parsing.
            if (str_starts_with($contents, "\xFF\xFE")) {
                $contents = mb_convert_encoding(substr($contents, 2), 'UTF-8', 'UTF-16LE');
            } elseif (str_starts_with($contents, "\xFE\xFF")) {
                $contents = mb_convert_encoding(substr($contents, 2), 'UTF-8', 'UTF-16BE');
            } elseif (str_starts_with($contents, "\xEF\xBB\xBF")) {
                $contents = substr($contents, 3);
            } elseif (mb_detect_encoding($contents, ['UTF-16LE', 'UTF-16BE'], true) !== false) {
                $contents = mb_convert_encoding($contents, 'UTF-8');
            }

            $firstLine = strtok($contents, "\r\n");
            $delimiterCounts = [
                ',' => substr_count((string) $firstLine, ','),
                ';' => substr_count((string) $firstLine, ';'),
                "\t" => substr_count((string) $firstLine, "\t"),
            ];
            arsort($delimiterCounts);
            $delimiter = array_key_first($delimiterCounts);
            if ($delimiterCounts[$delimiter] === 0) {
                $delimiter = ',';
            }
            $handle = fopen('php://temp', 'r+');
            fwrite($handle, $contents);
            rewind($handle);

            if ($handle === false) {
                throw new \RuntimeException('The uploaded CSV file could not be opened.');
            }

            // The first row is the CSV header.
            fgetcsv($handle, 0, $delimiter);
            $line = 1;

            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                $line++;
                if (count($row) < 3 || trim(implode('', $row)) === '') {
                    if (trim(implode('', $row)) !== '') {
                        $errors[] = "Line {$line}: expected Medicine Name, Stock Quantity and Selling Price.";
                        $skipped++;
                    }
                    continue;
                }

                // CSV format: Medicine Name/SKU, Stock Quantity, Selling Price
                $medicineIdentifier = trim((string) $row[0], " \t\n\r\0\x0B\xEF\xBB\xBF\"");
                $quantityValue = trim((string) $row[1]);
                $priceValue = trim((string) $row[2]);
                $quantity = filter_var($quantityValue, FILTER_VALIDATE_INT);
                $price = filter_var($priceValue, FILTER_VALIDATE_FLOAT);

                if ($quantity === false || $quantity <= 0 || $price === false || $price < 0) {
                    $errors[] = "Line {$line}: invalid quantity or price for {$medicineIdentifier}.";
                    $skipped++;
                    continue;
                }

                // Medicine খুঁজুন (name বা SKU দিয়ে)
                $medicine = Medicine::whereRaw('LOWER(TRIM(name)) = ?', [strtolower($medicineIdentifier)])
                    ->orWhereRaw('LOWER(TRIM(sku)) = ?', [strtolower($medicineIdentifier)])
                    ->first();

                if (!$medicine) {
                    $errors[] = "Line {$line}: medicine not found: {$medicineIdentifier}.";
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

            fclose($handle);
        });

        $message = "Imported: {$imported} items. Skipped: {$skipped} items.";
        if ($errors !== []) {
            return back()->with('success', $message)->with('error', implode(' ', array_slice($errors, 0, 10)));
        }

        return back()->with('success', $message);
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
