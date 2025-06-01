<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Role::create(['name' => 'guest']);
        Role::create(['name' => 'applicant']);
        Role::create(['name' => 'employee']);
        Role::create(['name' => 'admin']);
    }
}
