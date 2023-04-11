<?php

namespace Database\Seeders;

use App\Domain\Aggregates\MetaDataType;
use Illuminate\Database\Seeder;

class MetaDataTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MetaDataType::factory()->count(5)->create();
    }
}
