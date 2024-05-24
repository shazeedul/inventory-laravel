<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\RolePermissionTableSeeder;
use Modules\Account\Database\Seeders\AccountDatabaseSeeder;
use Modules\Setting\Database\Seeders\SettingSeeder;
use Modules\Language\Database\Seeders\LanguageTableSeeder;
use Modules\Customer\Database\Seeders\CustomerDatabaseSeeder;
use Modules\Product\Database\Seeders\ProductDatabaseSeeder;
use Modules\Supplier\Database\Seeders\SupplierDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SettingSeeder::class,
            LanguageTableSeeder::class,
            RolePermissionTableSeeder::class,
            CustomerDatabaseSeeder::class,
            SupplierDatabaseSeeder::class,
            ProductDatabaseSeeder::class,
            AccountDatabaseSeeder::class,
        ]);
        Artisan::call('optimize:clear');
    }
}
