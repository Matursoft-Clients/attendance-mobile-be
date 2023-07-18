<?php

namespace Database\Seeders;

use App\Models\Branch;
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
            'branch_uuid'       => Branch::inRandomOrder()->first()->uuid,
            'job_position_uuid' => JobPosition::inRandomOrder()->first()->uuid,
            'nik'               => '2821932132174823',
            'name'              => 'Hannah',
            'email'             => 'hannah@mail.com',
            'whatsapp_number'   => '082329321471',
            'password'          => Hash::make('hannah123'),
        ]);

        Employee::create([
            'branch_uuid'       => Branch::inRandomOrder()->first()->uuid,
            'job_position_uuid' => JobPosition::inRandomOrder()->first()->uuid,
            'nik'               => '3249357834958329',
            'name'              => 'Reed',
            'email'             => 'predimolanaz23@gmail.com',
            'whatsapp_number'   => '081234567890',
            'password'          => Hash::make('hahahaha'),
        ]);
    }
}
