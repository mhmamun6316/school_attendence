<?php

namespace Database\Seeders;

use App\Models\Admin\Category;
use App\Models\Admin\Package;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['id' => 1, 'name' => 'messenger'],
            ['id' => 2, 'name' => 'mobile sms'],
            ['id' => 3, 'name' => 'whats app'],
            ['id' => 4, 'name' => 'telegram'],
            ['id' => 5, 'name' => 'email'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['id' => $category['id']], $category);
        }
    }
}
