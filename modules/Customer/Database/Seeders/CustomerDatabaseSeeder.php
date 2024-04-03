<?php

namespace Modules\Customer\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Account\Entities\AccountSubCode;
use Modules\Customer\Entities\Customer;

class CustomerDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $customers = [
            [
                'name' => 'Customer 1',
                'email' => 'customer1@gmail.com',
                'mobile_no' => '0926384756',
                'address' => 'Yangon',
                'status' => 1,
                'created_by' => 1,
            ],
            [
                'name' => 'Customer 2',
                'email' => 'customer2@gmail.com',
                'mobile_no' => '0926384757',
                'address' => 'Yangon',
                'status' => 1,
                'created_by' => 1,
            ],
            [
                'name' => 'Customer 3',
                'email' => 'customer3@gmail.com',
                'mobile_no' => '0926384753',
                'address' => 'Yangon',
                'status' => 1,
                'created_by' => 1,
            ],
        ];

        foreach ($customers as $customer) {
            $cus = Customer::create($customer);
            AccountSubCode::create([
                'account_sub_type_id' => 3,
                'reference_id' => $cus->id,
                'code' => str_pad($cus->id, 10, '0', STR_PAD_LEFT),
                'name' => $cus->name,
                'status' => true,
            ]);
        }
    }
}
