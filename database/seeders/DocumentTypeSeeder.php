<?php

namespace Database\Seeders;

use App\Domain\Aggregates\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DocumentType::factory()->count(5)->create();
    }
}
