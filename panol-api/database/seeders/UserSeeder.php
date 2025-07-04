<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'demo@sistransporte.com'],
            [
                'name' => 'demo',
                'email' => 'demo@sistransporte.com',
                'password' => Hash::make('12345678'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
