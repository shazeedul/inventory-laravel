<?php

namespace Modules\Account\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Account\Entities\FinancialYear;

class FinancialYearTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        FinancialYear::insert([
            0 => [
                'name' => '2021',
                'start_date' => '2021-01-01',
                'end_date' => '2021-12-31',
                'status' => false,
                'is_closed' => true,
                'created_at' => now(),
            ],
            1 => [
                'name' => '2022',
                'start_date' => '2022-01-01',
                'end_date' => '2022-12-31',
                'status' => false,
                'is_closed' => true,
                'created_at' => now(),
            ],
            2 => [
                'name' => '2023',
                'start_date' => '2023-01-01',
                'end_date' => '2023-12-31',
                'status' => false,
                'is_closed' => true,
                'created_at' => now(),
            ],
            3 => [
                'name' => '2024',
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
                'status' => true,
                'is_closed' => false,
                'created_at' => now(),
            ],
        ]);
    }
}
