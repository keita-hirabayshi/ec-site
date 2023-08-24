<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('primary_categories')->insert([
            [
                'name' => '旅先',
                'sort_order' => 1
            ],
            [
                'name' => '食事',
                'sort_order' => 2
            ],
            [
                'name' => '気候',
                'sort_order' => 3
            ],
        ]);
    
        DB::table('secondary_categories')->insert([
            [
                'name' => '国内',
                'sort_order' => 1,
                'primary_category_id' => 1
            ],
            [
                'name' => '海外',
                'sort_order' => 1,
                'primary_category_id' => 1
            ],
            [
                'name' => '和食',
                'sort_order' => 1,
                'primary_category_id' => 2
            ],
            [
                'name' => '洋食',
                'sort_order' => 1,
                'primary_category_id' => 2
            ],
            [
                'name' => 'その他',
                'sort_order' => 1,
                'primary_category_id' => 2
            ],
            [
                'name' => '暑い',
                'sort_order' => 1,
                'primary_category_id' => 3
            ],
            [
                'name' => '涼しい',
                'sort_order' => 1,
                'primary_category_id' => 3
            ],
            [
                'name' => '寒い',
                'sort_order' => 1,
                'primary_category_id' => 3
            ],

        ]);
    }
}
