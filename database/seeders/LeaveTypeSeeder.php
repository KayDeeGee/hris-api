<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaveTypes = [
            [
                'name' => 'Sick Leave',
                'description' => 'For health-related absences',
                'notice_days' => 0,
                'value' => 5,
            ],
            [
                'name' => 'Vacation Leave',
                'description' => 'For personal time off or travel',
                'notice_days' => 7,
                'value' => 10,
            ],
            [
                'name' => 'Maternity Leave',
                'description' => '60 days of paid leave for childbirth (working days)',
                'notice_days' => 30,
                'value' => 60,
            ],
            [
                'name' => 'Paternity Leave',
                'description' => '7 days of leave for fathers',
                'notice_days' => 7,
                'value' => 7,
            ],
            [
                'name' => 'Bereavement Leave',
                'description' => 'Leave due to the death of a family member',
                'notice_days' => 0,
                'value' => 3,
            ],
        ];

        foreach ($leaveTypes as $type) {
            LeaveType::firstOrCreate(['name' => $type['name']], $type);
        }
    }
}
