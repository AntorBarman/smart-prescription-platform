<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\MedicineGeneric;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        // Categories
        $categories = [
            ['name' => 'Analgesics & Pain Relief', 'slug' => 'analgesics', 'description' => 'Pain relievers and fever reducers'],
            ['name' => 'Antibiotics', 'slug' => 'antibiotics', 'description' => 'Infection treatment medications'],
            ['name' => 'Antacids & GI', 'slug' => 'antacids', 'description' => 'Acid reflux and gastrointestinal treatment'],
            ['name' => 'Antihistamines', 'slug' => 'antihistamines', 'description' => 'Allergy treatment'],
            ['name' => 'Cardiovascular', 'slug' => 'cardiovascular', 'description' => 'Heart and blood pressure medications'],
            ['name' => 'Diabetes', 'slug' => 'diabetes', 'description' => 'Diabetes management'],
            ['name' => 'Respiratory', 'slug' => 'respiratory', 'description' => 'Asthma and respiratory treatment'],
            ['name' => 'Antidepressants', 'slug' => 'antidepressants', 'description' => 'Mental health medications'],
            ['name' => 'Vitamins & Supplements', 'slug' => 'vitamins', 'description' => 'Nutritional supplements'],
            ['name' => 'Skin & Dermatology', 'slug' => 'dermatology', 'description' => 'Skin treatment medications'],
        ];

        foreach ($categories as $cat) {
            MedicineCategory::firstOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }

        // Generics
        $generics = [
            ['name' => 'Paracetamol'],
            ['name' => 'Ibuprofen'],
            ['name' => 'Diclofenac'],
            ['name' => 'Aspirin'],
            ['name' => 'Amoxicillin'],
            ['name' => 'Ciprofloxacin'],
            ['name' => 'Azithromycin'],
            ['name' => 'Cephalexin'],
            ['name' => 'Doxycycline'],
            ['name' => 'Omeprazole'],
            ['name' => 'Esomeprazole'],
            ['name' => 'Ranitidine'],
            ['name' => 'Cetirizine'],
            ['name' => 'Loratadine'],
            ['name' => 'Fexofenadine'],
            ['name' => 'Amlodipine'],
            ['name' => 'Atenolol'],
            ['name' => 'Losartan'],
            ['name' => 'Atorvastatin'],
            ['name' => 'Metformin'],
            ['name' => 'Glibenclamide'],
            ['name' => 'Insulin Glargine'],
            ['name' => 'Salbutamol'],
            ['name' => 'Montelukast'],
            ['name' => 'Fluoxetine'],
            ['name' => 'Sertraline'],
            ['name' => 'Amitriptyline'],
            ['name' => 'Vitamin C'],
            ['name' => 'Vitamin D3'],
            ['name' => 'Calcium Carbonate'],
            ['name' => 'Hydrocortisone'],
            ['name' => 'Clotrimazole'],
        ];

        foreach ($generics as $gen) {
            MedicineGeneric::firstOrCreate(
                ['name' => $gen['name']],
                $gen
            );
        }

        // Medicines - [generic, category_slug, name, strength, dosage_form, sku, barcode]
        $medicines = [
            ['Paracetamol', 'analgesics', 'Paracetamol 500mg', '500mg', 'tablet', 'MED-PARA-500', '100001'],
            ['Ibuprofen', 'analgesics', 'Ibuprofen 400mg', '400mg', 'tablet', 'MED-IBU-400', '100002'],
            ['Diclofenac', 'analgesics', 'Diclofenac 50mg', '50mg', 'tablet', 'MED-DIC-50', '100003'],
            ['Aspirin', 'analgesics', 'Aspirin 75mg', '75mg', 'tablet', 'MED-ASP-75', '100004'],
            ['Paracetamol', 'analgesics', 'Paracetamol Syrup 120mg/5ml', '120mg/5ml', 'syrup', 'MED-PARA-SYR', '100005'],
            ['Ibuprofen', 'analgesics', 'Ibuprofen Syrup 100mg/5ml', '100mg/5ml', 'syrup', 'MED-IBU-SYR', '100006'],
            ['Amoxicillin', 'antibiotics', 'Amoxicillin 500mg', '500mg', 'capsule', 'MED-AMOX-500', '100007'],
            ['Ciprofloxacin', 'antibiotics', 'Ciprofloxacin 500mg', '500mg', 'tablet', 'MED-CIPRO-500', '100008'],
            ['Azithromycin', 'antibiotics', 'Azithromycin 500mg', '500mg', 'tablet', 'MED-AZ-500', '100009'],
            ['Cephalexin', 'antibiotics', 'Cephalexin 500mg', '500mg', 'capsule', 'MED-CEPH-500', '100010'],
            ['Doxycycline', 'antibiotics', 'Doxycycline 100mg', '100mg', 'capsule', 'MED-DOXY-100', '100011'],
            ['Amoxicillin', 'antibiotics', 'Amoxicillin Syrup 125mg/5ml', '125mg/5ml', 'syrup', 'MED-AMOX-SYR', '100012'],
            ['Omeprazole', 'antacids', 'Omeprazole 20mg', '20mg', 'capsule', 'MED-OME-20', '100013'],
            ['Omeprazole', 'antacids', 'Omeprazole 40mg', '40mg', 'capsule', 'MED-OME-40', '100014'],
            ['Esomeprazole', 'antacids', 'Esomeprazole 40mg', '40mg', 'tablet', 'MED-ESO-40', '100015'],
            ['Ranitidine', 'antacids', 'Ranitidine 150mg', '150mg', 'tablet', 'MED-RAN-150', '100016'],
            ['Cetirizine', 'antihistamines', 'Cetirizine 10mg', '10mg', 'tablet', 'MED-CET-10', '100017'],
            ['Loratadine', 'antihistamines', 'Loratadine 10mg', '10mg', 'tablet', 'MED-LOR-10', '100018'],
            ['Fexofenadine', 'antihistamines', 'Fexofenadine 120mg', '120mg', 'tablet', 'MED-FEX-120', '100019'],
            ['Cetirizine', 'antihistamines', 'Cetirizine Syrup 5mg/5ml', '5mg/5ml', 'syrup', 'MED-CET-SYR', '100020'],
            ['Amlodipine', 'cardiovascular', 'Amlodipine 5mg', '5mg', 'tablet', 'MED-AMLO-5', '100021'],
            ['Amlodipine', 'cardiovascular', 'Amlodipine 10mg', '10mg', 'tablet', 'MED-AMLO-10', '100022'],
            ['Atenolol', 'cardiovascular', 'Atenolol 50mg', '50mg', 'tablet', 'MED-ATEN-50', '100023'],
            ['Losartan', 'cardiovascular', 'Losartan 50mg', '50mg', 'tablet', 'MED-LOS-50', '100024'],
            ['Atorvastatin', 'cardiovascular', 'Atorvastatin 20mg', '20mg', 'tablet', 'MED-ATOR-20', '100025'],
            ['Metformin', 'diabetes', 'Metformin 500mg', '500mg', 'tablet', 'MED-MET-500', '100026'],
            ['Metformin', 'diabetes', 'Metformin 850mg', '850mg', 'tablet', 'MED-MET-850', '100027'],
            ['Glibenclamide', 'diabetes', 'Glibenclamide 5mg', '5mg', 'tablet', 'MED-GLIB-5', '100028'],
            ['Insulin Glargine', 'diabetes', 'Insulin Glargine 100IU/ml', '100IU/ml', 'injection', 'MED-INS-100', '100029'],
            ['Salbutamol', 'respiratory', 'Salbutamol Inhaler 100mcg', '100mcg', 'inhaler', 'MED-SAL-100', '100030'],
            ['Montelukast', 'respiratory', 'Montelukast 10mg', '10mg', 'tablet', 'MED-MONT-10', '100031'],
            ['Salbutamol', 'respiratory', 'Salbutamol Syrup 2mg/5ml', '2mg/5ml', 'syrup', 'MED-SAL-SYR', '100032'],
            ['Fluoxetine', 'antidepressants', 'Fluoxetine 20mg', '20mg', 'capsule', 'MED-FLU-20', '100033'],
            ['Sertraline', 'antidepressants', 'Sertraline 50mg', '50mg', 'tablet', 'MED-SER-50', '100034'],
            ['Amitriptyline', 'antidepressants', 'Amitriptyline 25mg', '25mg', 'tablet', 'MED-AMI-25', '100035'],
            ['Vitamin C', 'vitamins', 'Vitamin C 500mg', '500mg', 'tablet', 'MED-VITC-500', '100036'],
            ['Vitamin D3', 'vitamins', 'Vitamin D3 1000IU', '1000IU', 'tablet', 'MED-VITD-1000', '100037'],
            ['Calcium Carbonate', 'vitamins', 'Calcium Carbonate 500mg', '500mg', 'tablet', 'MED-CAL-500', '100038'],
            ['Hydrocortisone', 'dermatology', 'Hydrocortisone Cream 1%', '1%', 'cream', 'MED-HYDRO-1', '100039'],
            ['Clotrimazole', 'dermatology', 'Clotrimazole Cream 1%', '1%', 'cream', 'MED-CLOT-1', '100040'],
        ];

        $createdCount = 0;
        $skippedCount = 0;

        foreach ($medicines as $med) {
            $generic = MedicineGeneric::where('name', $med[0])->first();
            $category = MedicineCategory::where('slug', $med[1])->first();

            if ($generic && $category) {
                // SKU OR Barcode দিয়ে check করুন
                $medicine = Medicine::where('sku', $med[5])
                    ->orWhere('barcode', $med[6])
                    ->first();

                if (!$medicine) {
                    Medicine::create([
                        'category_id' => $category->id,
                        'generic_id' => $generic->id,
                        'name' => $med[2],
                        'strength' => $med[3],
                        'dosage_form' => $med[4],
                        'sku' => $med[5],
                        'barcode' => $med[6],
                        'requires_prescription' => true,
                        'is_active' => true,
                    ]);
                    $createdCount++;
                } else {
                    $skippedCount++;
                }
            }
        }

        $this->command->info("Medicine seeding completed!");
        $this->command->info("Created: $createdCount medicines");
        $this->command->info("Skipped: $skippedCount medicines");
        $this->command->info("Categories: " . MedicineCategory::count());
        $this->command->info("Generics: " . MedicineGeneric::count());
        $this->command->info("Total Medicines: " . Medicine::count());
    }
}
