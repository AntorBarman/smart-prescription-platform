<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'System Admin', 'email' => 'admin@prescription.com', 'password' => 'Admin@123', 'role' => 'ADMIN'],
            ['name' => 'Dr. Demo Doctor', 'email' => 'doctor@prescription.com', 'password' => 'Doctor@123', 'role' => 'DOCTOR'],
            ['name' => 'Demo Pharmacist', 'email' => 'pharmacist@prescription.com', 'password' => 'Pharmacy@123', 'role' => 'PHARMACIST'],
            ['name' => 'Demo Pharmacy Manager', 'email' => 'manager@prescription.com', 'password' => 'Manager@123', 'role' => 'PHARMACY_MANAGER'],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                    'status' => 'active',
                ]
            );

            $user->forceFill([
                'status' => 'active',
                'email_verified_at' => $user->email_verified_at ?? now(),
            ])->save();
            $user->assignRole($data['role']);
        }

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