<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelantanId = DB::table('states')->where('name', 'Kelantan')->value('id');

        if (! $kelantanId) {
            $this->command?->warn("CitySeeder: 'Kelantan' not found in states table.");
            return;
        }

        // Mukim (seed as "cities") — Kelantan only
        $mukims = [
            // Kota Bharu area
            'Kota Bharu', 'Panji', 'Badang', 'Kubang Kerian', 'Banggu', 'Pendek',
            'Sering', 'Beta', 'Ketereh', 'Kenali', 'Kadok', 'Chekok', 'Salor',

            // Pasir Mas area
            'Pasir Mas', 'Chetok', 'Tendong', 'Apam', 'Kubang Gadong', 'Gual Periok', 'Bunut Susu',

            // Tumpat area
            'Tumpat', 'Wakaf Bharu', 'Pengkalan Kubor', 'Kebakat', 'Geting',

            // Bachok area
            'Bachok', 'Jelawat', 'Gunong', 'Repek', 'Kemasin', 'Pantai Irama',

            // Pasir Puteh area
            'Pasir Puteh', 'Selising', 'Limbongan', 'Gong Datok', 'Bukit Abal',

            // Machang area
            'Machang', 'Pulai Chondong', 'Labok', 'Temangan',

            // Tanah Merah area
            'Tanah Merah', 'Bukit Panau', 'Ulu Kusial', 'Jedok',

            // Kuala Krai area
            'Kuala Krai', 'Olak Jeram', 'Dabong', 'Batu Mengkebang',

            // Gua Musang area
            'Gua Musang', 'Bertam', 'Lojing', 'Pulai',

            // Jeli area
            'Jeli', 'Batu Melintang', 'Kuala Balah',
        ];

        foreach ($mukims as $name) {
            $slug = Str::slug($name);

            DB::table('cities')->updateOrInsert(
                [
                    'state_id' => $kelantanId,
                    // if you don't have 'slug' column, remove this line
                    'slug' => $slug,
                ],
                [
                    'name' => $name,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
