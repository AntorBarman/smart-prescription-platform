<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\PharmacyInventory;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $pharmacy = auth()->user()->pharmacy;

        $sales = Sale::with(['prescription.patient', 'creator'])
            ->where('pharmacy_id', $pharmacy->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('Pharmacy/Sales/Index', [
            'sales' => $sales,
        ]);
    }

    public function create(Request $request)
    {
        $pharmacy = auth()->user()->pharmacy;

        $prescription = null;
        $prescriptionItems = [];

        if ($request->prescription_id) {
            $prescription = \App\Models\Prescription::with(['patient', 'doctor', 'items.medicine'])
                ->find($request->prescription_id);

            if ($prescription) {
                // Prescription items থেকে auto-fill items
                foreach ($prescription->items as $item) {
                    $inventory = PharmacyInventory::where('pharmacy_id', $pharmacy->id)
                        ->where('medicine_id', $item->medicine_id)
                        ->first();

                    $prescriptionItems[] = [
                        'medicine_id' => $item->medicine_id,
                        'medicine_name' => $item->medicine->name,
                        'quantity' => $item->quantity,
                        'unit_price' => $inventory ? $inventory->selling_price : 0,
                    ];
                }
            }
        }

        $inventory = PharmacyInventory::with('medicine')
            ->where('pharmacy_id', $pharmacy->id)
            ->where('stock_quantity', '>', 0)
            ->get();

        return Inertia::render('Pharmacy/Sales/Create', [
            'prescription' => $prescription,
            'prescriptionItems' => $prescriptionItems,
            'inventory' => $inventory,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $pharmacy = auth()->user()->pharmacy;

        DB::transaction(function () use ($request, $pharmacy) {
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            $tax = $subtotal * 0.05; // 5% tax
            $discount = $request->discount ?? 0;
            $grandTotal = $subtotal + $tax - $discount;

            $sale = Sale::create([
                'invoice_number' => Sale::generateInvoiceNumber(),
                'pharmacy_id' => $pharmacy->id,
                'prescription_id' => $request->prescription_id,
                'created_by' => auth()->id(),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'grand_total' => $grandTotal,
                'status' => 'completed',
            ]);

            foreach ($request->items as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'medicine_id' => $item['medicine_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                ]);

                // Deduct stock
                $inventory = PharmacyInventory::where('pharmacy_id', $pharmacy->id)
                    ->where('medicine_id', $item['medicine_id'])
                    ->first();

                if ($inventory) {
                    $inventory->stock_quantity -= $item['quantity'];
                    $inventory->save();

                    InventoryTransaction::create([
                        'pharmacy_id' => $pharmacy->id,
                        'medicine_id' => $item['medicine_id'],
                        'type' => 'SALE',
                        'quantity' => -$item['quantity'],
                        'reference_type' => Sale::class,
                        'reference_id' => $sale->id,
                        'created_by' => auth()->id(),
                    ]);
                }
            }
        });

        return redirect()->route('pharmacy.sales.index')
            ->with('success', 'Sale completed successfully.');
    }

    public function show(Sale $sale)
    {
        $sale->load(['items.medicine', 'prescription.patient', 'creator']);

        return Inertia::render('Pharmacy/Sales/Show', [
            'sale' => $sale,
        ]);
    }
}
