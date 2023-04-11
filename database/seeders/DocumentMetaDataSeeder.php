<?php

namespace Database\Seeders;

use App\Domain\Aggregates\DocumentMetaData;
use Illuminate\Database\Seeder;

class DocumentMetaDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DocumentMetaData::factory()->count(5)->create();
    }
}
