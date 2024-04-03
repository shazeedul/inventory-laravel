<?php

namespace Modules\Account\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Account\Database\Seeders\AccountTypeTableSeeder;
use Modules\Account\Database\Seeders\AccountSubTypeSeederTableSeeder;
use Modules\Account\Database\Seeders\ChartOfAccountSeederTableSeeder;
use Modules\Account\Database\Seeders\AccountVoucherTypeSeederTableSeeder;

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
        $this->call(AccountSubTypeSeederTableSeeder::class);
        $this->call(AccountVoucherTypeSeederTableSeeder::class);
        $this->call(ChartOfAccountSeederTableSeeder::class);
    }
}
