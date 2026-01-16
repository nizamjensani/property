<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultPassword = 'password'; // change this

        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'nizamjensanidigital@gmail.com',
                'phone_number' => '0193558159',
                'username' => 'nizamjensani',
                'role' => 'superadmin',
                'first_address' => '10 Jalan 22/1',
                'second_address' => 'Taman Mentakab',
                'email_verified_at' => null,
                'has_email_authentication' => false,
                'postcode' => 24070,
                'city' => 'Mentakab',
                'state' => 'Pahang',
                'password' => Hash::make($defaultPassword),

            ],
        ];
        foreach($users as $user){
            User::updateOrCreate(
                ['email' => $user['email']], // lookup key
                $user // values to update
            );
        }
    }
}
