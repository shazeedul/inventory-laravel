<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Category;

class CategoryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // category seeder with key
        $categories = [
            [
                'name' => 'Category 1',
                'status' => 1,
                'created_by' => 1,
            ],
            [
                'name' => 'Category 2',
                'status' => 1,
                'created_by' => 1,
            ],
            [
                'name' => 'Category 3',
                'status' => 1,
                'created_by' => 1,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
