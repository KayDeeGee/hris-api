<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\OvertimeRequest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OvertimeRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $employees = Employee::where('role', 'employee')->pluck('id')->toArray();
        // $managers = Employee::whereIn('role', ['manager', 'hr'])->pluck('id')->toArray();

        $employees = Employee::pluck('id')->toArray();
        $managers = [11];

        foreach (range(1, 20) as $i) {
            OvertimeRequest::create([
                'employee_id' => fake()->randomElement($employees),
                'duration' => fake()->numberBetween(1, 5),
                'reason' => fake()->sentence(),
                'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
                'reviewed_by' => fake()->randomElement($managers),
                'reviewed_at' => now(),
            ]);
        }
    }
}
