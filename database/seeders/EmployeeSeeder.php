<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\EmployeeAddress;
use App\Models\EmployeeContact;
use App\Models\EmployeeEmergencyContact;
use App\Models\EmployeeMovement;
use App\Models\EmployeePersonalDetail;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'first_name' => "User{$i}",
                'last_name' => "Last{$i}",
                'email' => "user{$i}@example.com",
                'password' => bcrypt('password'),
            ]);

            $employee = Employee::create([
                'user_id' => $user->id,
                'employee_number' => "EMP00{$i}",
                'job_id' => 1, // assumes job_id 1 exists
                'qr_code_path' => "qr_codes/emp00{$i}.png",
                'status' => 'active',
            ]);

            EmployeePersonalDetail::create([
                'employee_id' => $employee->id,
                'first_name' => "User{$i}",
                'middle_name' => "M",
                'last_name' => "Last{$i}",
                'suffix' => null,
                'gender' => 'male',
                'birthdate' => now()->subYears(20 + $i),
                'civil_status' => 'single',
                'nationality' => 'Filipino',
            ]);

            EmployeeAddress::create([
                'employee_id' => $employee->id,
                'region' => 'NCR',
                'province' => 'Metro Manila',
                'city' => 'Quezon City',
                'barangay' => 'Barangay 123',
                'street' => 'Street 1',
                'house_number' => "12{$i}",
                'zip_code' => '1100',
            ]);

            EmployeeContact::create([
                'employee_id' => $employee->id,
                'type' => "mobile",
                'value' => "0917123456{$i}",
            ]);

            EmployeeEmergencyContact::create([
                'employee_id' => $employee->id,
                'name' => "Emergency Person {$i}",
                'relationship' => 'Parent',
                'contact_number' => "0998123456{$i}",
                'email' => "0998123456{$i}",
                'address' => 'Same address',
            ]);

            EmployeeMovement::create([
                'employee_id' => $employee->id,
                'movement_type' => 'initial_assignment',
                'description' => "Initial assignment for employee {$i}",
                'effective_date' => now(),
                'from_job_id' => 1,
                'to_job_id' => 2,
            ]);
        }
    }
}
