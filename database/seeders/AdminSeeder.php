<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Member;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Member::updateOrCreate(
            ['email' => 'admin@pawtopia.com'],
            [ 
                'name' => 'Super Admin',
                'phone' => '0000000000',
                'address' => 'Pawtopia Headquarters',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ]
        );

    }
}
