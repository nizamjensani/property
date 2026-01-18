<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = [
            ['name' => 'Johor', 'code' => 'JHR'],
            ['name' => 'Kedah', 'code' => 'KDH'],
            ['name' => 'Kelantan', 'code' => 'KTN'],
            ['name' => 'Melaka', 'code' => 'MLK'],
            ['name' => 'Negeri Sembilan', 'code' => 'NSN'],
            ['name' => 'Pahang', 'code' => 'PHG'],
            ['name' => 'Perak', 'code' => 'PRK'],
            ['name' => 'Perlis', 'code' => 'PLS'],
            ['name' => 'Pulau Pinang', 'code' => 'PNG'],
            ['name' => 'Sabah', 'code' => 'SBH'],
            ['name' => 'Sarawak', 'code' => 'SWK'],
            ['name' => 'Selangor', 'code' => 'SGR'],
            ['name' => 'Terengganu', 'code' => 'TRG'],
            ['name' => 'Wilayah Persekutuan Kuala Lumpur', 'code' => 'WPKL'],
            ['name' => 'Wilayah Persekutuan Labuan', 'code' => 'WPLB'],
            ['name' => 'Wilayah Persekutuan Putrajaya', 'code' => 'WPPJ'],
        ];

        foreach ($states as $s) {
            DB::table('states')->updateOrInsert(
                ['name' => $s['name']],
                [
                    'code' => $s['code'] ?? null,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
