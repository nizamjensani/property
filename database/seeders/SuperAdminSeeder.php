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
                'email_verified_at' => null,
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
