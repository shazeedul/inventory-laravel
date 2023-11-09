<?php

namespace Modules\Unit\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Unit\Entities\Unit;
use Illuminate\Database\Eloquent\Model;

class UnitDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $units = [
            [
                'name' => 'Unit 1',
                'status' => 1,
                'created_by' => 1,
            ],
            [
                'name' => 'Unit 2',
                'status' => 1,
                'created_by' => 1,
            ],
            [
                'name' => 'Unit 3',
                'status' => 1,
                'created_by' => 1,
            ],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}
