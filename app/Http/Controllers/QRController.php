<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Services\PrescriptionQRService;
use Illuminate\Http\Request;

class QRController extends Controller
{
    protected $qrService;

    public function __construct(PrescriptionQRService $qrService)
    {
        $this->qrService = $qrService;
    }

    public function generate(Prescription $prescription)
    {
        try {
            $payload = $this->qrService->generatePayload($prescription);

            return response()->json([
                'success' => true,
                'data' => [
                    'prescription_number' => $prescription->prescription_number,
                    'qr_payload' => $payload,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function process(Request $request)
    {
        $request->validate([
            'qr_content' => 'required|string',
        ]);

        try {
            $payload = $this->qrService->validate($request->qr_content);

            $prescription = Prescription::with(['patient', 'doctor', 'items.medicine'])
                ->where('prescription_number', $payload['rx'])
                ->first();

            if (!$prescription) {
                return response()->json([
                    'success' => false,
                    'message' => 'Prescription not found.',
                ], 404);
            }

            // Check status
            if ($prescription->status === 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Prescription has been cancelled.',
                ], 400);
            }

            if ($prescription->status === 'fulfilled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Prescription already fulfilled.',
                ], 400);
            }

            // Check expiry
            if ($prescription->expires_at && $prescription->expires_at->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Prescription has expired.',
                ], 400);
            }

            // Get pharmacy inventory pricing
            $pharmacy = auth()->user()->pharmacy;

            $itemsWithPrice = $prescription->items->map(function ($item) use ($pharmacy) {
                $inventory = \App\Models\PharmacyInventory::where('pharmacy_id', $pharmacy->id)
                    ->where('medicine_id', $item->medicine_id)
                    ->first();

                $unitPrice = $inventory ? $inventory->selling_price : 0;
                $totalPrice = $unitPrice * $item->quantity;
                $inStock = $inventory ? $inventory->stock_quantity : 0;
                $isAvailable = $inStock >= $item->quantity;

                return [
                    'id' => $item->id,
                    'medicine_name' => $item->medicine->name,
                    'strength' => $item->medicine->strength,
                    'dosage' => $item->dosage,
                    'duration_days' => $item->duration_days,
                    'quantity' => $item->quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'in_stock' => $inStock,
                    'is_available' => $isAvailable,
                ];
            });

            $subtotal = $itemsWithPrice->sum('total_price');
            $tax = $subtotal * 0.05; // 5% tax
            $grandTotal = $subtotal + $tax;

            return response()->json([
                'success' => true,
                'data' => [
                    'prescription' => $prescription,
                    'items' => $itemsWithPrice,
                    'pricing' => [
                        'subtotal' => round($subtotal, 2),
                        'tax' => round($tax, 2),
                        'grand_total' => round($grandTotal, 2),
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
