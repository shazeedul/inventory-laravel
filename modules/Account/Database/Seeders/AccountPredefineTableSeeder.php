<?php

namespace Modules\Account\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Account\Entities\AccountPredefine;

class AccountPredefineTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        AccountPredefine::insert([
            0 => [
                'key' => 'cash_code',
                'chart_of_account_id' => 49,
            ],
            1 => [
                'key' => 'purchase_code',
                'chart_of_account_id' => 80,
            ],
            2 => [
                'key' => 'sales_code',
                'chart_of_account_id' => 70,
            ],
            3 => [
                'chart_of_account_id' => 47,
                'key' => 'customer_code',
            ],
            4 => [
                'chart_of_account_id' => 60,
                'key' => 'supplier_code',
            ],
            5 => [
                'chart_of_account_id' => 72,
                'key' => 'costs_of_good_solds',
            ],
            6 => [
                'chart_of_account_id' => 63,
                'key' => 'vat',
            ],
            7 => [
                'chart_of_account_id' => 53,
                'key' => 'inventory_code',
            ],
            8 => [
                'chart_of_account_id' => 65,
                'key' => 'current_year_profit_loss_code',
            ],
            9 => [
                'chart_of_account_id' => 66,
                'key' => 'last_year_profit_loss_code',
            ],
            10 => [
                'chart_of_account_id' => 73,
                'key' => 'sales_discount',
            ],
            11 => [
                'chart_of_account_id' => 71,
                'key' => 'purchase_discount',
            ],
        ]);
    }
}
