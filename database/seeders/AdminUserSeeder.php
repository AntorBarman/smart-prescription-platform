<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'System Admin',
            'email' => 'admin@prescription.com',
            'password' => Hash::make('Admin@123'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('ADMIN');

        // Create Demo Doctor
        $doctor = User::create([
            'name' => 'Dr. Demo Doctor',
            'email' => 'doctor@prescription.com',
            'password' => Hash::make('Doctor@123'),
            'email_verified_at' => now(),
        ]);
        $doctor->assignRole('DOCTOR');

        // Create Demo Pharmacist
        $pharmacist = User::create([
            'name' => 'Demo Pharmacist',
            'email' => 'pharmacist@prescription.com',
            'password' => Hash::make('Pharmacy@123'),
            'email_verified_at' => now(),
        ]);
        $pharmacist->assignRole('PHARMACIST');

        // Create Demo Pharmacy Manager
        $manager = User::create([
            'name' => 'Demo Pharmacy Manager',
            'email' => 'manager@prescription.com',
            'password' => Hash::make('Manager@123'),
            'email_verified_at' => now(),
        ]);
        $manager->assignRole('PHARMACY_MANAGER');

        $this->command->info('Demo users created successfully!');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin', 'admin@prescription.com', 'Admin@123'],
                ['Doctor', 'doctor@prescription.com', 'Doctor@123'],
                ['Pharmacist', 'pharmacist@prescription.com', 'Pharmacy@123'],
                ['Manager', 'manager@prescription.com', 'Manager@123'],
            ]
        );
    }
}