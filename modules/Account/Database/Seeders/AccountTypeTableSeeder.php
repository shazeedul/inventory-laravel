<?php

namespace Modules\Account\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Account\Entities\AccountType;

class AccountTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        AccountType::insert([
            0 => [
                'name' => 'Assets',
                'created_at' => now(),
            ],
            1 => [
                'name' => 'Liabilities',
                'created_at' => now(),
            ],
            2 => [
                'name' => 'Income',
                'created_at' => now(),
            ],
            3 => [
                'name' => 'Expenditure',
                'created_at' => now(),
            ],
            4 => [
                'name' => 'Owner Equity',
                'created_at' => now(),
            ],
        ]);
    }
}
