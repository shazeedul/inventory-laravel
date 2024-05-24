<?php

namespace Modules\Account\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Account\Database\Seeders\AccountTypeTableSeeder;
use Modules\Account\Database\Seeders\AccountSubTypeTableSeeder;
use Modules\Account\Database\Seeders\ChartOfAccountTableSeeder;
use Modules\Account\Database\Seeders\AccountVoucherTypeTableSeeder;

class AccountDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(AccountTypeTableSeeder::class);
        $this->call(AccountSubTypeTableSeeder::class);
        $this->call(AccountVoucherTypeTableSeeder::class);
        $this->call(ChartOfAccountTableSeeder::class);
        $this->call(FinancialYearTableSeeder::class);
    }
}
