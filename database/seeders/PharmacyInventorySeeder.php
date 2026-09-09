<?php

namespace Database\Seeders;

use App\Models\Pharmacy;
use App\Models\PharmacyInventory;
use App\Models\Medicine;
use App\Models\InventoryTransaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class PharmacyInventorySeeder extends Seeder
{
    public function run(): void
    {
        // সব pharmacy খুঁজুন
        $pharmacies = Pharmacy::all();

        if ($pharmacies->isEmpty()) {
            // Demo pharmacy create করুন
            $owner = User::where('email', 'pharmacist@prescription.com')->firstOrFail();
            $pharmacy = Pharmacy::create([
                'name' => 'Demo Pharmacy',
                'phone' => '01700000002',
                'email' => 'pharmacy@prescription.com',
                'address' => 'Dhaka, Bangladesh',
                'license_number' => 'PH-2026-001',
                'status' => 'approved',
                'owner_id' => $owner->id,
            ]);
            $pharmacies = collect([$pharmacy]);
        }

        // সব medicines
        $medicines = Medicine::all();

        if ($medicines->isEmpty()) {
            $this->command->info('No medicines found. Run MedicineSeeder first.');
            return;
        }

        // প্রতিটি pharmacy-র জন্য inventory data
        foreach ($pharmacies as $pharmacy) {
            foreach ($medicines as $medicine) {
                $stock = 100;
                
                // Price based on medicine type
                $basePrice = match($medicine->dosage_form) {
                    'tablet' => rand(2, 15),
                    'capsule' => rand(5, 25),
                    'syrup' => rand(80, 250),
                    'injection' => rand(50, 300),
                    'inhaler' => rand(200, 500),
                    'cream' => rand(100, 350),
                    default => rand(10, 100),
                };

                // Reorder level (10-30% of stock)
                $reorderLevel = max(10, (int)($stock * 0.15));

                $inventory = PharmacyInventory::firstOrCreate(
                    [
                        'pharmacy_id' => $pharmacy->id,
                        'medicine_id' => $medicine->id,
                    ],
                    [
                        'stock_quantity' => $stock,
                        'selling_price' => $basePrice,
                        'reorder_level' => $reorderLevel,
                    ]
                );

                InventoryTransaction::firstOrCreate([
                    'pharmacy_id' => $pharmacy->id,
                    'medicine_id' => $medicine->id,
                    'type' => 'PURCHASE',
                    'reference_type' => self::class,
                    'notes' => 'Initial stock from seeder',
                ], [
                    'quantity' => $inventory->stock_quantity,
                    'created_by' => $pharmacy->owner_id,
                ]);
            }
        }

        $this->command->info('Pharmacy inventory seeded successfully!');
        $this->command->info('Total pharmacies: ' . $pharmacies->count());
        $this->command->info('Total medicines per pharmacy: ' . $medicines->count());
    }
}