<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\JobPosition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::create([
            'job_position_uuid' => JobPosition::first()->uuid,
            'name'              => 'Hannah',
            'email'             => 'hannah@mail.com',
            'password'          => Hash::make('hannah123'),
        ]);

        Employee::create([
            'job_position_uuid' => JobPosition::first()->uuid,
            'name' => 'Reed',
            'email' => 'predimolanaz23@gmail.com',
            'password' => Hash::make('hahahaha'),
        ]);
    }
}
