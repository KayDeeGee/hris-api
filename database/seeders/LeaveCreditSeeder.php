<?php

namespace Database\Seeders;

use App\Models\LeaveCredit;
use App\Models\LeaveType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveCreditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $leaveTypes = LeaveType::all();

        if ($users->isEmpty() || $leaveTypes->isEmpty()) {
            $this->command->warn('⚠️ No users or leave types found. Please seed them first.');
            return;
        }

        foreach ($users as $user) {
            foreach ($leaveTypes as $leaveType) {
                LeaveCredit::create([
                    'user_id'       => $user->id,
                    'leave_type_id' => $leaveType->id,
                    'total_credits' => rand(10, 20),
                    'used_credits'  => rand(0, 5),
                    'year'          => now()->year,
                    'valid_from'    => Carbon::now()->startOfYear(),
                    'valid_until'   => Carbon::now()->endOfYear(),
                    'is_active'     => true,
                    'notes'         => 'Sample seeded leave credit',
                ]);
            }
        }

        $this->command->info('✅ Leave credits seeded successfully!');
    }
}
