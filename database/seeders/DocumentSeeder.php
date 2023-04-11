<?php

namespace Database\Seeders;

use App\Domain\Aggregates\Document;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Document::factory()->count(5)->create();
    }
}
