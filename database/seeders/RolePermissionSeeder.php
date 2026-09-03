<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions
        $permissions = [
            // Prescription permissions
            'prescription.create',
            'prescription.view',
            'prescription.update',
            'prescription.cancel',
            'prescription.print',
            
            // Inventory permissions
            'inventory.view',
            'inventory.update',
            'inventory.import',
            'inventory.export',
            'inventory.adjust',
            
            // Sales permissions
            'sale.create',
            'sale.view',
            'sale.confirm',
            'sale.refund',
            'sale.void',
            
            // Reports permissions
            'report.view',
            'report.export',
            
            // Pharmacy management
            'pharmacy.view',
            'pharmacy.create',
            'pharmacy.update',
            'pharmacy.approve',
            'pharmacy.suspend',
            
            // User management
            'user.view',
            'user.create',
            'user.update',
            'user.delete',
            'user.assign_role',
            
            // Medicine catalog
            'medicine.view',
            'medicine.create',
            'medicine.update',
            'medicine.delete',
            
            // Patient management
            'patient.view',
            'patient.create',
            'patient.update',
            
            // Audit
            'audit.view',
            
            // System settings
            'setting.view',
            'setting.update',
        ];

        // Create or find permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create or find roles and assign permissions
        
        // 1. ADMIN Role - Full access
        $adminRole = Role::firstOrCreate(['name' => 'ADMIN']);
        $adminRole->syncPermissions(Permission::all());

        // 2. DOCTOR Role
        $doctorRole = Role::firstOrCreate(['name' => 'DOCTOR']);
        $doctorRole->syncPermissions([
            'prescription.create',
            'prescription.view',
            'prescription.update',
            'prescription.cancel',
            'prescription.print',
            'patient.view',
            'patient.create',
            'patient.update',
            'medicine.view',
        ]);

        // 3. PHARMACIST Role
        $pharmacistRole = Role::firstOrCreate(['name' => 'PHARMACIST']);
        $pharmacistRole->syncPermissions([
            'prescription.view',
            'inventory.view',
            'inventory.update',
            'sale.create',
            'sale.view',
            'sale.confirm',
            'medicine.view',
        ]);

        // 4. PHARMACY_MANAGER Role
        $managerRole = Role::firstOrCreate(['name' => 'PHARMACY_MANAGER']);
        $managerRole->syncPermissions([
            'prescription.view',
            'inventory.view',
            'inventory.update',
            'inventory.import',
            'inventory.export',
            'inventory.adjust',
            'sale.create',
            'sale.view',
            'sale.confirm',
            'sale.refund',
            'sale.void',
            'report.view',
            'report.export',
            'medicine.view',
            'medicine.create',
            'medicine.update',
            'patient.view',
        ]);

        $this->command->info('Roles and permissions synced successfully!');
    }
}