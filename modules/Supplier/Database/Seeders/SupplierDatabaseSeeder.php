<?php

namespace Modules\Supplier\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Supplier\Entities\Supplier;
use Modules\Account\Entities\AccountSubCode;

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
            $sup = Supplier::create($supplier);
            AccountSubCode::create([
                'account_sub_type_id' => 4,
                'reference_id' => $sup->id,
                'code' => str_pad($sup->id, 10, '0', STR_PAD_LEFT),
                'name' => $sup->name,
                'status' => true,
            ]);
        }
    }
}
