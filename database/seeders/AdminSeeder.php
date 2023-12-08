<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('12341234');
        $records = [
            ['id' => 1, 'name' => 'admin', 'type' => 'admin', 'mobile' => '12341234', 'email' => 'admin@admin.com', 'password' => $password, 'image' => '', 'status' => 1],
        ];

        Admin::insert($records);
    }
}
