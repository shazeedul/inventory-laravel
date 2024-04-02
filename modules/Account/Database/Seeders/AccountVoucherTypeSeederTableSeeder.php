<?php

namespace Modules\Account\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Account\Entities\AccountVoucherType;

class AccountVoucherTypeSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        AccountVoucherType::insert([
            0 => [
                'name' => 'Debit Voucher',
                'short_name' => 'DV',
                'is_active' => true,
                'created_at' => now(),
            ],
            1 => [
                'name' => 'Credit Voucher',
                'short_name' => 'CV',
                'is_active' => true,
                'created_at' => now(),
            ],
            2 => [
                'name' => 'Contra Voucher',
                'short_name' => 'TV',
                'is_active' => true,
                'created_at' => now(),
            ],
            3 => [
                'name' => 'Journal Voucher',
                'short_name' => 'JV',
                'is_active' => true,
                'created_at' => now(),
            ],
            4 => [
                'name' => 'Cash Payment Voucher',
                'short_name' => 'CP',
                'is_active' => true,
                'created_at' => now(),
            ],
            5 => [
                'name' => 'Cash Receipt Voucher',
                'short_name' => 'CR',
                'is_active' => true,
                'created_at' => now(),
            ],
            6 => [
                'name' => 'Bank Payment Voucher',
                'short_name' => 'BP',
                'is_active' => true,
                'created_at' => now(),
            ],
            7 => [
                'name' => 'Bank Receipt Voucher',
                'short_name' => 'BR',
                'is_active' => true,
                'created_at' => now(),
            ],
            8 => [
                'name' => 'Note Voucher',
                'short_name' => 'NV',
                'is_active' => true,
                'created_at' => now(),
            ]
        ]);
    }
}
