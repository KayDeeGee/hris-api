<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\LeaveDetail;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveRequestSeeder extends Seeder
{
    public function run()
    {
        // Make sure you have employees and leave types
        // $employee = Employee::first() ?? Employee::factory()->create();
        $leaveType = LeaveType::first() ?? LeaveType::factory()->create([
            'name' => 'Vacation Leave',
        ]);

        // Create 3 leave requests
        for ($i = 0; $i < 3; $i++) {
            $leaveRequest = LeaveRequest::create([
                'employee_id'   => 10,
                'leave_type_id' => $leaveType->id,
                'total_value'   => rand(1, 3), // total days
                'reason'        => 'Sample reason ' . ($i + 1),
                'status'        => ['pending', 'approved', 'rejected'][array_rand(['pending', 'approved', 'rejected'])],
            ]);

            // Attach leave details (randomly full or half days)
            for ($j = 0; $j < $leaveRequest->total_value; $j++) {
                LeaveDetail::create([
                    'leave_request_id' => $leaveRequest->id,
                    'date'             => Carbon::now()->addDays($j),
                    'length'           => ['full', 'first_half', 'second_half'][array_rand(['full', 'first_half', 'second_half'])],
                ]);
            }
        }
    }
}
