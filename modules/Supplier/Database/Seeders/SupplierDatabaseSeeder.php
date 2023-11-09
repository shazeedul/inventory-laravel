<?php

namespace Modules\Supplier\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Supplier\Entities\Supplier;

class SupplierDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $suppliers = [
            [
                'name' => 'Supplier 1',
                'email' => 'supplier1@gmail.com',
                'mobile_no' => '0926384755',
                'address' => 'Yangon',
                'status' => 1,
                'created_by' => 1,
            ],
            [
                'name' => 'Supplier 2',
                'email' => 'supplier2@gmail.com',
                'mobile_no' => '0926384756',
                'address' => 'Yangon',
                'status' => 1,
                'created_by' => 1,
            ],
            [
                'name' => 'Supplier 3',
                'email' => 'supplier3@gmail.com',
                'mobile_no' => '0926384757',
                'address' => 'Yangon',
                'status' => 1,
                'created_by' => 1,
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
