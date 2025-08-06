<?php

namespace Database\Seeders;

use App\Models\AttendanceRecord;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class AttendanceRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $employeeId = 1;
        $startDate = Carbon::now()->subMonths(2)->startOfMonth(); // 1st day of 2 months ago
        $endDate = Carbon::now()->endOfMonth(); // End of current month

        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            // Skip weekends
            if ($currentDate->isWeekend()) {
                $currentDate->addDay();
                continue;
            }

            // Random time-in between 8:45 to 9:15
            $timeInHour = 9;
            $timeInMinute = rand(0, 30); // 0 = on time, 30 = very late
            $timeIn = Carbon::createFromTime($timeInHour, $timeInMinute);

            // Standard 8-hour shift with 1-hour break = 7 total work hours
            $timeOut = $timeIn->copy()->addHours(8);

            // Calculate late duration (if after 9:00 AM)
            $lateDuration = $timeIn->gt(Carbon::createFromTime(9, 0))
                ? Carbon::createFromTime(9, 0)->diffInMinutes($timeIn) / 60
                : 0;

            Log::info('Late Duration:', ['late_duration' => $lateDuration]);

            AttendanceRecord::create([
                'employee_id' => $employeeId,
                'date' => $currentDate->toDateString(),
                'time_in' => $timeIn->format('H:i:s'),
                'time_out' => $timeOut->format('H:i:s'),
                'total_hours' => 7.00,
                'late_duration' => round($lateDuration, 2),
            ]);

            $currentDate->addDay();
        }
    }
}
