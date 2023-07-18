<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Branch::create([
            'name'                        => 'Cabang 1',
            'code'                        => 'CBH-1',
            'presence_location_address'   => 'Makam RT 04/03',
            'presence_location_latitude'  => '-7.299449',
            'presence_location_longitude' => '109.499504',
        ]);

        Branch::create([
            'name'                        => 'Cabang 2',
            'code'                        => 'CBH-2',
            'presence_location_address'   => 'Sumampir RT 10/03',
            'presence_location_latitude'  => '-7.300403',
            'presence_location_longitude' => '109.510106',
        ]);

        Branch::create([
            'name'                        => 'Cabang 3',
            'code'                        => 'CBH-3',
            'presence_location_address'   => 'Losari RT 01/04',
            'presence_location_latitude'  => '-7.299027',
            'presence_location_longitude' => '109.540647',
        ]);
    }
}
