<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Product\Entities\Product;
use Illuminate\Database\Eloquent\Model;

class ProductDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $products = [
            [
                'name' => 'Product 1',
                'category_id' => 1,
                'unit_id' => 1,
                'status' => 1,
                'created_by' => 1,
            ],
            [
                'name' => 'Product 2',
                'category_id' => 2,
                'unit_id' => 2,
                'status' => 1,
                'created_by' => 1,
            ],
            [
                'name' => 'Product 3',
                'category_id' => 3,
                'unit_id' => 3,
                'status' => 1,
                'created_by' => 1,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
