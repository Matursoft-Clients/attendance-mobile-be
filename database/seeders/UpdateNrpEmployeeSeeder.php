<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class UpdateNrpEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees_uuid = Employee::pluck('uuid')->all();

        for ($i = 0; $i < count($employees_uuid); $i++) {
            $employee = Employee::where('uuid', $employees_uuid[$i])->first();
            $employee->update([
                'nrp' => $i,
            ]);
        }
    }
}
