<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\SoftDeletes; 
class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('owners')->insert([

            [
                'name' => 'test1',
                'email' => 'test1@test1.com',
                'password' => Hash::make('password123'),
                'created_at' => '2023/01/01 12:34:56'
            ],
            [
                'name' => 'test2',
                'email' => 'test2@test1.com',
                'password' => Hash::make('password123'),
                'created_at' => '2023/01/01 12:34:56'
            ],
            [
                'name' => 'test3',
                'email' => 'test3@test1.com',
                'password' => Hash::make('password123'),
                'created_at' => '2023/01/01 12:34:56'
            ],
            [
                'name' => 'test4',
                'email' => 'test4@test1.com',
                'password' => Hash::make('password123'),
                'created_at' => '2023/01/01 12:34:56'
            ],
            [
                'name' => 'test5',
                'email' => 'test5@test1.com',
                'password' => Hash::make('password123'),
                'created_at' => '2023/01/01 12:34:56'
            ],
            [
                'name' => 'test6',
                'email' => 'test6@test1.com',
                'password' => Hash::make('password123'),
                'created_at' => '2023/01/01 12:34:56'
            ]
        ]);
    }
}
