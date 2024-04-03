<?php

namespace Modules\Account\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Account\Entities\ChartOfAccount;

class ChartOfAccountSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        ChartOfAccount::insert([
            0 => [
                'code' => '1',
                'name' => 'Assets',
                'head_level' => 1,
                'parent_id' => 0,
                'account_type_id' => 1,
                'is_cash_nature' => 0,
                'is_bank_nature' => 0,
                'is_budget' => 0,
                'is_depreciation' => 0,
                'is_subtype' => 0,
                'account_sub_type_id' => NULL,
                'is_stock' => 0,
                'is_fixed_asset_schedule' => 0,
                'depreciation_rate' => NULL,
                'note_no' => NULL,
                'asset_code' => NULL,
                'depreciation_code' => NULL,
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'updated_by' => 1,
                'updated_at' => now(),
            ],
            1 => [
                'code' => '2',
                'name' => 'Liabilities',
                'head_level' => 1,
                'parent_id' => 0,
                'account_type_id' => 2,
                'is_cash_nature' => 0,
                'is_bank_nature' => 0,
                'is_budget' => 0,
                'is_depreciation' => 0,
                'is_subtype' => 0,
                'account_sub_type_id' => NULL,
                'is_stock' => 0,
                'is_fixed_asset_schedule' => 0,
                'depreciation_rate' => NULL,
                'note_no' => NULL,
                'asset_code' => NULL,
                'depreciation_code' => NULL,
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'updated_by' => 1,
                'updated_at' => now(),
            ],
            2 => [
                'code' => '3',
                'name' => 'Owner Equity',
                'head_level' => 1,
                'parent_id' => 0,
                'account_type_id' => 2,
                'is_cash_nature' => 0,
                'is_bank_nature' => 0,
                'is_budget' => 0,
                'is_depreciation' => 0,
                'is_subtype' => 0,
                'account_sub_type_id' => NULL,
                'is_stock' => 0,
                'is_fixed_asset_schedule' => 0,
                'depreciation_rate' => NULL,
                'note_no' => NULL,
                'asset_code' => NULL,
                'depreciation_code' => NULL,
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'updated_by' => 1,
                'updated_at' => now(),
            ],
            3 => [
                'code' => '4',
                'name' => 'Profit and Loss',
                'head_level' => 1,
                'parent_id' => 0,
                'account_type_id' => 3,
                'is_cash_nature' => 0,
                'is_bank_nature' => 0,
                'is_budget' => 0,
                'is_depreciation' => 0,
                'is_subtype' => 0,
                'account_sub_type_id' => NULL,
                'is_stock' => 0,
                'is_fixed_asset_schedule' => 0,
                'depreciation_rate' => NULL,
                'note_no' => NULL,
                'asset_code' => NULL,
                'depreciation_code' => NULL,
                'is_active' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'updated_by' => 1,
                'updated_at' => now(),
            ],
        ]);
    }
}
