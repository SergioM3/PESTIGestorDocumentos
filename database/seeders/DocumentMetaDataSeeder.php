<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentMetadataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public static function run(): void
    {
        for ($i = 0; $i < 50; $i++) {
            $data = [
                        "document_id" => Factory::create()->numberBetween(1, \App\Domain\Aggregates\Document\Document::count()),
                        "metadata_type_id" => Factory::create()->numberBetween(1, \App\Domain\Aggregates\Metadata\MetadataType::count()),
                        'value' => Factory::create()->word
                    ];
            DB::table('document_metadata')->insert($data);
        }
    }
}
